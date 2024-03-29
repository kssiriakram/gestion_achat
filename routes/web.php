<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaController;
use App\Http\Controllers\Tab_comparatifController;
use App\Http\Middleware\Authorization;
use App\Http\Middleware\Emetteur;
use  App\Http\Middleware\Manager;
use  App\Http\Middleware\Directeur;
use  App\Http\Middleware\Acheteur;
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

Route::get('/retourne_acheteur/{id}', [DaController::class,'get_retourne_acheteur']);
Route::post('/acheteur_edit_dm', [DaController::class,'acheteur_edit_dm']);
Route::get('/retourne_manager/{id}', [DaController::class,'get_retourne_manager']);
Route::post('/manager_edit_tab',[Tab_comparatifController::class,'manager_edit_tab']);
Route::post('/manager_edit_dm', [DaController::class,'manager_edit_dm']);
Route::get('/acheteur_nouvelletab/{id}',[Tab_comparatifController::class,'get_nouvelle_tab_acheteur']);
Route::post('/acheteur_edit_tab',[Tab_comparatifController::class,'acheteur_edit_tab']);


Route::get('/manager_nouvelledm/{id}',[DaController::class,'get_nouvelle_dm_manager']);
Route::get('/manager_nouvelletab/{id}',[Tab_comparatifController::class,'get_nouvelle_tab_manager']);
Route::post('/manager_add_dm',[DaController::class,'add_dm_manager']);
Route::post('/manager_add_tab',[Tab_comparatifController::class,'manager_add_tab']);
Route::get('/retourne_directeur/{id}', [DaController::class,'get_retourne_directeur']);
Route::post('/directeur_edit_dm', [DaController::class,'directeur_edit_dm']);

Route::get('/acheteur_nouvelledm/{id}',[DaController::class,'get_nouvelle_dm_acheteur']);
Route::post('/acheteur_add_dm',[DaController::class,'add_dm_acheteur']);
Route::get('/nouveau_tab_comparatif/{id}',[Tab_comparatifController::class,'get_nouveau_tab_comparatif']);
Route::post('/add_tab_comparatif',[Tab_comparatifController::class,'add_tab_comparatif']);
Route::get('/retourne_managers_tab/{id}',[Tab_comparatifController::class,'get_retourne_tab_manager']);

Route::post('/directeur_add_dm',[DaController::class,'add_dm_directeur']);
Route::post('/directeur_add_tab',[Tab_comparatifController::class,'directeur_add_tab']);
Route::get('/directeur_nouvelletab/{id}',[Tab_comparatifController::class,'get_nouvelle_tab_directeur']);
Route::get('/directeur_nouvelledm/{id}',[DaController::class,'get_nouvelle_dm_directeur']);

Route::get('/dashboard',[DaController::class,'get_dashboard']);


Route::middleware(Authorization::class)->namespace('\App\Http\Controllers\Api')->group(function(){

    Route::get('/valide/{id}',[DaController::class,'get_valide_dm']);

Route::middleware(Manager::class)->namespace('\App\Http\Controllers\Api')->group(function () {
    Route::get('/manager_encoursdm',[DaController::class,'get_encours_dm_manager']);
    Route::get('/manager_encourstab',[Tab_comparatifController::class,'get_encours_tab_manager']);

    Route::get('/manager_cloture',[DaController::class,'get_cloture_dm_manager']);
    Route::get('/retourne_directeurs', [DaController::class,'get_retourne_directeurs']);
    Route::get('/retourne_directeurs_tab', [Tab_comparatifController::class,'get_retourne_directeurs']);

});


Route::middleware(Emetteur::class)->namespace('\App\Http\Controllers\Api')->group(function(){
    Route::get('/nouvelledm',[DaController::class,'nouvelledm']);
    Route::post('/add_dm',[DaController::class,'add_dm']);

//Route::get('/da_manager/{id}',[DaController::class,'get_da_manager']);
Route::get('/encoursdm',[DaController::class,'get_encours_dm']);
Route::get('/cloture',[DaController::class,'get_cloture_dm']);
Route::get('/retourne_acheteurs', [DaController::class,'get_retourne_acheteurs']);
Route::get('/retourne_managers', [DaController::class,'get_retourne_managers']);

});

Route::middleware(Acheteur::class)->namespace('\App\Http\Controllers\Api')->group(function(){


    //Route::get('/da_manager/{id}',[DaController::class,'get_da_manager']);
    Route::get('/acheteur_encoursdm',[DaController::class,'get_encours_dm_acheteur']);
    Route::get('/retourne_managerstab',[Tab_comparatifController::class,'get_retourne_managers']);
    Route::get('/acheteur_encourstab',[Tab_comparatifController::class,'get_encours_tab_acheteur']);

    });

Route::middleware(Directeur::class)->namespace('\App\Http\Controllers\Api')->group(function () {


    Route::get('/directeur_cloture',[DaController::class,'get_cloture_dm_directeur']);
    Route::get('/directeur_encoursdm',[DaController::class,'get_encours_dm_directeur']);
    Route::get('/directeur_encourstab',[Tab_comparatifController::class,'get_encours_tab_directeur']);


});




//Route::get('/da',[DaController::class,'da']);
/*Route::get('/registration',[AuthController::class,'registration']);*/
//Route::post('/register',[AuthController::class,'register']);

});
