<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\FirebaseAuthService;
use Illuminate\Http\Request;

class FirebaseAuthMiddleware
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuthService $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function handle(Request $request, Closure $next)
    {
        $idToken = session('firebase_id_token');

        if (!$idToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $firebaseUser = $this->firebaseAuth->verifyIdToken($idToken);

        if (!$firebaseUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->attributes->set('firebase_user', $firebaseUser);

        return $next($request);
    }
}
