<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;




class Product extends Controller{

  public $request;
  protected $response = [ 'data' => [], 'error' => [] ];

  public function __construct(Request $request){
    $this->request = $request;
  }


  public function get(){

    $this->response['data'] = ['34'];

    return $this->response;
  }
}