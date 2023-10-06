<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // User Register
    public function register(RegisterRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['username'] = 'tapo'; //strstr($data['email'], '@', true);
        $user = User::create($data);
        $token = $user->createToken(User::USER_TOKEN);
        return $this->success([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 'User has been register successfully.');
    }
    
    // User login for Authorization: Bearer +  AuthToken to get all informations
    public function login(LoginRequest $request) : JsonResponse
    {
        $isValid = $this->isValidateCredencial($request); 
        if(!$isValid['success']){
            return $this->error($isValid['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user   =  $isValid['user'];
        $token  =  $user->createToken(User::USER_TOKEN);
        return  $this->success([
            'user' => $user,
            'token' => $token->plainTextToken],
            'login successfully'
        );
          
    }
    
    private function isValidateCredencial(LoginRequest $request) : array
    {
        $data = $request->validated();
        $user  =  User::where("email", $data['email'])->first();
        if(is_null($user)) {
            return  ["success" => false, "message" => "invalide credencial"];
        }
        if (Hash::check($data['password'] , $user->password)) {
            return [
                'success' => true,
                'user' => $user
            ];
        }
        return  ["success" => false, "message" => "Failed! email not found"];
    }

    // User Detail with Authorization: Bearer AuthToken
    public function loginWithToken() : JsonResponse
    {
        return $this->success(
            auth()->user(),
            'login successfully'
        );
    }

    // User logout with Authorization: Bearer AuthToken
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null,'login successfully'
        );
        return response()->json(['message' => 'User successfully signed out']);
    }


}
