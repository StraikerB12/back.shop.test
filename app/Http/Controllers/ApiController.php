<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// use App\Http\Controllers\api\users;


class ApiController extends Controller
{
    public $request;
    protected $usesApi = "App\Http\Controllers\api\\";


    public function __construct(Request $request){
        $this->request = $request;
    }

    public function start($method){

        if( strpos($method, ".") !== false ){
            $metodElements = explode(".", $method);
            $nameClass = $this->usesApi.$metodElements[0];

            if( class_exists($nameClass) ){
                $class = new $nameClass($this->request); 

                if( method_exists($class, $metodElements[1]) ){
                    $method = $metodElements[1];
                    return response()->json( $class->$method() );
                }
            }
        }
        abort(404);
    }
}
