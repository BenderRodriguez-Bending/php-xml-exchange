<?php


namespace App\API;

use App\API\Products\AgrCalc;
use App\API\Products\AgrConfirm;
use App\API\Products\AgrPrint;
use App\API\Products\AgrPrintDoc;
use App\API\Products\AgrProlong;
use Exception;

class Index
{

    private $calc;
    private $confirm;
    private $print;
    private $print_document;
    private $search_prolong;

    public function __construct()
    {
        $this->calc = new AgrCalc();
        $this->confirm = new AgrConfirm();
        $this->print = new AgrPrint();
        $this->print_document = new AgrPrintDoc();
        $this->agr_prolong = new AgrProlong();
    }

    /**
     * @throws Exception
     */
    public function calc($request): string
    {
        return $this->calc->calc($request);
    }

    /**
     * @throws Exception
     */
    public function confirm($request): string
    {
        return $this->confirm->confirm($request);
    }

    /**
     * @throws Exception
     */
    public function print($request): string
    {
        return $this->print->print($request);
    }

    /**
     * @throws Exception
     */
    public function print_document($request): string
    {
        return $this->print_document->print_document($request);
    }

    /**
     * @throws Exception
     */
    public function search_prolong($request): string
    {
        return $this->search_prolong->prolong($request);
    }

    /**
     * @throws Exception
     */
    public function agr_prolong($request): string
    {
        return $this->agr_prolong->prolong($request);
    }


}
