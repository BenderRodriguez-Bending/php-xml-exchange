<?php

namespace App\API\XML;


use App\API\XMLController;


class XMLServiceData extends XMLController
{

    public function XMLServiceData($xml)
    {
        $serviceData = $this->service_data();

        $xml->startElement("ServiceData");
        $xml->writeElement("LocalDateTime", time());
        $xml->writeElement("HTTP_CLIENT_IP", $serviceData['client_ip']);
        $xml->writeElement("HTTP_X_FORWARDED_FOR", $serviceData['forwarded_for']);
        $xml->writeElement("REMOTE_ADDR", $serviceData['remote_addr']);
        $xml->writeElement("Browser", $serviceData['user_agent']);
        $xml->endElement();
    }
}
