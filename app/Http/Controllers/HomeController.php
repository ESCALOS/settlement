<?php

namespace App\Http\Controllers;

use App\Models\User;

class HomeController extends Controller
{
    public function index(){
        if(Auth()->user() == NULL){
            return redirect('login');
        }else{
            $user = User::find(auth()->user()->id);
            if($user->hasRole('ADMINISTRADOR')){
                return redirect()->route('admin.orders');
            }else{
                return view('dashboard');
            }
        }
    }
}
