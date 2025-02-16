<?php declare(strict_types=1);

namespace Auth\Dev;

use Sys\Fake\Generator;

class UserFakeGenerator extends Generator
{
    private array $sex = ['female', 'male'];

    public function generate($seed = true): array
    {
        $sex = $this->faker->optional(0.8)->randomElement([0, 1]);
        $email = $this->faker->email();

        $fields = [
            'name' => $this->userName($this->sex[$sex] ?? null),
            'email' => $email,
            'phone' => $this->faker->optional(0.6)->numerify('###########'),
            'dob' => $this->faker->optional(0.6)->date('Y-m-d', '2006-01-01'),
            'sex' => $sex,
            'info' => $this->faker->optional(0.6)->json(fn() => ['baz' => 'ban']),
            'password' => password_hash($this->faker->password(3, 8), PASSWORD_BCRYPT),
            'created' => $this->faker->dateTimeBetween('-13 years')->format('Y-m-d h:i:s'),
            
        ];

        return $fields;
    }

    public function avatar($user_id, $probability = 0.9, int $size = 120)
    {
        $dir = config('upload', 'avatar');
        $mode = $this->faker->randomElement(parent::MODES);
        $filename = $this->faker->optional($probability)->gravatar($dir, $mode, null, $size, true);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $new_filename = $dir . $user_id . '.' . $ext;
        rename($filename, $new_filename);

        return $new_filename;
    }

    private function userName($gender = null)
    {
        if (!$gender) {
            $gender = $this->faker->randomElement($this->sex);
        }

        return $this->faker->firstName($gender) . ' ' . $this->faker->lastName($gender);
    }
}
