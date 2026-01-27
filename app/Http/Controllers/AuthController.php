<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);



        return redirect()->back()->with('error', 'Une erreur s\'est produite');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = [
            'email' => strtolower($request->email),
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {

            $user = $request->user();



            return redirect()->route('home');
        }
        return redirect()->back()->withInput(['email'])->with('error', 'Email ou mot de passe incorrect');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
