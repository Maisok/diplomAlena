<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificateManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_certificates_index_page()
    {
        $admin = User::factory()->create(['status' => 'admin']);
        Certificate::factory()->count(5)->create();

        $response = $this->actingAs($admin)
            ->get(route('certificates.index'));

        $response->assertOk()
            ->assertViewIs('admin.certificates.index')
            ->assertViewHas('certificates');
    }

    /** @test */
    public function non_admin_cannot_view_certificates_index_page()
    {
        $user = User::factory()->create(['status' => 'parent']);

        $response = $this->actingAs($user)
            ->get(route('certificates.index'));

        $response->assertRedirect(route('home'));
    }

    /** @test */
    public function admin_can_view_create_certificate_page()
    {
        $admin = User::factory()->create(['status' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('certificates.create'));

        $response->assertOk()
            ->assertViewIs('admin.certificates.create');
    }

    /** @test */
    public function admin_can_store_new_certificate()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['status' => 'admin']);
        $file = UploadedFile::fake()->image('certificate.jpg', 500, 500);

        $response = $this->actingAs($admin)
            ->post(route('certificates.store'), [
                'title' => 'Test Certificate',
                'image' => $file,
                'type' => 'certificate',
            ]);

        $response->assertRedirect(route('certificates.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('certificates', [
            'title' => 'Test Certificate',
            'type' => 'certificate',
            'admin_id' => $admin->id,
        ]);

        Storage::disk('public')->assertExists('certificates/test-certificate-' . time() . '.jpg');
    }

    /** @test */
    public function admin_can_view_edit_certificate_page()
    {
        $admin = User::factory()->create(['status' => 'admin']);
        $certificate = Certificate::factory()->create(['admin_id' => $admin->id]);

        $response = $this->actingAs($admin)
            ->get(route('certificates.edit', $certificate));

        $response->assertOk()
            ->assertViewIs('admin.certificates.edit')
            ->assertViewHas('certificate', $certificate);
    }

    /** @test */
    public function admin_can_update_certificate()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['status' => 'admin']);
        $certificate = Certificate::factory()->create(['admin_id' => $admin->id]);
        $file = UploadedFile::fake()->image('updated.jpg');

        $response = $this->actingAs($admin)
            ->put(route('certificates.update', $certificate), [
                'title' => 'Updated Title',
                'image' => $file,
                'type' => 'license',
            ]);

        $response->assertRedirect(route('certificates.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('certificates', [
            'id' => $certificate->id,
            'title' => 'Updated Title',
            'type' => 'license',
        ]);

        Storage::disk('public')->assertExists('certificates/updated-title-' . time() . '.jpg');
    }

    /** @test */
    public function admin_can_delete_certificate()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['status' => 'admin']);
        $certificate = Certificate::factory()->create(['admin_id' => $admin->id]);

        $response = $this->actingAs($admin)
            ->delete(route('certificates.destroy', $certificate));

        $response->assertRedirect(route('certificates.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('certificates', ['id' => $certificate->id]);
    }

    /** @test */
    public function certificate_requires_valid_data()
    {
        $admin = User::factory()->create(['status' => 'admin']);

        // Тест с пустыми данными
        $response = $this->actingAs($admin)
            ->post(route('certificates.store'), []);

        $response->assertSessionHasErrors(['title', 'image', 'type']);

        // Тест с невалидным изображением
        $invalidFile = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->actingAs($admin)
            ->post(route('certificates.store'), [
                'title' => 'Test',
                'image' => $invalidFile,
                'type' => 'invalid',
            ]);

        $response->assertSessionHasErrors(['image', 'type']);
    }
}