<?php

namespace App\API\XML;


use App\API\ServiceData\UserData;
use App\API\XMLController;

class XMLInfo extends XMLController
{

    public function XMLInfo($xml, $type, $data)
    {
        $userData = $this->user_data();

        if (isset($data->ContractId)){
            $ContractId = $data->ContractId;
        }elseif (isset($data->Info->ContractId)){
            $ContractId = $data->Info->ContractId;
        }elseif (isset($data->Answer->ContractId)){
            $ContractId = $data->Answer->ContractId;
        }else{
            $ContractId = null;
        }

        $xml->startElement("Info");
        if ($data->Product){
            $xml->writeElement("Product", (string)$data->Product);
        }
        if ($type){
            $xml->writeElement("Operation", (string)$type);
        }
        if ($ContractId !== null){
            $xml->writeElement("ContractId", (int)$ContractId);
        }
        $xml->writeElement("Source", "b2b");
        if ($userData['user_id']){
            $xml->writeElement("UserId", (int)$userData['user_id']);
        }
        if ($userData['session_id']){
            $xml->writeElement("SessionId", (string)$userData['session_id']);
        }
        $xml->endElement();
    }

}
