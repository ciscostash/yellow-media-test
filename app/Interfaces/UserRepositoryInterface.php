<?php

namespace App\Interfaces;

use App\Models\User;
use App\Interfaces\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $userData): User;

    public function authenticate($email, $password): string|False;
  
    public function update($userId, array $newData): User;

    public function getByEmail($email): ?User;

    public function setPasswordRecoveryToken($userId): User;

    public function getByEmailAndToken($email, $token): ?User;

    public function updatePassword($userId, $password): void;

    public function resetPasswordRecoveryToken($userId): void;
}