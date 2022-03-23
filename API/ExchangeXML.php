<?php


namespace App\API;

use App\Models\Logs;
use Exception;
use SimpleXMLElement;
use XMLWriter;

class ExchangeXML
{

    /**
     * @throws Exception
     */
    public function exchange($xml): string
    {
        $domain = env('BACK_URLS');
        $urls =env('BACK_API_V1');
        $curl = curl_init();
        $headers = [];

        curl_setopt_array($curl, array(
            CURLOPT_URL => $domain.$urls,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => 'requestXML='.$xml,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));
        $response = trim(curl_exec($curl));
        $error = trim(curl_error($curl));
        curl_close($curl);

        if ($error) {
            return $error;
        }

        if(substr($response, 0, 5) === "<?xml" || substr($response, 0, 12) === "<WebRequest>"){

            $send_xml = new SimpleXMLElement($xml);
            $xml_response = new SimpleXMLElement($response);
            $code = (int)$xml_response->Error->Code;

            if ((string)$send_xml->Info->Operation === "Login"){
                $user_id = (int)$xml_response->Answer->UserId;
            }else{
                $user_id = (int)$send_xml->Info->UserId;
            }

            $log = Logs::create([
                'user_id'  => $user_id,
                'xml_send' => (string)$xml,
                'name'     => (string)$send_xml->Info->Operation
            ]);

            if($code != 0){
                $log->text_error = (string)$xml_response->Error->Text;
                if($code == 90){
                    auth()->logout();
                }
            }
            $log->code = $code;
            $log->xml_response = $response;
            $log->save();
        }else{
            $response = self::errorXml();
        }
        return $response;
    }

    static function errorXml(): string
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0');
        $xml->startElement("WebRequest");
            $xml->startElement("Error");
                $xml->writeElement("Code", 40);
                $xml->writeElement("Text", "Ошибка обработки запроса. Пожалуйста, повторите запрос.");
            $xml->endElement();
        $xml->endElement();
        $xml->endDocument();
        return $xml->outputMemory();
    }
}
