<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;



    /**
     * Тест на неуспешную авторизацию пользователя с неправильными учетными данными.
     *
     * @return void
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        // Создаем пользователя
        $user = User::factory()->create([
            'login' => '12345',
            'password' => Hash::make('password123'),
        ]);

        // Данные для авторизации с неправильным паролем
        $credentials = [
            'login' => '12345',
            'password' => 'wrongpassword',
        ];

        // Отправляем POST-запрос на авторизацию
        $response = $this->withoutMiddleware()->post(route('login'), $credentials);

        // Проверяем, что пользователь был перенаправлен обратно на страницу входа
        $response->assertRedirect('/');

        // Проверяем, что пользователь не авторизован
        $this->assertGuest();
    }

    /**
     * Тест на неуспешную авторизацию пользователя с отсутствующим логином.
     *
     * @return void
     */
    public function test_user_cannot_login_without_login()
    {
        // Создаем пользователя
        $user = User::factory()->create([
            'login' => '12345',
            'password' => Hash::make('password123'),
        ]);

        // Данные для авторизации без логина
        $credentials = [
            'password' => 'password123',
        ];

        // Отправляем POST-запрос на авторизацию
        $response = $this->withoutMiddleware()->post(route('login'), $credentials);

        // Проверяем, что пользователь был перенаправлен обратно на страницу входа
        $response->assertRedirect('/');

        // Проверяем, что пользователь не авторизован
        $this->assertGuest();
    }

    /**
     * Тест на неуспешную авторизацию пользователя с отсутствующим паролем.
     *
     * @return void
     */
    public function test_user_cannot_login_without_password()
    {
        // Создаем пользователя
        $user = User::factory()->create([
            'login' => '12345',
            'password' => Hash::make('password123'),
        ]);

        // Данные для авторизации без пароля
        $credentials = [
            'login' => '12345',
        ];

        // Отправляем POST-запрос на авторизацию
        $response = $this->withoutMiddleware()->post(route('login'), $credentials);

        // Проверяем, что пользователь был перенаправлен обратно на страницу входа
        $response->assertRedirect('/');

        // Проверяем, что пользователь не авторизован
        $this->assertGuest();
    }
}