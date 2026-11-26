<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKelolaPegawaiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed database or setup admin
    }

    public function test_admin_can_view_create_and_edit_forms_with_no_hp_and_without_ketua_checkbox()
    {
        $admin = User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $pegawai = User::factory()->create([
            'role'      => 'pegawai',
            'no_hp'     => '081234567890',
            'is_active' => true,
        ]);

        // 1. Create page
        $responseCreate = $this->actingAs($admin)->get(route('admin.pegawai.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('name="no_hp"', false);
        $responseCreate->assertDontSee('Tugaskan sebagai Ketua Tim');
        $responseCreate->assertDontSee('name="is_ketua_tim"', false);

        // 2. Edit page
        $responseEdit = $this->actingAs($admin)->get(route('admin.pegawai.edit', $pegawai->id));
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('name="no_hp"', false);
        $responseEdit->assertSee('081234567890');
        $responseEdit->assertDontSee('Tugaskan sebagai Ketua Tim');
        $responseEdit->assertDontSee('name="is_ketua_tim"', false);
    }

    public function test_admin_can_store_pegawai_with_no_hp_and_role_sets_is_ketua_tim()
    {
        $admin = User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $team = Team::create([
            'nama_tim'   => 'Tim Statistik Produksi',
            'singkatan'  => 'PROD',
            'is_active'  => true,
        ]);

        // Simpan Pegawai dengan Role Ketua Tim
        $response = $this->actingAs($admin)->post(route('admin.pegawai.store'), [
            'name'      => 'Budi Santoso',
            'username'  => 'budisantoso',
            'email'     => 'budi@bps.go.id',
            'no_hp'     => '081299988877',
            'password'  => 'password123',
            'role'      => 'ketua_tim',
            'tim_id'    => $team->id,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.pegawai.index'));

        $this->assertDatabaseHas('users', [
            'username'     => 'budisantoso',
            'no_hp'        => '081299988877',
            'role'         => 'ketua_tim',
            'is_ketua_tim' => 1,
        ]);

        // Pastikan ketua_id di tim terupdate
        $budi = User::where('username', 'budisantoso')->first();
        $this->assertEquals($budi->id, $team->fresh()->ketua_id);
    }

    public function test_admin_can_update_pegawai_no_hp_and_role()
    {
        $admin = User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $pegawai = User::factory()->create([
            'name'         => 'Siti Rahma',
            'username'     => 'sitirahma',
            'email'        => 'siti@bps.go.id',
            'no_hp'        => '0811111111',
            'role'         => 'pegawai',
            'is_ketua_tim' => 0,
            'is_active'    => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.pegawai.update', $pegawai->id), [
            'name'      => 'Siti Rahma, S.Stat.',
            'username'  => 'sitirahma',
            'email'     => 'siti@bps.go.id',
            'no_hp'     => '0822222222',
            'role'      => 'pegawai',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.pegawai.index'));

        $this->assertDatabaseHas('users', [
            'id'           => $pegawai->id,
            'no_hp'        => '0822222222',
            'role'         => 'pegawai',
            'is_ketua_tim' => 0,
        ]);
    }
}
