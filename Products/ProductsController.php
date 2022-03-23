<?php

namespace App\Http\Controllers\CONSOLIDATION\Products;

use App\API\Index;
use App\Http\Controllers\CONSOLIDATION\SettingsController;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use SimpleXMLElement;
use DB;


class ProductsController extends Controller implements SettingsController
{

    private $API;

    public function __construct()
    {
        $this->middleware('auth');
        $this->API = new Index();
    }


    private function includeModels($product)
    {
        return (object)Products::FormPage($product);
    }


    public function index($product)
    {
        $product = convertUrl($product);
        if (DB::table('check_points')->where('user_id', Auth::id())->where('access', $product)->exists()){
            return view('Consolidation.Products.index', [
                'product' => $product,
                'name_product' => self::PRODUCTS[$product],
                'forms' => (array)$this->includeModels($product),
                'scr' => 'products'
            ]);
        }else{
            return view('errors.no_access')->with(['message' => 'Доступ к продукту запрещен. Обратитесь к куратору']);
        }
    }

    /**
     * @throws Exception
     */

    public function calc(Request $data): string
    {
        return new SimpleXMLElement($this->API->calc($data));
    }


    /**
     * @throws Exception
     */

    public function confirm(Request $data): string
    {
        return new SimpleXMLElement($this->API->confirm($data));
    }

    /**
     * @throws Exception
     */

    public function print(Request $data): string
    {
        return new SimpleXMLElement($this->API->print($data));
    }

    /**
     * @throws Exception
     */
    public function print_document(Request $data, $counter = 1): string
    {
        $response = new SimpleXMLElement($this->API->print_document($data));
        if((int)$response->Error->Code === 30 && $counter <= 6){
            sleep(5);
            return $this->print_document($data, $counter++);
        }
        return $this->analysis->analysisResponsePrintDocument($response);
    }

    /**
     * @throws Exception
     */
    public function search_prolong(Request $data): string
    {
        return new SimpleXMLElement($this->API->search_prolong($data));
    }

    /**
     * @throws Exception
     */
    public function agr_prolong(Request $data): string
    {
        return new SimpleXMLElement($this->API->agr_prolong($data));
    }

}
