<?php

namespace App\Http\Controllers;

use App\Interfaces\UserServiceInterface;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function register()
    {
        return $this->userService->registerUser(request());
    }

    public function signIn()
    {
        return $this->userService->authenticateUser(request());
    }

    public function requestRecoverToken()
    {
        return $this->userService->getRecoverToken(request());
    }
    
    public function recoverPassword()
    {
        return $this->userService->recoverPassword(request());
    }
}