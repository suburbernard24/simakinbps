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

    public function test_admin_can_download_template_with_no_hp_column()
    {
        $admin = User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.pegawai.template'));
        $response->assertStatus(200);
        $this->assertStringContainsString('spreadsheetml.sheet', $response->headers->get('content-type'));

        // Load spreadsheet from streamed content
        $content = $response->streamedContent();
        $tempFile = tempnam(sys_get_temp_dir(), 'tmpl_');
        file_put_contents($tempFile, $content);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tempFile);
        $sheet = $spreadsheet->getActiveSheet();

        // Check header column H3 is No. Handphone / WhatsApp
        $this->assertEquals('No. Handphone / WhatsApp', $sheet->getCell('H3')->getValue());
        $this->assertEquals('081234567890', $sheet->getCell('H4')->getValue());

        @unlink($tempFile);
    }

    public function test_admin_can_import_excel_with_no_hp_column()
    {
        $admin = User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $team = Team::create([
            'nama_tim'   => 'Tim Neraca Wilayah',
            'singkatan'  => 'NERACA',
            'is_active'  => true,
        ]);

        // Create spreadsheet with new template format
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = [
            'A3' => 'No',
            'B3' => 'Nama dan Gelar (*)',
            'C3' => 'Username Login (*)',
            'D3' => 'Password (*)',
            'E3' => 'Tim Kerja BPS',
            'F3' => 'NIP',
            'G3' => 'Email Resmi',
            'H3' => 'No. Handphone / WhatsApp',
            'I3' => 'Jabatan',
            'J3' => 'Golongan/Pangkat',
            'K3' => 'Role Akses (*)',
            'L3' => 'Status Akun Aktif (*)',
        ];
        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $row4 = [
            'A4' => '1',
            'B4' => 'Ahmad Dahlan',
            'C4' => 'ahmaddahlan',
            'D4' => 'password123',
            'E4' => 'Tim Neraca Wilayah',
            'F4' => '199001012015011001',
            'G4' => 'ahmad@bps.go.id',
            'H4' => '085233445566',
            'I4' => 'Pranata Komputer',
            'J4' => 'III/b',
            'K4' => 'Ketua Tim',
            'L4' => 'Ya',
        ];
        foreach ($row4 as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'test_import_') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempFile,
            'import_pegawai.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($admin)->post(route('admin.pegawai.import'), [
            'file_excel' => $uploadedFile,
        ]);

        $response->assertRedirect(route('admin.pegawai.index'));

        $this->assertDatabaseHas('users', [
            'username'     => 'ahmaddahlan',
            'no_hp'        => '085233445566',
            'role'         => 'ketua_tim',
            'is_ketua_tim' => 1,
            'tim_id'       => $team->id,
        ]);

        @unlink($tempFile);
    }
}
