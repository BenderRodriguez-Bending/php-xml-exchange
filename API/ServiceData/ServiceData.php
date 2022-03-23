<?php


namespace App\API\ServiceData;


class ServiceData
{

    public function serviceData(): array
    {
        if(!empty($_SERVER["HTTP_USER_AGENT"])){
            $user_agent = $_SERVER["HTTP_USER_AGENT"];
        }else{
            $user_agent = null;
        }

        if (!empty($_SERVER["HTTP_CLIENT_IP"])){
            $client_ip = $_SERVER["HTTP_CLIENT_IP"];
        }else{
            $client_ip = null;
        }

        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $forwarded_for = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else{
            $forwarded_for = null;
        }

        if (!empty($_SERVER["REMOTE_ADDR"])){
            $remote_addr = $_SERVER["REMOTE_ADDR"];
        }else{
            $remote_addr = null;
        }

        return [
            'user_agent' => $user_agent,
            'client_ip' => $client_ip,
            'forwarded_for' => $forwarded_for,
            'remote_addr' => $remote_addr
        ];
    }
}
