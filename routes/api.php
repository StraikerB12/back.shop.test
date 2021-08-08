<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/test', function () {
    return "{test: true}";
});
// ['middleware'=>['frontMiddleware'], 'uses'=>'ApiController@start'] 
Route::match( ['get','post'],  '/front/{method}',  [ApiController::class, 'start'] )->middleware('frontMiddleware');
