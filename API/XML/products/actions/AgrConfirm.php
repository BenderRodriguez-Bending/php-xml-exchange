<?php

namespace App\API\XML\products\actions;

use App\API\XMLBodyController;
use Illuminate\Support\Facades\DB;

class AgrConfirm extends XMLBodyController
{
    static function bodyXml($xml, $data)
    {
        $xml->startElement("Contract");
        $xml->writeElement("TypePolis", $data->TypePolis);
        $xml->writeElement("TypePay", $data->TypePay);
        if (isset($data->SendEmail)){
            $xml->writeElement("SendEmail", $data->SendEmail);
        }
        if (isset($data->EmailValidCode)){
            $xml->writeElement("EmailValidCode", $data->EmailValidCode);
        }
        $xml->endElement();
    }
}
