<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserServiceInterface;
use App\Mail\PasswordRecoveryMail;

class UserService implements UserServiceInterface
{
    private $userRepository;
    
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser($request)
    {
        $validator = Validator::make($request->toArray(), [
            'email'         => 'required|email|unique:users,email|max:255',
            'password'      => 'required|string|min:8|max:64',
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'phone'         => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'validation failed',
                'details'   => $validator->messages()
            ]);
        }

        $user = $this->userRepository->create([
            'email'         => $request->input('email'),
            'password'      => $request->input('password'),
            'first_name'    => $request->input('first_name'),
            'last_name'     => $request->input('last_name'),
            'phone'         => $request->input('phone'),
        ]);

        $apiKey = $user->api_key;

        return response()->json([
            'status'    => 'success',
            'api_key'   => $apiKey,
        ], 201);
    }

    public function authenticateUser($request)
    {
        $validator = Validator::make($request->toArray(), [
            'email'     => 'required',
            'password'  => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'validation failed',
                'details'   => $validator->messages()
            ]);
        }

        $email      = $request->input('email');
        $password   = $request->input('password');
        $apiKey     = $this->userRepository->authenticate($email, $password);

        if ($apiKey) {
            return response()->json(['status' => 'success', 'api_key' => $apiKey]);
        }

        return response()->json([
            'status'    => 'error',
            'message'   => 'invalid login/password', 
        ], 401);
    }

    public function getRecoverToken($request)
    {
        $validator = Validator::make($request->toArray(), [
            'email' => 'required|exists:users,email',
        ]);
    
        if ($validator->fails()) {
            return response([
                'status'    => 'error',
                'message'   => 'validation failed',
                'details'   => $validator->messages()
            ]);
        }

        $user = $this->userRepository->getByEmail($request->email);
        $user = $this->userRepository->setPasswordRecoveryToken($user->id);

        Mail::to($user->email)->send(new PasswordRecoveryMail($user));

        return response()->json(['status' => 'success']);
    }

    public function recoverPassword($request)
    {
        $validator = Validator::make($request->toArray(), [
            'email'                 => 'required',
            'reset_password_code'   => 'required',
            'password'              => 'required|string|min:8|max:64',
        ]);
    
        if ($validator->fails()) {
            return response([
                'status'    => 'error',
                'message'   => 'validation failed',
                'details'   => $validator->messages()
            ]);
        }

        $token  = $request->reset_password_code;
        $user   = $this->userRepository->getByEmailAndToken($request->email, $token);

        if ($user) {
            $this->userRepository->updatePassword($user->id, $request->password);
            $this->userRepository->resetPasswordRecoveryToken($user->id);

            return response()->json(['status' => 'success']);
        }

        return response()->json([
            'status'    => 'error',
            'message'   => 'No user with such token and email.'
        ], 401);
    }
}