<?php

declare(strict_types=1);

namespace App\Dev;

use Faker;

class FakeUsersGenerator
{
    private $faker;

    public function __construct(private ModelFakeUser $model, string $locale)
    {
        $this->faker = Faker\Factory::create($locale);
    }
    
    public function generate(int $count)
    {
        $gender = ['female', 'male'];
        $data = [];

        for ($i = 0; $i < $count; $i++) {
            $dob = $this->faker->dateTimeBetween('-80 years', '-15 years')
                ->getTimestamp();

            $sex = $this->faker->randomKey($gender);

            $data[$i] = [
                'name' => $this->faker->name($gender[$sex]),
                'email' => $this->faker->unique()->email(),
                'dob' => date('Y-m-d', $dob),
                'sex' => $sex,
                'password' => password_hash('123', PASSWORD_DEFAULT),
            ];
        }

        $this->model->insert($data);
    }
}
