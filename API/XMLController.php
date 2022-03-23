<?php

namespace App\API;

use App\API\ServiceData\ServiceData;
use App\API\ServiceData\UserData;
use App\Http\Controllers\Controller;

abstract class XMLController extends Controller
{
    private $serviceData;
    private $userData;

    public function __construct()
    {
        $this->serviceData = new ServiceData();
        $this->userData = new UserData();
    }

    public function service_data(): array
    {
        return $this->serviceData->serviceData();
    }
    public function user_data(): array
    {
        return $this->userData->userData();
    }

}
