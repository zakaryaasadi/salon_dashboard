<?php

use App\Customer;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', 'BookingController@get')
->name('index');
Route::get('/booking/{id}', 'BookingController@view');
Route::post('/booking/{id}', 'BookingController@approve')
        ->name("approve");


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
