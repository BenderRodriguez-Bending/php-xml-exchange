<?php

namespace App\API;

use App\Http\Controllers\Controller;

abstract class XMLBodyController extends Controller
{
    abstract static function bodyXml($xml, $data);
}
