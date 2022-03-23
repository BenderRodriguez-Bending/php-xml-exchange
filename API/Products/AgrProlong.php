<?php

namespace App\API\Products;

use App\API\ExchangeXML;
use Exception;
use App\API\XML\XMLIndex;

class AgrProlong
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
        $xml = $this->XML->xml('AgrProlong', 'products\\actions', 'AgrProlong', $data);
        return $this->exchange->exchange($xml);
    }

}