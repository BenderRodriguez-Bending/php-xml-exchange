<?php

namespace App\API\XML\person;


use App\API\XMLController;
use XMLWriter;

class Search extends XMLController
{
    public function createXml($data): string
    {
        $data = (object)$data;
        $serviceData = $this->service_data();
        $userData = $this->user_data();

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument();

        $xml->startElement("WebRequest");

        $xml->startElement("Info");
        $xml->writeElement("Source", "b2b");
        $xml->writeElement("Operation", "SubjFind");
        $xml->writeElement("UserId", $userData->user_id);
        $xml->writeElement("SessionId", $userData->session_id);
        $xml->endElement();

        $xml->startElement("ServiceData");
        $xml->writeElement("LocalDateTime", time());
        $xml->writeElement("HTTP_CLIENT_IP", $serviceData->client_ip);
        $xml->writeElement("HTTP_X_FORWARDED_FOR", $serviceData->forwarded_for);
        $xml->writeElement("REMOTE_ADDR", $serviceData->remote_addr);
        $xml->writeElement("Browser", $serviceData->user_agent);
        $xml->endElement();

        $xml->startElement("Body");
        $xml->startElement("Subject");

        foreach ($data as $item){
            $xml->writeElement($item['name'], $item['value']);
        }

        $xml->endElement();
        $xml->endElement();

        $xml->endElement();
        $xml->endDocument();

        return $xml->outputMemory();
    }
}
