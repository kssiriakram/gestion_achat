<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(){
           return view("auth-login");
    }

    public function registration(){
        return view("registration");
    }

    public function register(Request $request){
         $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'username' => 'required'
         ]);

         $user = new User();
         $user->type=$request->type;
         $user->username=$request->username;
         $user->name=$request->name;
         $user->email=$request->email;
         $user->password=Hash::make($request->password);
         $res = $user->save();

         if($res){
            return back()->with('success','You have register successfully');
         }else{
            return back()->with('fail','Something wrong during registring');
         }


    }

    public function signin(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required'
         ]);



         $user = DB::table('users')->where("username", $request->username)->first();

         if($user){
            if(Hash::check($request->password,$user->password)){
                $request->session()->put('loginId',$user->id);
                $request->session()->put('type',$user->type);
                $request->session()->put('departement',$user->departement);

                if($user->type=='emetteur')
                return redirect('/encoursdm');
                else if($user->type=='manager')
                return redirect('/manager_encoursdm');
                else if($user->type=='directeur')
                return redirect('/directeur_encoursdm');
                else if($user->type=='acheteur')
                return redirect('/acheteur_encoursdm');


            }else{
                return back()->with('fail','passwords does not match');
            }

         }else{
            return back()->with('fail','acount does not exist');
         }


    }

    function logout(Request $request){
        $request->session()->flush();
        return redirect("/login");
    }
}
