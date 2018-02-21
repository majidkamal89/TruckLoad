<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
// Company Routes
Route::get('company/list', 'CompanyController@loadData')->middleware('api');
Route::post('company/save', 'CompanyController@store')->middleware('api');
Route::get('company/delete/{id}', 'CompanyController@destroy')->middleware('api');
//

// Driver login Route
Route::post('login', 'UserController@driverLogin')->middleware('api');

// Loads routes
Route::group(array('before' => 'jwt-auth'), function()
{
    Route::get('masterData/{type}', 'LoadController@getAllLoad')->middleware('api');

    //truck loads methods
   // Route::get('/load', 'LoadController@getAllLoadList')->middleware('api');

    Route::get('/load/{trucklistid}/{loadid}', 'LoadController@getLoadList')->middleware('api');
    Route::post('/load/{trucklistid}', 'LoadController@store')->middleware('api');

    Route::post('load/{trucklistid}/{loadid}', 'LoadController@update')->middleware('api');
    Route::delete('load/{trucklistid}/{loadid}', 'LoadController@destroy')->middleware('api');

    // Truck list methods
    Route::get('/trucklist', 'LoadController@getAllTruckList')->middleware('api');
    Route::get('/trucklist/{trucklistid}','LoadController@getTruckListByID')->middleware('api');
    Route::post('/trucklist', 'TrucklistController@store')->middleware('api');
    Route::post('trucklist/{id}', 'TrucklistController@update')->middleware('api');
    Route::delete('trucklist/{id}', 'TrucklistController@destroy')->middleware('api');


    Route::get('/project/{cus_id}', 'TrucklistController@getAllProjects')->middleware('api');
    Route::get('/customer', 'TrucklistController@getAllCustomers')->middleware('api');
    Route::get('/vehicle', 'TrucklistController@getAllVehicles')->middleware('api');

    Route::get('getLoad', 'TrucklistController@getLoads')->middleware('api');
    Route::get('getVolum', 'TrucklistController@getVolum')->middleware('api');
    Route::get('allimages/{trucklistid}', 'TrucklistController@getAllImagesById')->middleware('api');
    Route::get('delSignature/{trucklistid}', 'TrucklistController@delSignature')->middleware('api');
    Route::delete('delAttachements/{trucklistid}', 'TrucklistController@delAttachements')->middleware('api');

    Route::get('/logout', 'UserController@destroyJwt')->middleware('api');

    Route::post('/attachment/save', 'TrucklistController@storeAttachment')->middleware('api');
    Route::post('/attachment/save/front', 'TrucklistController@storeSignatureFront')->middleware('api');

});

