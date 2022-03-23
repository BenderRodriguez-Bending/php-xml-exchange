<?php

namespace App\API\Products;

use App\API\ExchangeXML;
use Exception;
use App\API\XML\XMLIndex;

class AgrFind
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
    public function prolong($data): string
    {
        $xml = $this->XML->xml('AgrFind', 'products\\actions', 'AgrFind', $data);
        return $this->exchange->exchange($xml);
    }

}