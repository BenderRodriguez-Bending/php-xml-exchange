<?php

namespace App\API\XML\user;


use App\API\XMLController;
use XMLWriter;


class Login extends XMLController
{
    public function createXml(object $data): string
    {
        $serviceData = $this->service_data();

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0');

        $xml->startElement("WebRequest");

        $xml->startElement("Info");
        $xml->writeElement("Source", "b2b");
        $xml->writeElement("Operation", "Login");
        $xml->endElement(); //End the element

        $xml->startElement("ServiceData");
        $xml->writeElement("LocalDateTime", time());
        $xml->writeElement("HTTP_CLIENT_IP", $serviceData->client_ip);
        $xml->writeElement("HTTP_X_FORWARDED_FOR", $serviceData->forwarded_for);
        $xml->writeElement("REMOTE_ADDR", $serviceData->remote_addr);
        $xml->writeElement("Browser", $serviceData->user_agent);
        $xml->endElement(); //End the element

        $xml->startElement("Body");
        $xml->writeElement("User", $data->login);
        $xml->writeElement("Psw", $data->password);
        $xml->endElement(); //End the element

        $xml->endElement(); //End the element
        $xml->endDocument();

        return $xml->outputMemory();
    }
}
