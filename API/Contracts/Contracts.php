<?php


namespace App\API\Contracts;


use App\API\ExchangeXML;
use App\API\ServiceData\ServiceData;
use App\API\ServiceData\UserData;
use http\Env\Request;
use XMLWriter;
use SimpleXMLElement;

class Contracts
{
    private $exchange;

    public function __construct()
    {
        $this->exchange = new ExchangeXML();
    }

    public function load($request)
    {
        $query = self::createXml($request);
        $response = $this->exchange->exchange($query);
        $contracts = new SimpleXMLElement($response);

        if (sizeof($contracts)){
            return json_encode([
                'res' => 1,
                'count_contracts' => sizeof($contracts),
                'contracts' => $contracts
            ]);
        }else{
            return json_encode([
                'res' => 0
            ]);
        }
    }

    static function createXml($request)
    {
        $serviceData = ServiceData::serviceData();
        $userData = UserData::userData();
        //create a new xmlwriter object
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument();

        $xml->startElement("WebRequest");

        $xml->startElement("Info");
        $xml->writeElement("UserId", $userData->user_id);
        $xml->writeElement("SessionId", $userData->session_id);
        $xml->writeElement("Source", "b2b");
        $xml->writeElement("Operation", "AgrList");
        $xml->writeElement("Product", $request->product_agr_name);
        $xml->endElement(); //End the element

        $xml->startElement("ServiceData");
        $xml->writeElement("LocalDateTime", time());
        $xml->writeElement("HTTP_CLIENT_IP", $serviceData->client_ip);
        $xml->writeElement("HTTP_X_FORWARDED_FOR", $serviceData->forwarded_for);
        $xml->writeElement("REMOTE_ADDR", $serviceData->remote_addr);
        $xml->writeElement("Browser", $serviceData->user_agent);
        $xml->endElement(); //End the element

        $xml->startElement("Body");
        $xml->writeElement("PageNo", $request->pageNo);
        $xml->writeElement("RowOnPage", $request->rowOnPage);
        $xml->writeElement("Status", $request->status);
        $xml->writeElement("ContractName", $request->contractName);
        $xml->writeElement("InsurerName", $request->insurerName);
        $xml->writeElement("BsoNumber", $request->bsoNumber);
        $xml->writeElement("DateSignFrom", $request->dateSignFrom ? date('d.m.Y', strtotime($request->dateSignFrom)) : "" );
        $xml->writeElement("DateSignTo", $request->dateSignTo ? date('d.m.Y', strtotime($request->dateSignTo)) : "");
        $xml->writeElement("DateBegFrom", $request->dateBegFrom ? date('d.m.Y', strtotime($request->dateBegFrom)) : "");
        $xml->writeElement("DateBegTo", $request->dateBegTo ? date('d.m.Y', strtotime($request->dateBegTo)) : "");
        $xml->writeElement("DateEndFrom", $request->dateEndFrom ? date('d.m.Y', strtotime($request->dateEndFrom)) : "");
        $xml->writeElement("DateEndTo", $request->dateEndTo ? date('d.m.Y', strtotime($request->dateEndTo)) : "");
        $xml->endElement(); //End the element

        $xml->endElement(); //End the element
        $xml->endDocument();

        return $xml->outputMemory();
    }
}
