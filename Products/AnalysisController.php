<?php

namespace App\Http\Controllers\CONSOLIDATION\Products;

use App\API\Index;
use App\Http\Controllers\CONSOLIDATION\SettingsController;
use Exception;
use stdClass;
use Illuminate\Http\JsonResponse;

class AnalysisController implements SettingsController
{
    private $API;

    public function __construct()
    {
        $this->API = new Index();
    }

    /**
     * @throws Exception
     */
    public function analysisResponseCalc($response): string
    {
        $error_code = (int)$response->Error->Code;
        $error_text = (string)$response->Error->Text;
        if ($error_code === 0){
            $contract = $response->Answer->Contract;
            $calc = $response->Answer->Calc;
            $result = [
                "ErrorCode"    => $error_code,
                "ContractId"   => (int)$contract->ContractId,
                "ContractName" => (string)$contract->ContractName,
                "PremiumSum"   => (int)$calc->PremiumSum,
                "TariffStr"    => str_replace('; ', ';<br>', (string)$calc->TariffStr),
            ];
        }elseif ($error_code === 40){
            $result = [
                "ErrorCode" => $error_code,
                "ErrorText" => $error_text
            ];
        }else{
            $result = [
                "ErrorCode" => '',
                "ErrorText" => ''
            ];
        }
        $result['Buttons'] = $this->analysisCalcForButtons($response);
        return json_encode($result);
    }

    /**
     * @throws Exception
     */
    public function analysisResponseConfirm($response): string
    {
        $result = [];
        $result['ErrorCode'] = (int)$response->Error->Code;
        $result['ErrorText'] = (string)$response->Error->Text;

        $result['Buttons'] = $this->analysisConfirmForButtons($response);
        return json_encode($result);
    }

    /**
     * @throws Exception
     */
    public function analysisResponsePrint($response): string
    {
        $error_code = (int)$response->Error->Code;
        $result = [];
        $result['ErrorCode'] = $error_code;
        $result['ErrorText'] = (string)$response->Error->Text;
        if ($error_code === 0){
            $result['PrintDocs'] = $response->Answer->PrintDocs;
        }

        //$result['Buttons'] = $this->analysisCalcForButtons($response);
        return json_encode($result);
    }

    public function analysisResponsePrintDocument($response): string
    {
        $result = new stdClass();

        if(isset($response->Answer->FileName)){
            $contract_id = (string)$response->Info->ContractId;
            $File_n = explode('.', $response->Answer->FileName);
            $file_contents = base64_decode((string)$response->Answer->FileData);
            $path = storage_path('app/temps/'.$contract_id.'/');
            if (!is_dir(($path))){
                mkdir(($path), 0777, true);
            };
            file_put_contents($path . $response->Answer->FileName, $file_contents);
            $result->status = 1;
            $result->types = $File_n[1];
            $result->filename = (string)$response->Answer->FileName;
            $result->contract_id = $contract_id;
        }else{
            (int)$response->Error->Code == 30 ? $result->status = 2 : $result->status = 3;
        }
        if ($response->Error->Text){
            $result->error = (string)$response->Error->Text;
        }
        return json_encode($result);
        //return response()->json($result);
    }

    /**
    анализ расчёта для кнопок
     **/
    private function analysisCalcForButtons($response): array
    {
        $calculate_contract = 1;
        $confirm_contract = 0;
        $check_status_contract = 0;
        $agreement_contract = 0;
        $print_contract = 0;
        $copy_contract = 0;

        $error_code = (int)$response->Error->Code;
        $contract_id = isset($response->Answer->Contract->ContractId) ? (int)$response->Answer->Contract->ContractId : 0;
        $confirm = isset($response->Answer->Contract->Confirm) ? (int)$response->Answer->Contract->Confirm : 0;

        if ($error_code === 0 && $contract_id > 0){
            $confirm_contract = 1;
            $print_contract = 1;
            $copy_contract = 1;
        }
        if (isset($response->Answer->Contract->NeedAccept)){
            $agreement_contract = $response->Answer->Contract->NeedAccept === 'Y' ? 1 : 0;
        }

        return [
            'calculate_contract' => $calculate_contract,
            'confirm_contract' => $confirm_contract,
            'check_status_contract' => $check_status_contract,
            'agreement_contract' => $agreement_contract,
            'print_contract' => $print_contract,
            'copy_contract' => $copy_contract
        ];
    }

    /**
     * @throws Exception
     */
    public function analysisResponseProlong($response): string
    {
        $error_code = (int)$response->Error->Code;
        $error_text = (string)$response->Error->Text;
        if ($error_code === 0){
            $contract = $response->Answer->Contract;
            $result = [
                "ErrorCode"    => $error_code,
                "ContractId"   => (int)$contract->ContractId,
                "Route"   => route('product', [self::URL_SLUG_NAME[(string)$response->Info->Product]]) ,
                "ContractName" => (string)$contract->ContractName,
                "ContractProduct" => (string)$contract->ContractProduct,
                "ContractProductTitle" => (string)$contract->ContractProductTitle,
                "Data" => $this->parse_data($response->Answer),
            ];
        }elseif ($error_code === 40){
            $result = [
                "ErrorCode" => $error_code,
                "ErrorText" => $error_text
            ];
        }else{
            $result = [
                "ErrorCode" => '',
                "ErrorText" => ''
            ];
        }
        $result['Buttons'] = $this->analysisCalcForButtons($response);
        return json_encode($result);
    }

    private function parse_data($data): array
    {
        $index = 1;
        $contract = [];
        foreach ($data as $items){
            if (isset($items->Contract[0])){
                foreach ($items->Contract[0] as $name => $value){
                    $contract[$name] = (string)$value;
                }
            }
            if (isset($items->Insurer[0])){
                foreach ($items->Insurer[0] as $name => $value){
                    $name = (string)$name === 'Fullname' ? 'FullName' : $name;
                    $contract['Insurer['.$name.']'] = (string)$value;
                }
            }
            if (isset($items->AddPersons[0])){
                foreach ($items->AddPersons[0]->AddPerson[0] as $name => $value){
                    $contract['AddPerson['.$index.']['.$name.']'] = (string)$value;
                }
                $index++;
            }
        }
        return $contract;
    }

    private function analysisConfirmForButtons($response): array
    {
        //dd($response);
        $calculate_contract = null;
        $confirm_contract = null;
        $check_status_contract = null;
        $agreement_contract = null;
        $print_contract = null;
        $copy_contract = null;

        $error_code = (int)$response->Error->Code;

        if ($error_code === 0 || $error_code === 70){
            $calculate_contract = 0;
            $confirm_contract = 0;
            $check_status_contract = 0;
            $agreement_contract = 0;
            $print_contract = 1;
            $copy_contract = 1;
        }
        if ($error_code === 40){
            $calculate_contract = 1;
            $confirm_contract = 1;
            $check_status_contract = 0;
            $agreement_contract = 0;
            $print_contract = 0;
            $copy_contract = 0;
        }

        return [
            'calculate_contract' => $calculate_contract,
            'confirm_contract' => $confirm_contract,
            'check_status_contract' => $check_status_contract,
            'agreement_contract' => $agreement_contract,
            'print_contract' => $print_contract,
            'copy_contract' => $copy_contract
        ];
    }


}
