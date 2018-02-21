<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function (){
    Route::get('/', 'HomeController@index')->name('home');

    Route::group(['prefix' => '/', 'middleware' => 'supperAdmin'], function(){
        Route::get('companies', 'CompanyController@index')->name('allCompany');
        Route::get('/create/user/{id}', 'UserController@create')->name('createUser');
        Route::get('/edit/user/{id}', 'UserController@edit')->name('editUser');
    });
    Route::post('/create/user/save', 'UserController@store')->name('storeUser');

    Route::group(['middleware' => 'impersonate'], function()
    {
        Route::group(['middleware' => 'Manager'], function(){
            // For Impersonate user
            Route::get('/manager', 'HomeController@index');

            // Customer Routes
            Route::group(['prefix' => '/'], function(){
                Route::get('customers', 'CustomerController@index')->name('listCustomers');
                Route::get('customer/list', 'CustomerController@loadData');
                Route::post('customer/save', 'CustomerController@store');
                Route::get('customer/delete/{id}', 'CustomerController@destroy');
            });
            //

            // Project Routes
            Route::group(['prefix' => 'project'], function(){
                Route::get('/{customer_id}', 'ProjectController@index')->name('listProjects');
                Route::get('/detail/{id}', 'ProjectController@show')->name('projectDetail');
                Route::post('/save', 'ProjectController@store')->name('storeProject');
                Route::get('/delete/{id}', 'ProjectController@destroy');
            });
            //

            // Settings page Routes
            Route::group(['prefix' => 'settings'], function(){
                // Driver Routes
                Route::get('/driver', 'UserController@index')->name('listDrivers');
                Route::get('/driver/list', 'UserController@getAllDriver')->name('getAllDriver');
                Route::get('/driver/delete/{id}', 'UserController@destroy');

                // Scale Routes
                Route::get('/scale', 'MasterController@scaleIndex')->name('scale');
                Route::get('/list/{type}', 'MasterController@getAllSettings')->name('getAllSettings');
                Route::post('/save', 'MasterController@store')->name('storeSetting');
                Route::get('/delete/{id}', 'MasterController@destroy');

                // Mass Type Routes
                Route::get('/massType', 'MasterController@massTypeIndex')->name('massType');

                // Vehicle Routes
                Route::get('/vehicle', 'MasterController@vehicleIndex')->name('vehicle');
                //Setting - Company route
                Route::get('/company', 'MasterController@showCompany')->name('getCompany');
                Route::get('/company/list', 'CompanyController@loadData')->name('getUserCompany');
                Route::post('/company/save', 'CompanyController@store');
            });
            //

            // Truck list and Truck load Routes
            Route::group(['prefix' => 'lasslister'], function(){
                Route::get('/', 'TrucklistController@index')->name('lasslister');
                Route::get('/list', 'TrucklistController@getAllTruckList')->name('getAllTruck');
                Route::get('/customers', 'TrucklistController@getAllCustomers');
                Route::get('/drivers', 'TrucklistController@getAllDrivers');
                Route::get('/projects/{customer_id}', 'TrucklistController@getAllProjects');
                Route::get('/vehicles/{project_id}', 'TrucklistController@getAllVehicles');
                Route::post('/save', 'TrucklistController@store')->name('storeLasslister');
                Route::get('/delete/{id}', 'TrucklistController@destroy');
                Route::get('/loads/{truckListId}', 'LoadController@viewLoads')->name('viewLoads');
                Route::get('/loads/list/{truckListId}', 'LoadController@getAllLoads')->name('getAllLoads');
                Route::post('/loads/list/{truckListId}', 'LoadController@getAllLoads');
                Route::get('/masterData/{type}', 'LoadController@getMasterData');
                Route::post('/loads/save', 'LoadController@saveLoadData')->name('saveLoadData');
                Route::get('/loads/delete/{id}', 'LoadController@destroy');
                Route::post('/attachment/save', 'TrucklistController@storeAttachment')->name('storeAttachment');

                ///
                Route::get('/pdf/{id}', 'TrucklistController@createPdf')->name('truckListPdf');
                Route::get('/download/pdf/{name}', 'LoadController@downloadPdf');
            });
        });

    });

    // Login As Routes
    Route::get('/users/impersonate/{id}', 'UserController@impersonate');
    Route::get('/users/stop', 'UserController@stopImpersonate');
    //
});
Route::get('/', 'HomeController@index');
