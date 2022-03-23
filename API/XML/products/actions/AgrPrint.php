<?php

namespace App\API\XML\products\actions;

use App\API\XMLBodyController;

class AgrPrint extends XMLBodyController
{
    static function bodyXml($xml, $data)
    {
        $xml->startElement("Contract");
        $xml->endElement();
    }
}
