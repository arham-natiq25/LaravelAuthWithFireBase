<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class FirebaseAuthService
{
    protected $apiKey;
    protected $client;

    public function __construct()
    {
        $this->apiKey = env('FIREBASE_API_KEY');
        $this->client = new Client();
    }

    public function signUpWithEmailAndPassword($email, $password)
    {
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:signUp?key={$this->apiKey}";

        $response = $this->client->post($url, [
            'json' => [
                'email' => $email,
                'password' => $password,
                'returnSecureToken' => true,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }


    public function verifyIdToken($idToken)
    {
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$this->apiKey}";

        $response = $this->client->post($url, [
            'json' => [
                'idToken' => $idToken,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function signInWithEmailAndPassword($email, $password)
    {
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key={$this->apiKey}";

        $response = $this->client->post($url, [
            'json' => [
                'email' => $email,
                'password' => $password,
                'returnSecureToken' => true,
            ],
        ]);

        // dd (json_decode($response->getBody(), true));

        return json_decode($response->getBody(), true);
    }

    // Add other methods like sign up, password reset, etc.
}
