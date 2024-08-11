<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseAuthService;

class AuthController extends Controller
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuthService $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $response = $this->firebaseAuth->signInWithEmailAndPassword($request->email, $request->password);

        if (isset($response['idToken'])) {
            session(['firebase_id_token' => $response['idToken']]);
            return redirect()->route('home');
        }

        return redirect()->back()->withErrors(['email' => 'Authentication failed']);
    }

    public function logout()
    {
        session()->forget('firebase_id_token');
        return redirect()->route('login');
    }

    public function showRegisterForm()
{
    return view('auth.register');
}

public function register(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $response = $this->firebaseAuth->signUpWithEmailAndPassword($request->email, $request->password);

    if (isset($response['idToken'])) {
        session(['firebase_id_token' => $response['idToken']]);
        return redirect()->route('home');
    }

    return redirect()->back()->withErrors(['email' => 'Registration failed']);
}

}
