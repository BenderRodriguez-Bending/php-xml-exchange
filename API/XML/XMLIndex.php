<?php

namespace App\API\XML;


use XMLWriter;

class XMLIndex
{
    private $XMLInfo;
    private $XMLServiceData;

    public function __construct()
    {
        $this->XMLServiceData = new XMLServiceData();
        $this->XMLInfo = new XMLInfo();
    }

    public function xml($type, $data): string
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0');
            $xml->startElement("WebRequest");
                $this->XMLInfo->XMLInfo($xml, $type, $data);
                $this->XMLServiceData->XMLServiceData($xml);
                $xml->startElement("Body");
                    XMLBody::bodyXml($xml, $data);
                $xml->endElement();
            $xml->endElement();
        $xml->endDocument();
        return $xml->outputMemory();
    }
}
