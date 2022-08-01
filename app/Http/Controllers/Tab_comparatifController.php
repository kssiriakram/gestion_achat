<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tab_comparatif;
use App\Models\Fournisseur;
use App\Mail\Tab_comparatif_mail;

class Tab_comparatifController extends Controller
{
    function get_nouveau_tab_comparatif($id){


        return view('/nouveau_tab_comparatif'  , ['id' => $id]);
    }

    function add_tab_comparatif(Request $request){

        $request->validate(
            [

                'fournisseur' => 'required|array|min:1',
                'fournisseur.*' => 'required',
                'prix' => 'required|array|min:1',
                'prix.*' => 'required|float',
                'remise' => 'required|array|min:1',
                'remise.*' => 'required|float',
                'devise' => 'required|array|min:1'
             ]
            );


            $tab = new Tab_comparatif();
            $tab->id = $request->id;

            $tab->save();

            for($i=0;$i<count($request->fournisseur);$i++){
                $fournisseur=new Fournisseur();
                $fournisseur->nom_fournisseur = $request->fournisseur[$i];
                 $fournisseur->prix = $request->prix[$i];

                 $fournisseur->devise= $request->devise[$i];
                 $fournisseur->remise= $request->remise[$i];

                 $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$request->id)->get();
                 $fournisseur->prix_total =0;
                 for($j=0;$j<count($dm);$j++)
                 $fournisseur->prix_total += $request->prix[$i]*$dm[$j]->qte-$request->remise[$i];

                 $fournisseur->id_tab_comparatif=$request->id;

                 $fournisseur->save();

    }

    if($res) {
        $dm=DB::table('ligne_das')->join('da_models','da_models.id','=','ligne_das.id_da')->where('da_models.id',$request->id)->get();
        $user = DB::table('users')->where("id", '=',$dm[0]->id_acheteur)->get()->first();
        $emetteur = DB::table('users')->where("id", '=',$dm[0]->id_emetteur)->get()->first();
        $destinaire = DB::table('users')->where("type", "=","manager")->where("departement", "=",$emetteur->departement)->get()->first();
       Mail::to($destinaire->email)->send(new Tab_comparatif_mail($user->username, $user->societe, $user->type,$user->email,"", "demande d'achat" , $da_id->id));
        return back()->with('success', "you're demand is registered");
    }
    else
    return back()->with('fail',"some error occured at registring your demand");
 }


}
