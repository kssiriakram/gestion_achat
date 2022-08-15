<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tab_comparatif;
use App\Models\Fournisseur;
use App\Models\Ligne_da;
use App\Models\ligne_da_fournisseur;
use App\Mail\Tab_comparatif_mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Tab_comparatifController extends Controller
{
    function get_nouveau_tab_comparatif($id){

        $count = Ligne_da::where('id_da',$id)->count();
        $id_das = Ligne_da::where('id_da',$id)->get('id');




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
        $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$request->id)->get();
        $user = DB::table('users')->where("id", '=',$dm[0]->id_acheteur)->get()->first();
        $emetteur = DB::table('users')->where("id", '=',$dm[0]->id_emetteur)->get()->first();
        $destinaire = DB::table('users')->where("type", "=","manager")->where("departement", "=",$emetteur->departement)->get()->first();
       Mail::to($destinaire->email)->send(new Tab_comparatif_mail($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $request->id));
        return back()->with('success', "you're demand is registered");
    }
    else
    return back()->with('fail',"some error occured at registring your demand");
 }


}
