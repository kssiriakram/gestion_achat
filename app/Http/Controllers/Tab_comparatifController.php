<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tab_comparatif;
use App\Models\DaModel;
use App\Models\Fournisseur;
use App\Models\Ligne_da;
use App\Models\ligne_da_fournisseur;
use App\Mail\Tab_comparatif_mail;
use App\Mail\Tab_comparatif_manager;
use App\Mail\Tab_comparatif_manager_refus;
use App\Mail\Tab_comparatif_directeur_refus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Tab_comparatifController extends Controller
{

    function get_nouvelle_tab_acheteur($id){
        $dm=DB::table('da_models')->join('ligne_das','da_models.id','=','ligne_das.id_da')
        ->join('tab_comparatifs','tab_comparatifs.id','=','da_models.id')
        ->join('fournisseurs','tab_comparatifs.id','=','fournisseurs.id_tab_comparatif')
        ->where('da_models.id',$id)->get();


        $e = DB::table('da_models')->where('id',$id)->get();


    $tab=DB::table('fournisseurs')->join('ligne_da_fournisseurs','ligne_da_fournisseurs.id_fournisseur','=','fournisseurs.id')
    ->join('ligne_das','ligne_da_fournisseurs.id_ligne_da','=','ligne_das.id')->where('ligne_das.id_da',$id)->get();




       $fournisseur = DB::table('fournisseurs')->where('id_tab_comparatif','=',$e[0]->id)->count();

       $ligne_da = DB::table('ligne_das')->where('id_da','=',$e[0]->id)->count();

        $user=DB::table('users')->where('id',$e[0]->id_acheteur)->get()->first();
        $directeur = DB::table('users')->where('id',$e[0]->id_directeur)->get()->first();
        $emetteur= DB::table('users')->where('id',$e[0]->id_emetteur)->get()->first();

        return view('acheteur_nouvelletab',['acheteur'=>$user ,'tab'=>$dm, 'dm'=>$tab , 'emetteur' => $emetteur , 'directeur' => $directeur
        , 'fournisseur' => $fournisseur , 'ligne_da' => $ligne_da ] );
     }

     function acheteur_edit_tab(Request $request){

        $request->validate(
            [

                'fournisseur.*.*' => 'required',
                'prix.*.*' => 'required',
                'remise.*.*' => 'required'
            ]
            );

           $keys_fournisseur=array_keys($request->fournisseur);
           $keys_prix=array_keys($request->prix);
           $keys_remise=array_keys($request->remise);


           for($i=0;$i<count($keys_fournisseur);$i++){
            $values_fournisseur = array_keys($request->fournisseur[$keys_fournisseur[$i]]);

            $values_prix = array_keys($request->prix[$keys_prix[$i]]);
            $values_remise = array_keys($request->remise[$keys_remise[$i]]);


            for($j=0;$j<count($values_fournisseur);$j++){

                $fournisseur=Fournisseur::find($values_fournisseur[$j]);
                $fournisseur->nom_fournisseur=$request->fournisseur[$keys_fournisseur[$i]][$values_fournisseur[$j]];
                $produit=ligne_da_fournisseur::where('id_fournisseur','=',$values_fournisseur[$j])
                ->where('id_ligne_da','=',$keys_fournisseur[$i])->get()->first();
                $produit->prix=$request->prix[$keys_prix[$i]][$values_prix[$j]];
                $produit->remise=$request->remise[$keys_prix[$i]][$values_remise[$j]];
                $fournisseur->save();
                $produit->save();
            }
           }


           $ids =DB::table('ligne_da_fournisseurs')
           ->join('ligne_das','ligne_da_fournisseurs.id_ligne_da','=','ligne_das.id')
           ->where('ligne_das.id_da',$request->id)->get();

           $j=0;
           $k=0;
           $fournisseurs = Fournisseur::where('id_tab_comparatif',$request->id)->get();

           for($i=0;$i<count($fournisseurs);$i++){
            $fournisseur = Fournisseur::where('id',$fournisseurs[$i]->id)->get()->first();
            $fournisseur->prix_total=0;

            while($j<count($ids)){
            $ligne_da = ligne_da::where('id',$ids[$j]->id)->get()->first();
            $produit = ligne_da_fournisseur::where('id_ligne_da',$ids[$j]->id)->
            where('id_fournisseur',$fournisseurs[$i]->id)->get()->first();

            $fournisseur->prix_total +=$produit->prix*$ligne_da->qte - $produit->remise;
            $j=$j+count($fournisseurs);
        }
        $k++;
        $j=$k;
           $fournisseur->save();
        }



           $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$request->id)->get();
           $user = DB::table('users')->where("id", '=',$dm[0]->id_acheteur)->get()->first();
           $emetteur = DB::table('users')->where("id", '=',$dm[0]->id_emetteur)->get()->first();
           $destinaire = DB::table('users')->where("type", "=","manager")->where("departement", "=",$emetteur->departement)->get()->first();
          Mail::to($destinaire->email)->send(new Tab_comparatif_mail($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id));
           return back()->with('success', "you're demand is registered");

    }


    function get_encours_tab_acheteur(Request $request){
        // dd(DB::table('users')->where('departement','=',$request->session()->get('departement'))->get());


         $dm= DB::table('users')->join('da_models','users.id','=','da_models.id_acheteur')
         ->where('da_models.id_acheteur','=',$request->session()->get('loginId'))->
         join('tab_comparatifs','tab_comparatifs.id','=','da_models.id')->get();


         return view('acheteur_encourstab',['items'=>$dm]);
     }
    function get_nouveau_tab_comparatif($id){

        $count = Ligne_da::where('id_da',$id)->count();
        $id_das = Ligne_da::where('id_da',$id)->get();





        return view('/nouveau_tab_comparatif'  , ['id' => $id , 'count' => $count , 'id_das'=>$id_das]);
    }

    function add_tab_comparatif(Request $request){





        $request->validate(
            [

                'fournisseur' => 'required|array|min:1',
                'fournisseur.*' => 'required',
                'prix' => 'required|array|min:1',
                'prix.*' => 'required|between:0,99.99',
                'remise' => 'required|array|min:1',
                'remise.*' => 'required|between:0,99.99',
                'devise' => 'required|array|min:1'
             ]
            );


            $tab = new Tab_comparatif();
            $tab->id = $request->id;

            $tab->save();

            $fournisseurs = [];

            for($i=0;$i<count($request->fournisseur);$i++){
                $fournisseur=new Fournisseur();
                $fournisseur->nom_fournisseur = $request->fournisseur[$i];
                $fournisseur->id_tab_comparatif=$request->id;
                $fournisseur->save();

                $fournisseurs[$i] = Fournisseur::where('id',$fournisseur->id)->get()->first();
            }




            $ids = $request->id_das;



            for($i=0;$i<count($ids);$i++){

                for($j=0;$j<count($fournisseurs);$j++){

                $ligne_da_fournisseur=new ligne_da_fournisseur();


                $ligne_da_fournisseur->id_ligne_da = current($ids);




                $ligne_da_fournisseur->id_fournisseur =$fournisseurs[$j]->id;

                $ligne_da_fournisseur->prix = $request->prix[$i][$j];

                $ligne_da_fournisseur->devise= $request->devise[$i][$j];
                $ligne_da_fournisseur->remise= $request->remise[$i][$j];



                $ligne_da_fournisseur->save();



    }
    next($ids);

}



for($i=0;$i<count($fournisseurs);$i++){
    $fournisseur = Fournisseur::where('id',$fournisseurs[$i]->id)->get()->first();

    for($j=0;$j<count($ids);$j++){
    $ligne_da = ligne_da::where('id',$ids[$j])->get()->first();
    $produit = ligne_da_fournisseur::where('id_ligne_da',$ids[$j])->
    where('id_fournisseur',$fournisseurs[$i]->id)->get()->first();

    $fournisseur->prix_total +=$produit->prix*$ligne_da->qte - $produit->remise;
}
   $res=$fournisseur->save();
}

    if($res) {
      return redirect("/acheteur_nouvelletab/".$request->id);
    }
    else
    return back()->with('fail',"some error occured at registring your demand");
 }



 function get_encours_tab_manager(Request $request){
    // dd(DB::table('users')->where('departement','=',$request->session()->get('departement'))->get());


     $dm= DB::table('users')->join('da_models','users.id','=','da_models.id_emetteur')
     ->where('users.departement','=',$request->session()->get('departement'))->
     join('tab_comparatifs','tab_comparatifs.id','=','da_models.id')->get();


     return view('manager_encourstab',['items'=>$dm]);
 }

 function get_nouvelle_tab_manager($id){
    $dm=DB::table('da_models')->join('ligne_das','da_models.id','=','ligne_das.id_da')
    ->join('tab_comparatifs','tab_comparatifs.id','=','da_models.id')
    ->join('fournisseurs','tab_comparatifs.id','=','fournisseurs.id_tab_comparatif')
    ->where('da_models.id',$id)->get();



    $tab=DB::table('fournisseurs')->join('ligne_da_fournisseurs','ligne_da_fournisseurs.id_fournisseur','=','fournisseurs.id')
->join('ligne_das','ligne_da_fournisseurs.id_ligne_da','=','ligne_das.id')->where('ligne_das.id_da',$id)->get();



$liste_fournisseur = DB::table('fournisseurs')->where('id_tab_comparatif','=',$id)->get();


   $fournisseur = DB::table('fournisseurs')->where('id_tab_comparatif','=',$dm[0]->id_tab_comparatif)->count();

   $ligne_da = DB::table('ligne_das')->where('id_da','=',$dm[0]->id_da)->count();

    $user=DB::table('users')->where('id',$dm[0]->id_acheteur)->get()->first();
    $directeur = DB::table('users')->where('id',$dm[0]->id_directeur)->get()->first();
    $emetteur= DB::table('users')->where('id',$dm[0]->id_emetteur)->get()->first();

    return view('manager_nouvelletab',['acheteur'=>$user , 'dm'=>$tab,'tab'=>$dm , 'emetteur' => $emetteur , 'directeur' => $directeur
    , 'fournisseur' => $fournisseur , 'ligne_da' => $ligne_da  , 'liste_fournisseur' => $liste_fournisseur] );
 }

 function manager_add_tab(Request $request){

    $request->validate(
        [

            'observation' => 'required',
            'validation' => 'required|in:yes,no',
            'fournisseur' => 'required|array',
            'fournisseur.*' => 'required'

         ]
        );



        if($request->fournisseur){
            $key=array_keys($request->fournisseur);

            for($i=0;$i<count($key);$i++){
                $produit = ligne_da_fournisseur::where('id_fournisseur','=',$request->fournisseur[$key[$i]])
                ->where('id_ligne_da','=',$key[$i])->get()->first();
                $produit->fournisseur_souhaite=true;
                $produit->save();
            }
        }
    $tab = Tab_comparatif::find($request->id);
    $tab->commentaire_manager=$request->observation;
    $tab->date_chef_service = Carbon::now()->format('Y-d-m H:i:s');

    if($tab->date_directeur) $tab->date_directeur=NULL;

    if($request->validation=="yes"){   $tab->validation_manager=true;}
      else $tab->validation_manager=false;

      $tab->save();
      $da = DaModel::find($request->id);
      $departement = DB::table('users')->where('id',$da->id_emetteur)->get()->first()->departement;

    if($request->validation=="yes"){
        $user= DB::table('users')->where("type", "=","manager")->where("departement", "=",$departement)->get()->first();
        $destinaire = DB::table('users')->where("id", "=",$da->id_directeur)->get()->first();
        Mail::to($destinaire->email)->send(new Tab_comparatif_manager($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$request->observation));
     return back()->with('success', "you're demand is registered");
    }
    else{
        $user= DB::table('users')->where("type", "=","manager")->where("departement", "=",$departement)->get()->first();
        $destinaire=  DB::table('users')->where("id", "=",$da->id_acheteur)->get()->first();
        Mail::to($destinaire->email)->send(new Tab_comparatif_manager_refus($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$request->observation));
        return back()->with('success', "you're demand is registered");
    }

 }

 function get_encours_tab_directeur(Request $request){
    // dd(DB::table('users')->where('departement','=',$request->session()->get('departement'))->get());


     $dm= DB::table('users')->join('da_models','users.id','=','da_models.id_directeur')
     ->where('users.id','=',$request->session()->get('loginId'))->
     join('tab_comparatifs','tab_comparatifs.id','=','da_models.id')->get();


     return view('directeur_encourstab',['items'=>$dm]);
 }

 function get_nouvelle_tab_directeur($id){
    $dm=DB::table('da_models')->join('ligne_das','da_models.id','=','ligne_das.id_da')
    ->join('tab_comparatifs','tab_comparatifs.id','=','da_models.id')
    ->join('fournisseurs','tab_comparatifs.id','=','fournisseurs.id_tab_comparatif')
    ->where('da_models.id',$id)->get();




    $tab=DB::table('fournisseurs')->join('ligne_da_fournisseurs','ligne_da_fournisseurs.id_fournisseur','=','fournisseurs.id')
->join('ligne_das','ligne_da_fournisseurs.id_ligne_da','=','ligne_das.id')->where('ligne_das.id_da',$id)->get();



   $fournisseur = DB::table('fournisseurs')->where('id_tab_comparatif','=',$dm[0]->id_tab_comparatif)->count();

   $fournisseur_souhaite = DB::table('fournisseurs')
   ->join('ligne_da_fournisseurs','fournisseurs.id','=','ligne_da_fournisseurs.id_fournisseur')
   ->where('fournisseurs.id_tab_comparatif','=',$dm[0]->id_tab_comparatif)->
   where('ligne_da_fournisseurs.fournisseur_souhaite','=',true)->get();



   $ligne_da = DB::table('ligne_das')->where('id_da','=',$dm[0]->id_da)->count();

    $user=DB::table('users')->where('id',$dm[0]->id_acheteur)->get()->first();
    $directeur = DB::table('users')->where('id',$dm[0]->id_directeur)->get()->first();
    $emetteur= DB::table('users')->where('id',$dm[0]->id_emetteur)->get()->first();

    return view('directeur_nouvelletab',['acheteur'=>$user , 'dm'=>$tab,'tab'=>$dm , 'emetteur' => $emetteur , 'directeur' => $directeur
    , 'fournisseur' => $fournisseur , 'ligne_da' => $ligne_da ,'fournisseur_souhaite'=>$fournisseur_souhaite] );
 }

 function directeur_add_tab(Request $request){

    $request->validate(
        [

            'observation' => 'required',
            'validation' => 'required|in:yes,no'

         ]
        );

    $tab = Tab_comparatif::find($request->id);
    $tab->commentaire_manager=$request->observation;
    $tab->date_directeur = Carbon::now()->format('Y-d-m H:i:s');

    if($request->validation=="yes"){   $tab->validation_directeur=true;}
      else $tab->validation_directeur=false;

      $tab->save();
      $da = DaModel::find($request->id);
      $departement = DB::table('users')->where('id',$da->id_emetteur)->get()->first()->departement;

    if($request->validation=="yes"){
       /* $user= DB::table('users')->where("type", "=","manager")->where("departement", "=",$departement)->get()->first();
        $destinaire = DB::table('users')->where("id", "=",$da->id_directeur)->get()->first();
        Mail::to($destinaire->email)->send(new Tab_comparatif_manager($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$request->observation));
     return back()->with('success', "you're demand is registered");*/
    }
    else{
        $destinaire= DB::table('users')->where("type", "=","manager")->where("departement", "=",$departement)->get()->first();
        $user =  DB::table('users')->where("id", "=",$da->id_directeur)->get()->first();
        Mail::to($destinaire->email)->send(new Tab_comparatif_directeur_refus($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id,$request->observation));
        return back()->with('success', "you're demand is registered");
    }

 }

 function get_retourne_managers(Request $request){
    $dm=  DB::table('users')->join('da_models','users.id','=','da_models.id_acheteur')
        ->get('da_models.id');


    $tab = [];

    for($i=0;$i<count($dm);$i++)
    {
        if(DB::table('tab_comparatifs')->where('id','=',$dm[$i]->id)
        ->where('validation_manager','=',false)
        ->where('date_chef_service','<>',NULL)
        ->get()->first()!=NULL)

          $tab[$i]=DB::table('tab_comparatifs')->where('id','=',$dm[$i]->id)
          ->where('validation_manager','=',false)
          ->where('date_chef_service','<>',NULL)
          ->get()->first();
    }


    return view('retourner_managers_tab',['items'=>$tab]);
}


function get_retourne_tab_manager($id){
    $dm=DB::table('da_models')->join('ligne_das','da_models.id','=','ligne_das.id_da')
    ->join('tab_comparatifs','tab_comparatifs.id','=','da_models.id')
    ->join('fournisseurs','tab_comparatifs.id','=','fournisseurs.id_tab_comparatif')
    ->where('da_models.id',$id)->get();


    $e = DB::table('da_models')->where('id',$id)->get();


$tab=DB::table('fournisseurs')->join('ligne_da_fournisseurs','ligne_da_fournisseurs.id_fournisseur','=','fournisseurs.id')
->join('ligne_das','ligne_da_fournisseurs.id_ligne_da','=','ligne_das.id')->where('ligne_das.id_da',$id)->get();




   $fournisseur = DB::table('fournisseurs')->where('id_tab_comparatif','=',$e[0]->id)->count();

   $ligne_da = DB::table('ligne_das')->where('id_da','=',$e[0]->id)->count();

    $user=DB::table('users')->where('id',$e[0]->id_acheteur)->get()->first();
    $directeur = DB::table('users')->where('id',$e[0]->id_directeur)->get()->first();
    $emetteur= DB::table('users')->where('id',$e[0]->id_emetteur)->get()->first();

    return view('retourner_manager_tab',['acheteur'=>$user ,'tab'=>$dm, 'dm'=>$tab , 'emetteur' => $emetteur , 'directeur' => $directeur
    , 'fournisseur' => $fournisseur , 'ligne_da' => $ligne_da ] );
 }


 function get_retourne_directeurs(Request $request){
    $dm=  DB::table('users')->join('da_models','users.id','=','da_models.id_emetteur')
    ->where('users.departement','=',$request->session()->get('departement'))
        ->get('da_models.id');


    $tab = [];

    for($i=0;$i<count($dm);$i++)
    {
        if(DB::table('tab_comparatifs')->where('id','=',$dm[$i]->id)
        ->where('validation_directeur','=',false)
        ->where('date_directeur','<>',NULL)
        ->get()->first()!=NULL)

          $tab[$i]=DB::table('tab_comparatifs')->where('id','=',$dm[$i]->id)
          ->where('validation_directeur','=',false)
          ->where('date_directeur','<>',NULL)
          ->get()->first();
    }


    return view('retourner_directeurs_tab',['items'=>$tab]);
}


function manager_edit_tab(Request $request){

    $request->validate(
        [

            'fournisseur.*.*' => 'required',
            'prix.*.*' => 'required',
            'remise.*.*' => 'required'
        ]
        );

       $keys_fournisseur=array_keys($request->fournisseur);
       $keys_prix=array_keys($request->prix);
       $keys_remise=array_keys($request->remise);


       for($i=0;$i<count($keys_fournisseur);$i++){
        $values_fournisseur = array_keys($request->fournisseur[$keys_fournisseur[$i]]);

        $values_prix = array_keys($request->prix[$keys_prix[$i]]);
        $values_remise = array_keys($request->remise[$keys_remise[$i]]);


        for($j=0;$j<count($values_fournisseur);$j++){

            $fournisseur=Fournisseur::find($values_fournisseur[$j]);
            $fournisseur->nom_fournisseur=$request->fournisseur[$keys_fournisseur[$i]][$values_fournisseur[$j]];
            $produit=ligne_da_fournisseur::where('id_fournisseur','=',$values_fournisseur[$j])
            ->where('id_ligne_da','=',$keys_fournisseur[$i])->get()->first();
            $produit->prix=$request->prix[$keys_prix[$i]][$values_prix[$j]];
            $produit->remise=$request->remise[$keys_prix[$i]][$values_remise[$j]];
            $fournisseur->save();
            $produit->save();
        }
       }

       $ids =DB::table('ligne_da_fournisseurs')
       ->join('ligne_das','ligne_da_fournisseurs.id_ligne_da','=','ligne_das.id')
       ->where('ligne_das.id_da',$request->id)->get();

       $j=0;
       $k=0;
       $fournisseurs = Fournisseur::where('id_tab_comparatif',$request->id)->get();

       for($i=0;$i<count($fournisseurs);$i++){
        $fournisseur = Fournisseur::where('id',$fournisseurs[$i]->id)->get()->first();
        $fournisseur->prix_total=0;

        while($j<count($ids)){
        $ligne_da = ligne_da::where('id',$ids[$j]->id)->get()->first();
        $produit = ligne_da_fournisseur::where('id_ligne_da',$ids[$j]->id)->
        where('id_fournisseur',$fournisseurs[$i]->id)->get()->first();

        $fournisseur->prix_total +=$produit->prix*$ligne_da->qte - $produit->remise;
        $j=$j+count($fournisseurs);
    }
    $k++;
    $j=$k;
       $fournisseur->save();
    }

    $tab_comparatif = Tab_comparatif::where('id','=',$fournisseur->id_tab_comparatif)->get()->first();
    $tab_comparatif->date_chef_service = NULL;
    $tab_comparatif->save();


    $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$fournisseur->id_tab_comparatif)->get();
        $user = DB::table('users')->where("id", '=',$dm[0]->id_acheteur)->get()->first();
        $emetteur = DB::table('users')->where("id", '=',$dm[0]->id_emetteur)->get()->first();
        $destinaire = DB::table('users')->where("type", "=","manager")->where("departement", "=",$emetteur->departement)->get()->first();
       Mail::to($destinaire->email)->send(new Tab_comparatif_mail($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id));
        return back()->with('success', "you're demand is registered");

}

}
