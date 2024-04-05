<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client as OAuthClient;
const PASSPORT_SERVER_URL = "http://127.0.0.1";

class AuthController extends Controller
{
    private function passportAuthenticationData($email, $password) {
        $oauth_client_id = env('PASSPORT_CLIENT_ID');
        $oauth_client = OAuthClient::findOrFail($oauth_client_id);

        return [
            'grant_type' => 'password',
            'client_id' => $oauth_client_id,
            'client_secret' => $oauth_client->secret,
            'username' => $email,
            'password' => $password,
            'scope' => ''
        ];
    }

    public function login(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if (!$user)
        {
            return response()->json('Utilizador não encontrado', 401);
        }
        if ($user->blocked != 1)
        {
            try {
                request()->request->add($this->passportAuthenticationData($request->email, $request->password));
                $request = Request::create(PASSPORT_SERVER_URL . '/oauth/token', 'POST');
                $response = Route::dispatch($request);
                $errorCode = $response->getStatusCode();
                $auth_server_response = json_decode((string) $response->content(), true);
                return response()->json($auth_server_response, $errorCode);
            }
            catch (\Exception $e) {
                return response()->json('Autenticação falhada', 401);
            }
        }
        else{
            return response()->json('Utilizador bloqueado!', 401);
        }

    }

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }
}
