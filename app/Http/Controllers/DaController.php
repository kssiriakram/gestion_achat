<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DaModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DAMail;
use App\Mail\DAMail_manager;
use App\Mail\DAMail_directeur;
class DaController extends Controller
{

    function get_dashboard(){
        $total_demandes = DAModel::count();
        $total_demandes_encours = DAModel::where('validation_directeur',null)->count();
        $total_demandes_cloture = DAModel::where('validation_directeur','<>',null)->count();
        $total_demandes_jour = DAModel::whereDate('created_at',Carbon::now()->format('d-m-Y'))->count();
        $total_demandes_month = DAModel::whereMonth('created_at', '=', Carbon::now()->month)->count();
        $total_demandes_year = DAModel::whereYear('created_at', '=', Carbon::now()->year)->count();

        $total_demandes_per_month = [0,0,0,0,0,0,0,0,0,0,0,0];

        $data = DB::table('da_models')->
        select(DB::raw("count(id) data"),  DB::raw( "MONTH(created_at) month"))
            ->groupby(DB::raw( "MONTH(created_at)"))->orderby(DB::raw( "MONTH(created_at)"),"asc")->get();

            for($i=0;$i<$data->count();$i++){
              $total_demandes_per_month[$data[$i]->month-1] = $data[$i]->data;
            }


        return view('dashboard',['total_demandes' => $total_demandes
        ,'total_demandes_encours' => $total_demandes_encours,
        'total_demandes_cloture' => $total_demandes_cloture,
        'total_demandes_jour' => $total_demandes_jour,
        'total_demandes_month' => $total_demandes_month,
        'total_demandes_year' => $total_demandes_year,
        'total_demandes_per_month' =>  $total_demandes_per_month]);
    }
    function nouvelledm(){

        $user = DB::table('users')->where("type","acheteur")->get();
        $var = DB::table('da_models')->get()->last();
        $im = $var ?  $var->id+1 : 1;
        return view('/nouvelledm',['acheteurs'=>$user,'id'=>$im]);
    }

    function get_encours_dm(Request $request){
        $dm= DB::table('da_models')->where('id_emetteur','=',$request->session()->get('loginId'))->get();


        return view('encoursdm',['items'=>$dm]);
    }

    function get_cloture_dm(Request $request){
        $dm= DB::table('da_models')->where('id_emetteur','=',$request->session()->get('loginId'))->
        where('validation_directeur','=',true)->get();

        return view('cloture',['items'=>$dm]);
    }

    function get_cloture_dm_manager(Request $request){
        $dm=  DB::table('users')->join('da_models','users.id','=','da_models.id_emetteur')
        ->where('users.departement','=',$request->session()->get('departement'))->
        where('da_models.validation_directeur','=',true)->get();

        return view('manager_cloture',['items'=>$dm]);
    }

    function get_cloture_dm_directeur(Request $request){
        $dm= DB::table('da_models')->where('validation_directeur','=',true)->get();

        return view('directeur_cloture',['items'=>$dm]);
    }

      function add_dm(Request $request){

        $request->validate([
            'delai' => 'required',
            'reference' => 'required',
            'acheteur' => 'required',
            'quantite' => 'required|integer',
            'ccout' => 'required|integer',
            'cnecono' => 'required|integer',
            'societe' => 'required',
            'file' => 'required'
         ]);

         $da = new DaModel();

         $da->delai =Carbon::parse($request->delai);
         $da->reference = $request->reference;
         $da->qte= $request->quantite;
         $da->code_CC = $request->ccout;
         $da->code_NE = $request->cnecono;
         //$da->societe = $request->societe;
         $da->id_acheteur = $request->acheteur;

         $image_name = time().'-logo.'.$request->file->getClientOriginalExtension();
         $request->file->move(public_path('uploads'), $image_name);
         $da->file = $image_name;

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


  /*    function get_da_manager($id){
        $da = DB::table('da_models')->where('id','=',$id)->get()->first();
        $acheteur = DB::table('users')->where('id','=',$da->id_acheteur)->get()->first();
        return view('da_manager' , ['da' => $da , 'acheteur' => $acheteur]);
      }*/

      function get_encours_dm_manager(Request $request){
       // dd(DB::table('users')->where('departement','=',$request->session()->get('departement'))->get());


        $dm= DB::table('users')->join('da_models','users.id','=','da_models.id_emetteur')
        ->where('users.departement','=',$request->session()->get('departement'))->get();


        return view('manager_encoursdm',['items'=>$dm]);
    }

    function get_nouvelle_dm_manager($id){
        $dm=DB::table('da_models')->where('id',$id)->get()->first();
        $user=DB::table('users')->where('id',$dm->id_acheteur)->get()->first();

        return view('manager_nouvelledm',['acheteurs'=>$user , 'dm'=>$dm] );
     }



     function add_dm_manager(Request $request){

        $request->validate([
            'delai' => 'required',
            'reference' => 'required',
            'acheteur' => 'required',
            'quantite' => 'required|integer',
            'ccout' => 'required|integer',
            'cnecono' => 'required|integer',
            'observation' => 'required',
            'validation' => 'required|in:yes,no',

         ]);

    // $da = DB::table('users')->where('id','=',$request->id)->get()->first();
    $da = DaModel::find($request->id);
      $da->commentaire_manager = $request->observation;

      if($request->validation=="yes"){   $da->validation_manager=true;}
      else $da->validation_manager=false;

     $da->date_chef_service = Carbon::now();

      $da->save();

     if($da->validation_manager){
        $user= DB::table('users')->where("type", "=","manager")->where("departement", "=",$request->session()->get('departement'))->get()->first();
        $destinaire = DB::table('users')->where("type", "=","directeur")->get()->first();
        Mail::to($destinaire->email)->send(new DAMail_manager($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$request->observation));
     return back()->with('success', "you're demand is registered");
     }
    else
    {

        $user= DB::table('users')->where("type", "=","manager")->where("departement", "=",$request->session()->get('departement'))->get()->first();
        $destinaire= DB::table('users')->join('da_models','users.id','=','da_models.id_emetteur')->get()->first();
        Mail::to($destinaire->email)->send(new DAMail_manager($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$request->observation));
        return back()->with('success', "you're demand is registered");

    }
    }


    function get_encours_dm_directeur(){

        $dm = DB::table('da_models')->where('validation_manager','=',true)->get();
        return view('directeur_encoursdm',['items'=>$dm]);
    }

    function get_nouvelle_dm_directeur($id){
        $dm=DB::table('da_models')->where('id',$id)->get()->first();
        $user=DB::table('users')->where('id',$dm->id_acheteur)->get()->first();

        return view('directeur_nouvelledm',['acheteurs'=>$user , 'dm'=>$dm] );
     }

     function add_dm_directeur(Request $request){

        $request->validate([
            'delai' => 'required',
            'reference' => 'required',
            'acheteur' => 'required',
            'quantite' => 'required|integer',
            'ccout' => 'required|integer',
            'cnecono' => 'required|integer',
            'observation' => 'required',
            'validation' => 'required|in:yes,no',

         ]);

    // $da = DB::table('users')->where('id','=',$request->id)->get()->first();
    $da = DaModel::find($request->id);

      $da->commentaire_directeur = $request->observation;

      if($request->validation=="yes"){   $da->validation_directeur=true;}
      else $da->validation_directeur=false;

     $da->date_directeur = Carbon::now();

      $da->save();

     if($da->validation_directeur){


        $user= DB::table('users')->where("type", "=","directeur")->get()->first();
        $destinaire = DB::table('users')->where("type", "=","acheteur")->get()->first();
        Mail::to($destinaire->email)->send(new DAMail_directeur($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$da->commentaire_manager,$request->observation));
     return back()->with('success', "you're demand is registered");
     }
    else
    {
        $emetteur =User::find($da->id_emetteur)->get()->first();

        $manager = User::where('departement','=',$emetteur->departement)->where('type','=','manager')->get()->first();

        $user= DB::table('users')->where("type", "=","directeur")->get()->first();

       // Mail::to($emetteur->email)->send(new DAMail($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id));
        Mail::to($manager->email)->send(new DAMail_directeur($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$da->commentaire_manager,$request->observation));
        return back()->with('success', "you're demand is registered");

    }
    }

    function get_encours_dm_acheteur(Request $request){
        $dm= DB::table('da_models')->where('id_acheteur','=',$request->session()->get('loginId'))->get();


        return view('acheteur_encours',['items'=>$dm]);
    }

    function get_nouvelle_dm_acheteur($id){
        $dm=DB::table('da_models')->where('id',$id)->get()->first();
        $user=DB::table('users')->where('id',$dm->id_acheteur)->get()->first();

        return view('acheteur_nouvelledm',['acheteurs'=>$user , 'dm'=>$dm] );
     }

}
