<?php

namespace App\API\Products;

use App\API\ExchangeXML;
use App\API\XML\XMLIndex;
use Exception;

class AgrConfirm
{
    private $XML;
    private $exchange;

    public function __construct()
    {
        $this->XML = new XMLIndex();
        $this->exchange = new ExchangeXML();
    }

    /**
     * @throws Exception
     */
    public function confirm($data): string
    {
        $xml = $this->XML->xml('AgrConfirm', $data);
        return $this->exchange->exchange($xml);
    }
}
