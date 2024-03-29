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
use App\Mail\DAMail_acheteur_refus;
use App\Mail\DAMail_directeur_refus;
use App\Mail\DAMail_edit_acheteur;
use App\Mail\DAMail_manager_refus;
use App\Mail\DAMail_edit_manager;
use App\Models\Ligne_da;
use App\Mail\DAMail_edit_directeur;


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

/*************************************EMETTEUR****************************************** */
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

    function get_retourne_acheteurs(Request $request){
        $dm= DB::table('da_models')->where('id_emetteur','=',$request->session()->get('loginId'))
        ->where('validation_acheteur','=',false)
        ->where('date_acheteur','<>',NULL)
        ->get();
        return view('retourner_acheteurs',['items'=>$dm]);
    }

    function get_retourne_acheteur($id){
        $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$id)->get();
        $user=DB::table('users')->where('id',$dm[0]->id_acheteur)->get()->first();
        $emetteur= DB::table('users')->where('id',$dm[0]->id_emetteur)->get()->first();
        $manager= DB::table('users')->where("type", "=","manager")->where("departement", "=",$emetteur->departement)->get()->first();


        return view('retourner_acheteur',['acheteurs'=>$user , 'dm'=>$dm,'emetteur' => $emetteur, 'manager'=> $manager]);

    }

    function get_cloture_dm(Request $request){
        $dm= DB::table('da_models')->where('id_emetteur','=',$request->session()->get('loginId'))->
        where('validation_directeur','=',true)->get();

        return view('cloture',['items'=>$dm]);
    }

    function add_dm(Request $request){

        $request->validate([

             'reference' => 'required|array|min:1',
             'reference.*' => 'required',
             'designation' => 'required|array|min:1',
             'designation.*' => 'required',
             'acheteur' => 'required',
             'quantite' => 'required|array|min:1',
             'quantite.*' => 'required|integer',
             'cnecono' => 'required|array|min:1',
             'cnecono.*' => 'required',
             'societe' => 'required',


          ]);




          $da = new DaModel();

          if($request->delai)
          $da->delai =Carbon::parse($request->delai);

          if($request->fournisseur)
          $da->fournisseur = $request->fournisseur;

          $da->id_acheteur = $request->acheteur;
          $da->id_emetteur = $request->session()->get('loginId');
          if($request->session()->get('type')=='emetteur')
          $da->date_emetteur = Carbon::now()->format('Y-d-m H:i:s');
          $res = $da->save();

         for($i=0;$i<count($request->quantite);$i++){
         $da_ligne=new Ligne_da();
         $da_ligne->designation = $request->designation[$i];
          $da_ligne->reference = $request->reference[$i];
          $da_ligne->qte= $request->quantite[$i];

          if($request->ccout[$i])
          $da_ligne->code_CC = $request->ccout[$i];

          $da_ligne->code_NE = $request->cnecono[$i];



          if(isset($request->file[$i])){

          $image_name = time().'-logo.'.$request->file[$i]->getClientOriginalExtension();
          $request->file[$i]->move(public_path('uploads'), $image_name);
          $da_ligne->file = $image_name;

          }

          $da_ligne->id_da = $da->id;
           $da_ligne->save();
         }
          //$da->societe = $request->societe;
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

       function acheteur_edit_dm(Request $request){

        if(isset($request->file)){
        $files=[];
        $i=0;
        $keys = array_keys($request->file);

        while($i<count($keys)){
            if($i==0){
                for($j=0;$j<$keys[0]-1;$j++)
                $files[$j]=NULL;

                $files[$keys[0]-1]=$request->file[$keys[0]];
            }

            else{
                $n=$keys[$i]-$keys[$i-1];
                if($n==1)
                {

                    end($files);
                    $key=key($files);
                    $files[$key+1]=NULL;

                }
                if($n==2)
                {
                end($files);
                $key=key($files);
                $files[$key+1]=$request->file[$keys[$i]];
                }
                else{

                    for($j=0;$j<$n-2;$j++){
                        end($files);
                        $key=key($files);
                        $files[$key+1]=NULL;
                    }

                    end($files);
                    $key=key($files);
                    $files[$key+1]=$request->file[$keys[$i]];
                }
            }


        $i++;
        }
    }

        $request->validate([

            'reference' => 'required|array|min:1',
            'reference.*' => 'required',
            'designation' => 'required|array|min:1',
            'designation.*' => 'required',
            'quantite' => 'required|array|min:1',
            'quantite.*' => 'required|integer',
            'cnecono' => 'required|array|min:1',
            'cnecono.*' => 'required',
         ]);


         $da = DaModel::find($request->id);
         $da->date_emetteur = Carbon::now()->format('Y-d-m H:i:s');
         $da->date_acheteur = NULL;
         $res = $da->save();


         $da_ligne=Ligne_da::where("id_da", '=',$request->id)->get();
         //DB::table('ligne_das')->where("id_da", '=',$request->id)->get();

         for($i=0;$i<count($request->quantite);$i++){

            $da_ligne[$i]->designation = $request->designation[$i];
             $da_ligne[$i]->reference = $request->reference[$i];
             $da_ligne[$i]->qte= $request->quantite[$i];

             if($request->ccout[$i])
             $da_ligne[$i]->code_CC = $request->ccout[$i];

             $da_ligne[$i]->code_NE = $request->cnecono[$i];


             if(isset($files[$i]) && $files[$i]!==NULL){



             $image_name = time().'-logo.'.$files[$i]->getClientOriginalExtension();
             $files[$i]->move(public_path('uploads'), $image_name);
             $da_ligne[$i]->file = $image_name;

             }
              $da_ligne[$i]->save();
            }

            $da_id = DAModel::find($request->id);



            if($res) {
                $user = DB::table('users')->where("id", '=',$da_id->id_emetteur)->get()->first();

                $destinaire = DB::table('users')->where("id", '=',$da_id->id_acheteur)->get()->first();

               Mail::to($destinaire->email)->send(new DAMail_edit_acheteur($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $da_id->id));


                return back()->with('success', "you're demand is registered");
            }
            else
            return back()->with('fail',"some error occured at registring your demand");

       }


       function get_retourne_managers(Request $request){

        $dm= DB::table('da_models')->where('id_emetteur','=',$request->session()->get('loginId'))
        ->where('validation_manager','=',false)
        ->where('date_chef_service','<>',NULL)
        ->get();


        return view('retourner_managers',['items'=>$dm]);
    }

    function get_retourne_manager($id){
        $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$id)->get();
        $user=DB::table('users')->where('id',$dm[0]->id_acheteur)->get()->first();
        $emetteur= DB::table('users')->where('id',$dm[0]->id_emetteur)->get()->first();
        $manager= DB::table('users')->where("type", "=","manager")->where("departement", "=",$emetteur->departement)->get()->first();


        return view('retourner_manager',['acheteurs'=>$user , 'dm'=>$dm,'emetteur' => $emetteur, 'manager'=> $manager]);

    }


    function manager_edit_dm(Request $request){
        $files=[];
        if(isset($request->file)){


        $i=0;
        $keys = array_keys($request->file);

        while($i<count($keys)){
            if($i==0){
                for($j=0;$j<$keys[0]-1;$j++)
                $files[$j]=NULL;

                $files[$keys[0]-1]=$request->file[$keys[0]];
            }

            else{
                $n=$keys[$i]-$keys[$i-1];
                if($n==1)
                {

                    end($files);
                    $key=key($files);
                    $files[$key+1]=NULL;

                }
                if($n==2)
                {
                end($files);
                $key=key($files);
                $files[$key+1]=$request->file[$keys[$i]];
                }
                else{

                    for($j=0;$j<$n-2;$j++){
                        end($files);
                        $key=key($files);
                        $files[$key+1]=NULL;
                    }

                    end($files);
                    $key=key($files);
                    $files[$key+1]=$request->file[$keys[$i]];
                }
            }


        $i++;
        }


    }





        $request->validate([

            'reference' => 'required|array|min:1',
            'reference.*' => 'required',
            'designation' => 'required|array|min:1',
            'designation.*' => 'required',
            'quantite' => 'required|array|min:1',
            'quantite.*' => 'required|integer',
            'cnecono' => 'required|array|min:1',
            'cnecono.*' => 'required',
         ]);


         $da = DaModel::find($request->id);
         $da->date_emetteur = Carbon::now()->format('Y-d-m H:i:s');
         $da->date_chef_service = NULL;
         $res = $da->save();


         $da_ligne=Ligne_da::where("id_da", '=',$request->id)->get();
         //DB::table('ligne_das')->where("id_da", '=',$request->id)->get();

         for($i=0;$i<count($request->quantite);$i++){

            $da_ligne[$i]->designation = $request->designation[$i];
             $da_ligne[$i]->reference = $request->reference[$i];
             $da_ligne[$i]->qte= $request->quantite[$i];

             if($request->ccout[$i])
             $da_ligne[$i]->code_CC = $request->ccout[$i];

             $da_ligne[$i]->code_NE = $request->cnecono[$i];


             if(isset($files[$i]) && $files[$i]!==NULL){
             $image_name = time().'-logo.'.$files[$i]->getClientOriginalExtension();
             $files[$i]->move(public_path('uploads'), $image_name);
             $da_ligne[$i]->file = $image_name;

             }
              $da_ligne[$i]->save();
            }

            $da_id = DAModel::find($request->id);



            if($res) {
                $user = DB::table('users')->where("id", '=',$da_id->id_emetteur)->get()->first();

                $destinaire = DB::table('users')
                ->where("departement", '=',$user->departement)
                ->where("type", '=',"manager")
                ->get()->first();



               Mail::to($destinaire->email)->send(new DAMail_edit_manager($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $da_id->id));


                return back()->with('success', "you're demand is registered");
            }
            else
            return back()->with('fail',"some error occured at registring your demand");

       }



       ////////////////////////////////////////////////////////////////////////////////////


       function directeur_edit_dm(Request $request){

        if(isset($request->file)){
        $files=[];
        $i=0;
        $keys = array_keys($request->file);

        while($i<count($keys)){
            if($i==0){
                for($j=0;$j<$keys[0]-1;$j++)
                $files[$j]=NULL;

                $files[$keys[0]-1]=$request->file[$keys[0]];
            }

            else{
                $n=$keys[$i]-$keys[$i-1];
                if($n==1)
                {

                    end($files);
                    $key=key($files);
                    $files[$key+1]=NULL;

                }
                if($n==2)
                {
                end($files);
                $key=key($files);
                $files[$key+1]=$request->file[$keys[$i]];
                }
                else{

                    for($j=0;$j<$n-2;$j++){
                        end($files);
                        $key=key($files);
                        $files[$key+1]=NULL;
                    }

                    end($files);
                    $key=key($files);
                    $files[$key+1]=$request->file[$keys[$i]];
                }
            }


        $i++;
        }
    }







        $request->validate([

            'reference' => 'required|array|min:1',
            'reference.*' => 'required',
            'designation' => 'required|array|min:1',
            'designation.*' => 'required',
            'quantite' => 'required|array|min:1',
            'quantite.*' => 'required|integer',
            'cnecono' => 'required|array|min:1',
            'cnecono.*' => 'required',
         ]);


         $da = DaModel::find($request->id);
         $da->date_chef_service = Carbon::now()->format('Y-d-m H:i:s');
         $da->date_directeur = NULL;
         $res = $da->save();


         $da_ligne=Ligne_da::where("id_da", '=',$request->id)->get();
         //DB::table('ligne_das')->where("id_da", '=',$request->id)->get();

         for($i=0;$i<count($request->quantite);$i++){

            $da_ligne[$i]->designation = $request->designation[$i];
             $da_ligne[$i]->reference = $request->reference[$i];
             $da_ligne[$i]->qte= $request->quantite[$i];

             if($request->ccout[$i])
             $da_ligne[$i]->code_CC = $request->ccout[$i];

             $da_ligne[$i]->code_NE = $request->cnecono[$i];


             if(isset($files[$i]) && $files[$i]!==NULL){
             $image_name = time().'-logo.'.$files[$i]->getClientOriginalExtension();
             $files[$i]->move(public_path('uploads'), $image_name);
             $da_ligne[$i]->file = $image_name;

             }
              $da_ligne[$i]->save();
            }

            $da_id = DAModel::find($request->id);



            if($res) {
                $emetteur= DB::table('users')->where("id", '=',$da_id->id_emetteur)->get()->first();

                $user= DB::table('users')->where("type", '=','manager')
                ->where("departement", '=',$emetteur->departement)
                ->get()->first();

                $destinaire = DB::table('users')
                ->where("id", '=',$da_id->id_directeur)
                ->get()->first();



               Mail::to($destinaire->email)->send(new DAMail_edit_directeur($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $da_id->id,$da_id->commentaire_manager));


                return back()->with('success', "you're demand is registered");
            }
            else
            return back()->with('fail',"some error occured at registring your demand");

       }

       function get_retourne_directeurs(Request $request){

        $dm=  DB::table('users')->join('da_models','users.id','=','da_models.id_emetteur')
        ->where('users.departement','=',$request->session()->get('departement'))->
        where('da_models.validation_directeur','=',false)
        ->where('date_directeur','<>',NULL)->get();



        return view('retourner_directeurs',['items'=>$dm]);
    }


    function get_retourne_directeur($id){
        $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$id)->get();
        $user=DB::table('users')->where('id',$dm[0]->id_acheteur)->get()->first();
        $emetteur= DB::table('users')->where('id',$dm[0]->id_emetteur)->get()->first();
        $manager= DB::table('users')->where("type", "=","manager")->where("departement", "=",$emetteur->departement)->get()->first();


        return view('retourner_directeur',['acheteurs'=>$user , 'dm'=>$dm,'emetteur' => $emetteur, 'manager'=> $manager]);

    }


    function get_cloture_dm_manager(Request $request){

        $dm=  DB::table('users')->join('da_models','users.id','=','da_models.id_emetteur')
        ->where('users.departement','=',$request->session()->get('departement'))->
        where('da_models.validation_directeur','=',true)->get();

        return view('manager_cloture',['items'=>$dm]);
    }

    function get_cloture_dm_directeur(Request $request){
        $dm= DB::table('da_models')->where('validation_directeur','=',true)
        ->where('id_directeur','=',$request->session()->get('loginId'))->get();

        return view('directeur_cloture',['items'=>$dm]);
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
        $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$id)->get();

        $user=DB::table('users')->where('id',$dm[0]->id_acheteur)->get()->first();
        $directeur = DB::table('users')->where("type", "=","directeur")->get();
        $emetteur= DB::table('users')->where('id',$dm[0]->id_emetteur)->get()->first();

        return view('manager_nouvelledm',['acheteurs'=>$user , 'dm'=>$dm , 'emetteur' => $emetteur , 'directeur' => $directeur  ] );
     }



     function add_dm_manager(Request $request){

        $request->validate([
            'observation' => 'required',
            'validation' => 'required|in:yes,no',

         ]);

    // $da = DB::table('users')->where('id','=',$request->id)->get()->first();
    $da = DaModel::find($request->id);
    $departement = DB::table('users')->where('id',$da->id_emetteur)->get()->first()->departement;

      $da->commentaire_manager = $request->observation;

      $da->id_directeur = $request->directeur;

      if($request->validation=="yes"){   $da->validation_manager=true;}
      else $da->validation_manager=false;

     $da->date_chef_service = Carbon::now()->format('Y-d-m H:i:s');

      $da->save();

     if($da->validation_manager){
        $user= DB::table('users')->where("type", "=","manager")->where("departement", "=",$departement)->get()->first();
        $destinaire = DB::table('users')->where("id", "=",$request->directeur)->get()->first();
        Mail::to($destinaire->email)->send(new DAMail_manager($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$request->observation));
     return back()->with('success', "you're demand is registered");
     }
    else
    {

        $user= DB::table('users')->where("type", "=","manager")->where("departement", "=",$departement)->get()->first();
        $destinaire=  DB::table('users')->where("id", "=",$da->id_emetteur)->get()->first();
        Mail::to($destinaire->email)->send(new DAMail_manager_refus($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$request->observation));
        return back()->with('success', "you're demand is registered");

    }
    }


    function get_encours_dm_directeur(Request $request){

        $dm = DB::table('da_models')->where('validation_manager','=',true)
        ->where('id_directeur','=',$request->session()->get('loginId'))->get();
        return view('directeur_encoursdm',['items'=>$dm]);
    }

    function get_nouvelle_dm_directeur($id){
        $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$id)->get();

        $user=DB::table('users')->where('id',$dm[0]->id_acheteur)->get()->first();
        $emetteur= DB::table('users')->where('id',$dm[0]->id_emetteur)->get()->first();

        $manager= DB::table('users')->where("type", "=","manager")->where("departement", "=",$emetteur->departement)->get()->first();


        return view('directeur_nouvelledm',['acheteurs'=>$user , 'dm'=>$dm , 'emetteur' => $emetteur, 'manager'=> $manager]   );
     }

     function add_dm_directeur(Request $request){

        $request->validate([

            'observation' => 'required',
            'validation' => 'required|in:yes,no',

         ]);

    // $da = DB::table('users')->where('id','=',$request->id)->get()->first();
    $da = DaModel::find($request->id);


      $da->commentaire_directeur = $request->observation;

      if($request->validation=="yes"){   $da->validation_directeur=true;}
      else $da->validation_directeur=false;

     $da->date_directeur = Carbon::now()->format('Y-d-m H:i:s');;

      $da->save();

     if($da->validation_directeur){


        $user=DB::table('users')->where('id',$da->id_directeur)->get()->first();

        $destinaire=DB::table('users')->where('id',$da->id_acheteur)->get()->first();


        Mail::to($destinaire->email)->send(new DAMail_directeur($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$da->commentaire_manager,$request->observation));
     return back()->with('success', "you're demand is registered");
     }
    else
    {
        $emetteur =DB::table('users')->where('id',$da->id_emetteur)->get()->first();

        $manager = User::where('departement','=',$emetteur->departement)->where('type','=','manager')->get()->first();

        $user= DB::table('users')->where('id',$da->id_directeur)->get()->first();

       // Mail::to($emetteur->email)->send(new DAMail($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id));
        Mail::to($manager->email)->send(new DAMail_directeur_refus($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$da->commentaire_manager,$request->observation));
        return back()->with('success', "you're demand is registered");

    }
    }

    function get_encours_dm_acheteur(Request $request){
        $dm= DB::table('da_models')->where('id_acheteur','=',$request->session()->get('loginId'))->get();


        return view('acheteur_encours',['items'=>$dm]);
    }

    function get_nouvelle_dm_acheteur($id){
        $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$id)->get();

        $user=DB::table('users')->where('id',$dm[0]->id_acheteur)->get()->first();
        $emetteur= DB::table('users')->where('id',$dm[0]->id_emetteur)->get()->first();
        $manager= DB::table('users')->where("type", "=","manager")->where("departement", "=",$emetteur->departement)->get()->first();


        return view('acheteur_nouvelledm',['acheteurs'=>$user , 'dm'=>$dm,'emetteur' => $emetteur, 'manager'=> $manager]);
     }


     function add_dm_acheteur(Request $request){

        $request->validate([

            'observation' => 'required',

         ]);

    // $da = DB::table('users')->where('id','=',$request->id)->get()->first();
    $da = DaModel::find($request->id);

      $da->commentaire_acheteur = $request->observation;


      if($request->validation == 'yes')

     $da->validation_acheteur=true;
     else
     $da->validation_acheteur=false;


     $da->date_acheteur = Carbon::now()->format('Y-d-m H:i:s');;

      $da->save();

     if($da->validation_acheteur){
     return redirect('/nouveau_tab_comparatif/'.$da->id);
     }
    else
    {
        $emetteur =DB::table('users')->where('id',$da->id_emetteur)->get()->first();
        $user= DB::table('users')->where('id',$da->id_acheteur)->get()->first();

       // Mail::to($emetteur->email)->send(new DAMail($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id));
        Mail::to($emetteur->email)->send(new DAMail_acheteur_refus($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$da->commentaire_manager,$da->commentaire_directeur,$request->observation,$da->commentaire_acheteur));
        return back()->with('success', "you're demand is registered");

    }
    }

    function get_valide_dm($id) {

        $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$id)->get();

        $user=DB::table('users')->where('id',$dm[0]->id_acheteur)->get()->first();
        $emetteur= DB::table('users')->where('id',$dm[0]->id_emetteur)->get()->first();
        $manager= DB::table('users')->where("type", "=","manager")->where("departement", "=",$emetteur->departement)->get()->first();

        return view('/valide' ,['acheteurs'=>$user , 'dm'=>$dm,'emetteur' => $emetteur, 'manager'=> $manager]);
    }

}
