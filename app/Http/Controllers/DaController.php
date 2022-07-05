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
    function da(Request $request){
        if($request->session()->get('type') && $request->session()->get('loginId')){

        $user = DB::table('users')->where("type","acheteur")->get();
        return view('/da',['acheteurs'=>$user]);
        }else{
            return abort('403');
        }
    }

      function add_da(Request $request){

        $request->validate([
            'delai' => 'required',
            'reference' => 'required',
            'acheteur' => 'required',
            'qte' => 'required|integer',
            'code_CC' => 'required|integer',
            'code_NE' => 'required|integer',
            'societe' => 'required'
         ]);


         $da = new DaModel();
         $da->delai = $request->delai;
         $da->reference = $request->reference;
         $da->qte= $request->qte;
         $da->code_CC = $request->code_CC;
         $da->code_NE = $request->code_NE;
         $da->societe = $request->societe;
         $da->id_acheteur = $request->acheteur;
         $da->id_emetteur = $request->session()->get('loginId');
         if($request->session()->get('type')=='emetteur')
         $da->date_emetteur = Carbon::now();
         if($request->session()->get('type')=='chef de service')
         $da->date_chef_service = Carbon::now();
         if($request->session()->get('type')=='directeur')
         $da->date_directeur = Carbon::now();

         $res = $da->save();
         $da_id = DB::table('da_models')->get()->last();
         if($res) {
             $user = DB::table('users')->where("id", '=',$request->session()->get('loginId'))->get()->first();
             $destinaire = DB::table('users')->where("type", "=","chef de service")->where("departement", "=",$user->departement)->get()->first();

             Mail::to($destinaire->email)->send(new DAMail($user->username, $da_id->societe, $user->type,$user->email,"aaaa", "demande d'achat" , $da_id->id));
             return back()->with('success', "you're demand is registered");
         }
         else
         return back()->with('fail',"some error occured at registring your demand");
      }

}
