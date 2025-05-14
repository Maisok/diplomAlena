<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест на добавление пользователя, когда администратор авторизован.
     *
     * @return void
     */
    public function test_admin_can_add_user()
    {
        // Создаем администратора и авторизуем его
        $admin = User::factory()->create([
            'status' => 'admin',
        ]);

        $this->actingAs($admin);

        // Данные для нового пользователя
        $userData = [
            'last_name' => 'Иванов',
            'first_name' => 'Иван',
            'patronymic' => 'Иванович',
            'status' => 'parent',
            'phone_number' => '8 123 456 78 90',
            'email' => 'test@example.com',
            'login' => 'test',
            'password' => 'password123',
        ];

        // Отправляем POST-запрос на создание пользователя
        $response = $this->withoutMiddleware()->post(route('users.store'), $userData);

        // Проверяем, что пользователь был создан
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'Пользователь успешно создан.');

        // Проверяем, что пользователь был добавлен в базу данных
        $this->assertDatabaseHas('users', [
            'last_name' => 'Иванов',
            'first_name' => 'Иван',
            'patronymic' => 'Иванович',
            'status' => 'parent',
            'phone_number' => '8 123 456 78 90',
            'email' => 'test@example.com',
            'login' => 'test',
        ]);

        // Проверяем, что пароль был хеширован
        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * Тест на проверку методов isAdmin, isParent, isEducator.
     *
     * @return void
     */
    public function test_user_status_methods()
    {
        // Создаем пользователя с ролью администратора
        $admin = User::factory()->create([
            'status' => 'admin',
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isParent());
        $this->assertFalse($admin->isEducator());

        // Создаем пользователя с ролью родителя
        $parent = User::factory()->create([
            'status' => 'parent',
        ]);

        $this->assertFalse($parent->isAdmin());
        $this->assertTrue($parent->isParent());
        $this->assertFalse($parent->isEducator());

        // Создаем пользователя с ролью педагога
        $educator = User::factory()->create([
            'status' => 'educator',
        ]);

        $this->assertFalse($educator->isAdmin());
        $this->assertFalse($educator->isParent());
        $this->assertTrue($educator->isEducator());
    }
}