<?php

namespace App\Interfaces;

interface UserServiceInterface
{
    public function registerUser($request);

    public function authenticateUser($request);

    public function getRecoverToken($request);

    public function recoverPassword($request);
}