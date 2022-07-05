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


         $res = $da->save();
         $da_id = DB::table('da_models')->get()->last();
         if($res) {
             $user = DB::table('users')->where("id", '=',$request->session()->get('loginId'))->get()->first();
             $destinaire = DB::table('users')->where("type", "=","manager")->where("departement", "=",$user->departement)->get()->first();
            Mail::to($destinaire->email)->send(new DAMail($user->username, $da_id->societe, $user->type,$user->email,"", "demande d'achat" , $da_id->id));

            /* $url = "https://script.google.com/macros/s/AKfycbwR-nRPHikbPTn_q6TiWSzV8TSfkfiXlBCLEiN9Ti7Nqks8OPS1j_dv7Oj15JsMWFtd/exec";
             $ch = curl_init($url);
             curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_POSTFIELDS => http_build_query([
                   "recipient" => $destinaire->email,
                   "subject"   => "demande d'achat",
                   "body"      => " Une nouvelle demande d'achat est recu
                   "
                ])
             ]);
             $result = curl_exec($ch);
             echo $result;
*/
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
