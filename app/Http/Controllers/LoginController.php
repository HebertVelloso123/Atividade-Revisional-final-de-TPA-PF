<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function attempt(Request $request) {
        $credenciais = $request->only('email', 'password');
    
        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();
            return redirect()->route('produtos.index');
        }
    
        return back()->withErrors(['email' => 'Credenciais inválidas.']);
    }
    
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('produtos.index');
    }
    
}
