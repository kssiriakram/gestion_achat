<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaController;

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
Route::view('/mail', 'trymail');
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[AuthController::class,'login']);
Route::get('/registration',[AuthController::class,'registration']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/signin',[AuthController::class,'signin']);
Route::get('/da',[DaController::class,'da']);
Route::post('/add_da',[DaController::class,'add_da']);

Route::get('/email', function(){
    return new \App\Mail\DAMail('fyrifik', 'gyg o', 'Capgemini', "chef", "kssiriakram@gmail.com" ,"jfioevjpo","demadnde d'achat" ,3);
});
