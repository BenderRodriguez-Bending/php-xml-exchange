<?php

namespace App\API\XML\products\actions;

use App\API\XMLBodyController;

class AgrFind extends XMLBodyController
{
    static function bodyXml($xml, $data)
    {
        $xml->startElement("Contract");
            if (isset($data->ContractName) && !empty($data->ContractName)){
                $xml->writeElement("ContractName", $data->ContractName);
            }
            if (isset($data->BsoNumber) && !empty($data->BsoNumber)){
                $xml->writeElement("BsoNumber", $data->BsoNumber);
            }
        $xml->endElement();

        $xml->startElement("Insurer");
            if (isset($data->SubjectType) && !empty($data->SubjectType)){
                $xml->writeElement("SubjectType", $data->SubjectType);
            }
            if (isset($data->Fullname) && !empty($data->Fullname)){
                $xml->writeElement("Fullname", $data->Fullname);
            }
            if (isset($data->Birthday) && !empty($data->Birthday)){
                $xml->writeElement("Birthday", $data->Birthday);
            }
            if (isset($data->INN) && !empty($data->INN)){
                $xml->writeElement("INN", $data->INN);
            }
        $xml->endElement();

        $xml->startElement("Car");
            if (isset($data->Vin) && !empty($data->Vin)){
                $xml->writeElement("Vin", $data->Vin);
            }
            if (isset($data->PtsNumber) && !empty($data->PtsNumber)){
                $xml->writeElement("PtsNumber", $data->PtsNumber);
            }
            if (isset($data->RegNumber) && !empty($data->RegNumber)){
                $xml->writeElement("RegNumber", $data->RegNumber);
            }
        $xml->endElement();
    }
}