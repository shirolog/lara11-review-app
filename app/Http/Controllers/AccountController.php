<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function register(){

        return view('account.register');
    }

    public function store(Request $request){

        
       $validator = Validator::make($request->all(),[

            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required',
        ]);

        if($validator->fails()){

            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }
      
        $user = new User;

        $user -> name = $request->input('name');
        $user -> email = $request->input('email');
        $user -> password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('account.login')->with('success', 'You have registered successfully!');
    }


    public function login(){

        return view('account.login');
    }


    public function authenticate(Request $request){

        $validator = Validator::make($request->all(),[

            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){

            return redirect()->route('account.login')->withInput()->withErrors($validator);

        }


        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){

            return redirect()->route('account.profile');

        }else{

            return redirect()->route('account.login')->with('error', 'Either email or password is inccoret!');

        }
    }


    public function profile(){

        $user = User::find(Auth::user()->id);

        return view('account.profile', compact('user'));
    }



    public function updateProfile(Request $request){

        $validator = Validator::make($request->all(),[

            'name' => 'required|min:3',
            'email' => 'required|unique:users,email,' . Auth::user()->id . ',id'
        ]);
        
        if($validator->fails()){

            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }


        $user = User::find(Auth::user()->id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('account.profile')->with('success', 'Profile updated successfully!');
    }

    public function logout(Request $request){

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('account.login');
    }
}
