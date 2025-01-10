<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   public function loginForm(){
    return view('auth.login');
   }

  public function login(Request $request){
    $request->validate([
      'email' => 'required|email',
      'password' => 'required|min:6',
    ]);
    if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
        // Redirect to users.index on success
        return redirect()->route('users.index')->with('success', 'Login successful!');
    }
    return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
  }

  public function logout()
  {
    Auth::logout();
    return redirect()->route('login')->with('success', 'Logged out successfully!');
  }
}