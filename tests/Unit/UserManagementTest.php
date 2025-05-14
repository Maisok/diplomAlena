<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;


    /** @test */
    public function admin_can_create_a_new_user_with_valid_data()
{
    $admin = User::factory()->create([
        'status' => 'admin',
        'login' => 'admin1',
        'password' => bcrypt('password123'),
        'email' => 'admin@example.com',
        'phone_number' => '8 999 111 11 11'
    ]);

    // Используем латинские символы и простые данные
    $response = $this->actingAs($admin)
        ->post(route('users.store'), [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'patronymic' => 'Smith',
            'status' => 'parent',
            'phone_number' => '8 999 123 45 67',
            'email' => 'john.doe@example.com', // email в нижнем регистре
            'login' => 'johnd',
            'password' => 'password123',
        ]);

    $response->assertRedirect(route('users.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'login' => 'johnd',
    ]);
}

    /** @test */
    public function non_admin_users_cannot_create_new_users()
    {
        $parent = User::factory()->create(['status' => 'parent']);
        
        $response = $this->actingAs($parent)
            ->post(route('users.store'), [
                'last_name' => 'Ivan',
                'first_name' => 'Ivan',
                'status' => 'parent',
                'phone_number' => '8 999 123 45 67',
                'email' => 'Ivan@example.com',
                'login' => 'ivan1',
                'password' => 'password123',
            ]);
            
        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('users', ['email' => 'Ivan@example.com']);
    }

    /** @test */
    public function user_creation_requires_all_mandatory_fields()
    {
        $admin = User::factory()->create(['status' => 'admin']);
        
        $response = $this->actingAs($admin)
            ->post(route('users.store'), []);
            
        $response->assertSessionHasErrors([
            'last_name', 'first_name', 'status', 
            'phone_number', 'email', 'login', 'password'
        ]);
    }

    /** @test */
    public function last_name_must_contain_only_valid_characters()
    {
        $admin = User::factory()->create(['status' => 'admin']);
        
        $invalidNames = [
            'Ivan123', // цифры
            'Ivan!',   // спецсимвол
            'Iv@an',  // недопустимый символ
        ];
        
        foreach ($invalidNames as $name) {
            $response = $this->actingAs($admin)
                ->post(route('users.store'), [
                    'last_name' => $name,
                    'first_name' => 'Ivan',
                    'status' => 'parent',
                    'phone_number' => '8 999 123 45 67',
                    'email' => 'Ivan@example.com',
                    'login' => 'ivan1',
                    'password' => 'password123',
                ]);
                
            $response->assertSessionHasErrors('last_name');
        }
    }

    /** @test */
    public function phone_number_must_be_in_correct_format()
    {
        $admin = User::factory()->create(['status' => 'admin']);
        
        $invalidPhones = [
            '89991234567',    // без пробелов
            '8(999)123-45-67', // с другими символами
            '7 999 123 45 67', // начинается не с 8
            '8 999 123 4567',  // неправильные пробелы
        ];
        
        foreach ($invalidPhones as $phone) {
            $response = $this->actingAs($admin)
                ->post(route('users.store'), [
                    'last_name' => 'Ivan',
                    'first_name' => 'Ivan',
                    'status' => 'parent',
                    'phone_number' => $phone,
                    'email' => 'Ivan@example.com',
                    'login' => 'ivan1',
                    'password' => 'password123',
                ]);
                
            $response->assertSessionHasErrors('phone_number');
        }
    }

    /** @test */
    public function email_must_be_unique()
    {
        $admin = User::factory()->create(['status' => 'admin']);
        User::factory()->create(['email' => 'existing@example.com']);
        
        $response = $this->actingAs($admin)
            ->post(route('users.store'), [
                'last_name' => 'Ivan',
                'first_name' => 'Ivan',
                'status' => 'parent',
                'phone_number' => '8 999 123 45 67',
                'email' => 'existing@example.com',
                'login' => 'ivan1',
                'password' => 'password123',
            ]);
            
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function login_must_be_exactly_5_characters()
    {
        $admin = User::factory()->create(['status' => 'admin']);
        
        // Проверка слишком короткого логина
        $response = $this->actingAs($admin)
            ->post(route('users.store'), [
                'last_name' => 'Ivan',
                'first_name' => 'Ivan',
                'status' => 'parent',
                'phone_number' => '8 999 123 45 67',
                'email' => 'Ivan@example.com',
                'login' => 'ivan',
                'password' => 'password123',
            ]);
            
        $response->assertSessionHasErrors('login');
        
        // Проверка слишком длинного логина
        $response = $this->actingAs($admin)
            ->post(route('users.store'), [
                'last_name' => 'Ivan',
                'first_name' => 'Ivan',
                'status' => 'parent',
                'phone_number' => '8 999 123 45 67',
                'email' => 'Ivan@example.com',
                'login' => 'ivan12',
                'password' => 'password123',
            ]);
            
        $response->assertSessionHasErrors('login');
    }

    /** @test */
    public function password_must_be_at_least_8_characters()
    {
        $admin = User::factory()->create(['status' => 'admin']);
        
        $response = $this->actingAs($admin)
            ->post(route('users.store'), [
                'last_name' => 'Ivan',
                'first_name' => 'Ivan',
                'status' => 'parent',
                'phone_number' => '8 999 123 45 67',
                'email' => 'Ivan@example.com',
                'login' => 'ivan1',
                'password' => 'short',
            ]);
            
        $response->assertSessionHasErrors('password');
    }
}