<?php

namespace App\API;

use App\API\XML\PieceXML\XMLAddress;
use App\API\XML\PieceXML\XMLCar;
use App\API\XML\PieceXML\XMLContract;
use App\API\XML\PieceXML\XMLPerson;
use App\API\XML\PieceXML\XMLInsured;
use App\Http\Controllers\Controller;

abstract class XMLBodyController extends Controller
{
    abstract static function bodyXml($xml, $data);
}
