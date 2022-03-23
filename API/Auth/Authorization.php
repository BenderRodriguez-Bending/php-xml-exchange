<?php


namespace App\API\Auth;


use App\Models\User;
use App\API\ExchangeXML;
//use App\API\Auth\xml\Login;
use App\API\XML\user\Login;
use Exception;
use SimpleXMLElement;
use stdClass;
use App\Models\CheckPoint;



class Authorization
{

    private $exchange;
    private $auth;

    public function __construct()
    {
        $this->exchange = new ExchangeXML();
        $this->auth = new Login();
    }

    /**
     * @throws Exception
     */
    public function login($login, $password):   object
    {
        $data = [
            'login' => $login,
            'password' => $password
        ];
        $xml = $this->auth->createXml((object)$data);
        $response = $this->exchange->exchange($xml);
        $error = new stdClass();
        $error->id = 0;
        $error->error = "Ошибка соединения с сервером";
        if (substr($response, 0, 5) == "<?xml"){
            $xml_response = new SimpleXMLElement($response);
            $error_code = (integer)$xml_response->Error->Code;
            $error_text = (string)$xml_response->Error->Text;
            if ($error_code === 0){
                $user_id = (integer)$xml_response->Answer->UserId;
                $user_name = (string)$xml_response->Answer->User;
                $user_fullname = (string)$xml_response->Answer->UserFullname;
                $user_email = (string)$xml_response->Answer->UserEmail;
                $user_curator_email = (string)$xml_response->Answer->CuratorEmail;
                $user_session_back_id = (string)$xml_response->Answer->SessionId;
                $user_is_admin = (integer)$xml_response->Answer->Accesses->apB2BSystem->apB2BAdmin;
                $access = $xml_response->Answer->Accesses;

                if (User::where('id', $user_id)->exists()){
                    User::where('id', $user_id)
                        ->update([
                            'name' => $user_name,
                            'name_full' => $user_fullname,
                            'login' => $login,
                            'session_back_id' => $user_session_back_id,
                            'email' => $user_email,
                            'curator_email' => $user_curator_email,
                            'is_admin' => $user_is_admin
                        ]);
                }else{
                    $user = new User();
                    $user->id = $user_id;
                    $user->name = $user_name;
                    $user->name_full = $user_fullname;
                    $user->login = $login;
                    $user->session_back_id = $user_session_back_id;
                    $user->email = $user_email;
                    $user->curator_email = $user_curator_email;
                    $user->role_id = 1;
                    $user->is_admin = $user_is_admin;
                    $user->save();
                }
                $this->userCheckPoint($user_id, $access);
                return User::find($user_id);
            }else{
                $error->id = 0;
                $error->error = $error_text;
            }
        }
        return (object)$error;
    }

    private function userCheckPoint($user_id, $access)
    {
        $user_id = (int)$user_id;
        $access = (array)$access;
        CheckPoint::where('user_id', $user_id)->delete();
        $points = [];
        if (array_key_exists('apB2BSystem', $access)){
            if ((int)$access['apB2BSystem']->apB2BAdmin === 1){
                $points[] = [
                    'user_id' => $user_id,
                    'access' => 'IsAdmin',
                    'calculator' => null,
                    'confirm' => null
                ];
            }
        }
        foreach (array_keys((array)$access) AS $point){
            $calculator = isset($access[$point]->Calc[0]) ? (int)$access[$point]->Calc[0] : 0;
            $confirm = 1;
            if ((int)$access[$point]->AgrCalc[0] === 1){
                $points[] = [
                    'user_id' => $user_id,
                    'access' => $point,
                    'calculator' => $calculator,
                    'confirm' => $confirm
                ];
            }
        }
        CheckPoint::insert($points);
    }
}
