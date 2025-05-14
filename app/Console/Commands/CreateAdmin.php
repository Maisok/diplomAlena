<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'create:admin';

    protected $description = 'Create an admin user';

    public function handle()
    {
        $name = $this->ask('Enter admin name');
        $login = $this->ask('Enter admin login (5 digits)');
        $password = $this->secret('Enter admin password');

        // Проверка на длину логина
        if (strlen($login) !== 5 || !is_numeric($login)) {
            $this->error('Login must be exactly 5 digits.');
            return;
        }

        $user = User::create([
            'last_name' => $name,
            'first_name' => $name,
            'patronymic' => $name,
            'status' => 'admin',
            'login' => $login,
            'password' => Hash::make($password), // Добавляем пароль
        ]);

        $this->info('Admin user created successfully.');
    }
}