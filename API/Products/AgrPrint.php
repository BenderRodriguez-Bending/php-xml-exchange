<?php

namespace App\API\Products;

use App\API\ExchangeXML;
use Exception;
use App\API\XML\XMLIndex;

class AgrPrint
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
    public function print($data): string
    {
        $xml = $this->XML->xml('AgrPrint', $data);
        return $this->exchange->exchange($xml);
    }
}
