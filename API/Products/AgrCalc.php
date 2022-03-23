<?php

namespace App\API\Products;


use App\API\ExchangeXML;
use Exception;
use App\API\XML\XMLIndex;

class AgrCalc
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
    public function calc($data): string
    {
        $xml = $this->XML->xml('AgrCalc', $data);
        return $this->exchange->exchange($xml);
    }

}
