<?php


namespace App\API\Person;


use App\API\ExchangeXML;
use App\API\XML\person\Search;
use App\API\XML\XMLIndex;
use Exception;
use SimpleXMLElement;
use Request;

class Person
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
    public function search($request): string
    {
        $xml = $this->XML->xml('SubjFind', $request);
        return $this->exchange->exchange($xml);
    }
}
