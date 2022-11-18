<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Company;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'email'         => $this->faker->unique()->safeEmail,
            'phone'         => $this->faker->phoneNumber,
            'password'      => app('hash')->make($this->faker->password(6)),
        ];
    }

    public function addCompanies(int $count = null)
    {
        $count = $count ?? rand(0, 5);
    
        return $this->afterCreating(
            fn (User $user) => Company::factory()->count($count)->for($user)->create()
        );
    }
}
