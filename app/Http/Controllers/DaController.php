<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DaModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DAMail;
class DaController extends Controller
{
    function nouvelledm(){

        $user = DB::table('users')->where("type","acheteur")->get();
        $im = DB::table('da_models')->get()->last() ? DB::table('da_models')->get()->last() : 0;

        return view('/nouvelledm',['acheteurs'=>$user,'id'=>$im]);
    }

    function get_encours_dm(Request $request){
        $dm= DB::table('da_models')->where('id_emetteur','=',$request->session()->get('loginId'))->get();


        return view('encoursdm',['items'=>$dm]);
    }

    function get_cloture_dm(Request $request){
        $dm= DB::table('da_models')->where('id_emetteur','=',$request->session()->get('loginId'))->get();

        return view('cloture',['items'=>$dm]);
    }

      function add_dm(Request $request){

        $request->validate([
            'delai' => 'required',
            'reference' => 'required',
            'acheteur' => 'required',
            'quantite' => 'required|integer',
            'ccout' => 'required|integer',
            'cnecono' => 'required|integer',
            'societe' => 'required'
         ]);

         $da = new DaModel();
         $da->delai = $request->delai;
         $da->reference = $request->reference;
         $da->qte= $request->quantite;
         $da->code_CC = $request->ccout;
         $da->code_NE = $request->cnecono;
         //$da->societe = $request->societe;
         $da->id_acheteur = $request->acheteur;
         $da->id_emetteur = $request->session()->get('loginId');
         if($request->session()->get('type')=='emetteur')
         $da->date_emetteur = Carbon::now();


         $res = $da->save();
         $da_id = DB::table('da_models')->get()->last();
         if($res) {
             $user = DB::table('users')->where("id", '=',$request->session()->get('loginId'))->get()->first();
             $destinaire = DB::table('users')->where("type", "=","manager")->where("departement", "=",$user->departement)->get()->first();
            Mail::to($destinaire->email)->send(new DAMail($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $da_id->id));


             return back()->with('success', "you're demand is registered");
         }
         else
         return back()->with('fail',"some error occured at registring your demand");
      }


      function get_da_manager($id){
        $da = DB::table('da_models')->where('id','=',$id)->get()->first();
        $acheteur = DB::table('users')->where('id','=',$da->id_acheteur)->get()->first();
        return view('da_manager' , ['da' => $da , 'acheteur' => $acheteur]);
      }

}
