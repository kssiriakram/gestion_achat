<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaController;
use App\Http\Middleware\Authorization;

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
//Route::view('/mail', 'trymail');
Route::get('/', function () {
    return view('welcome');
});
Route::get('/login',[AuthController::class,'login']);
Route::post('/signin',[AuthController::class,'signin']);
Route::post('/logout',[AuthController::class,'logout']);

Route::middleware(Authorization::class)->namespace('\App\Http\Controllers\Api')->group(function(){
Route::view('/dashboard','dashboard-blog');
Route::get('/nouvelledm',[DaController::class,'nouvelledm']);
Route::post('/add_dm',[DaController::class,'add_dm']);
Route::get('/da_manager/{id}',[DaController::class,'get_da_manager']);
Route::get('/encoursdm',[DaController::class,'get_encours_dm']);
Route::get('/cloture',[DaController::class,'get_cloture_dm']);
Route::get('/manager/encoursdm',[DaController::class,'get_encours_dm_manager']);
Route::get('/manager/nouvelledm/{id}',[DaController::class,'get_nouvelle_dm_manager']);
Route::view('/manager_cloture',"manager_cloture");
//Route::get('/da',[DaController::class,'da']);
/*Route::get('/registration',[AuthController::class,'registration']);*/
//Route::post('/register',[AuthController::class,'register']);

});
