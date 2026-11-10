<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ProfileViewTest extends TestCase
{
    public function test_admin_can_view_profile(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('profil.show'));

        $response->assertOk();
        $response->assertSee('TOTAL DOKUMEN');
        $response->assertSee('TOTAL PENGGUNA');
        $response->assertSee('STATUS AKUN');
        $response->assertSee('Informasi akun');
        $response->assertSee('Admin');
    }

    public function test_guru_can_view_profile(): void
    {
        $guru = User::where('role', 'guru')->first() ?? User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('profil.show'));

        $response->assertOk();
        $response->assertSee('TOTAL KELAS');
        $response->assertSee('SISWA TERPANTAU');
        $response->assertSee('STATUS AKUN');
        $response->assertSee('Informasi akun');
        $response->assertSee('SEKOLAH');
        $response->assertSee('Guru');
    }

    public function test_siswa_can_view_profile(): void
    {
        $siswa = User::where('role', 'siswa')->first() ?? User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->get(route('profil.show'));

        $response->assertOk();
        $response->assertSee('TOTAL POIN');
        $response->assertSee('SOAL SELESAI');
        $response->assertSee('STATUS AKUN');
        $response->assertSee('Hari Streak');
        $response->assertSee('Informasi akun');
        $response->assertSee('Edit profil');
        $response->assertSee('KELAS');
    }
}
