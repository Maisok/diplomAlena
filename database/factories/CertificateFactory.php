<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'image' => 'certificates/' . Str::random(10) . '.jpg',
            'type' => $this->faker->randomElement(['license', 'certificate']),
            'admin_id' => User::factory()->create(['status' => 'admin']),
        ];
    }
}