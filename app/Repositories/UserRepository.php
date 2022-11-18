<?php
namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function create(array $userData): User
    {
        $userData['api_key'] = $this->generateRandomApiKey();
        $userData['password'] = app('hash')->make($userData['password']);

        return $this->model->create($userData);
    }

    public function authenticate($email, $password): string|False
    {
        $user = $this->model->where('email', $email)->first();
        
        if (Hash::check($password, $user->password)) {
            $apiKey = $this->generateRandomApiKey();
            
            $user->api_key = $apiKey;
            $user->save();

            return $apiKey;
        }

        return False;
    }
  
    public function update($userId, array $newData): User
    {
        $user = $this->model->find($userId);
        $user->fill($newData);
        $user->save();

        return $user;
    }

    public function getByEmail($email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function setPasswordRecoveryToken($userId): User
    {
        $token = base64_encode(Str::random(40));

        $user = $this->model->find($userId);
        $user->reset_password_code = $token;
        $user->save();

        return $user;
    }

    public function getByEmailAndToken($email, $token): ?User
    {
        return $this->model->where([
            'email' => $email,
            'reset_password_code' => $token,
        ])->first();
    }

    public function updatePassword($userId, $password): void
    {
        $user = $this->model->find($userId);
        $user->password = app('hash')->make($password);
        $user->save();
    }

    public function resetPasswordRecoveryToken($userId): void
    {
        $user = $this->model->find($userId);
        $user->reset_password_code = "";
        $user->save();
    }

    private function generateRandomApiKey()
    {
        do {
            $apiKey = base64_encode(Str::random(40));
        } while ($this->model->where(['api_key' => $apiKey])->first());

        return $apiKey;
    }
}