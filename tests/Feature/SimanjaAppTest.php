<?php

namespace Tests\Feature;

use App\Models\AlokasiPekerjaan;
use App\Models\HariKerjaAktif;
use App\Models\Team;
use App\Models\User;
use App\Services\KinerjaService;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimanjaAppTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    // =========================================================================
    // 1. AUTHENTICATION & NAVIGATION
    // =========================================================================

    public function test_guest_sees_landing_page_on_root(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SIMAKINBPS');
    }

    public function test_guest_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders_with_branding(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SIMAKINBPS');
        $response->assertSee('BPS Kabupaten Manggarai');
        $response->assertSee('Username');
        $response->assertSee('Password');
    }

    public function test_user_can_authenticate_successfully(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'AdminBPS@2026',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_invalid_login_rejected(): void
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'admin',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('username', 'anggota')->first();
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // =========================================================================
    // 2. ADMIN ROLE & AUTHORIZATION
    // =========================================================================

    public function test_admin_redirected_to_admin_dashboard_from_root_dashboard(): void
    {
        $admin = User::where('username', 'admin')->first();
        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_access_admin_portal_pages(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Administrator');

        $pegawaiRes = $this->actingAs($admin)->get(route('admin.pegawai.index'));
        $pegawaiRes->assertStatus(200);
        $pegawaiRes->assertSee('Daftar Pegawai & Role');

        $timRes = $this->actingAs($admin)->get(route('admin.tim.index'));
        $timRes->assertStatus(200);
        $timRes->assertSee('Manajemen Tim Kerja');

        $hkRes = $this->actingAs($admin)->get(route('admin.hari_kerja.index'));
        $hkRes->assertStatus(200);
        $hkRes->assertSee('Hari Kerja Aktif Bulanan');

        $upRes = $this->actingAs($admin)->get(route('admin.user_publik.index'));
        $upRes->assertStatus(200);
        $upRes->assertSee('Frontend User Publik');
    }

    public function test_admin_strictly_forbidden_from_performance_and_ckp_menus(): void
    {
        $admin = User::where('username', 'admin')->first();

        // Admin must not access CKP
        $ckpRes = $this->actingAs($admin)->get('/ckp');
        $ckpRes->assertStatus(403);

        // Admin must not access Penilaian
        $nilaiRes = $this->actingAs($admin)->get('/ckp/penilaian');
        $nilaiRes->assertStatus(403);

        // Admin must not access Input Alokasi
        $alokasiRes = $this->actingAs($admin)->get('/alokasi/input');
        $alokasiRes->assertStatus(403);

        // Admin must not access Grafik Kinerja
        $kinerjaRes = $this->actingAs($admin)->get('/kinerja/grafik');
        $kinerjaRes->assertStatus(403);
    }

    public function test_admin_can_manage_pegawai(): void
    {
        $admin = User::where('username', 'admin')->first();
        $tim = Team::first();

        // Create Pegawai
        $response = $this->actingAs($admin)->post(route('admin.pegawai.store'), [
            'name'                  => 'Pegawai Uji Baru',
            'username'              => 'pegawaiuji',
            'nip'                   => '199912312024011009',
            'jabatan'               => 'Statistisi Pertama',
            'golongan'              => 'III/a',
            'email'                 => 'uji@bps.go.id',
            'role'                  => 'pegawai',
            'tim_id'                => $tim->id,
            'is_ketua_tim'          => 0,
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.pegawai.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'pegawaiuji',
            'role'     => 'pegawai',
            'tim_id'   => $tim->id,
        ]);

        $created = User::where('username', 'pegawaiuji')->first();

        // Toggle Status
        $toggleRes = $this->actingAs($admin)->post(route('admin.pegawai.toggle', $created->id));
        $toggleRes->assertRedirect();
        $this->assertEquals(0, $created->fresh()->is_active);
    }

    public function test_admin_can_delete_pegawai(): void
    {
        $admin = User::where('username', 'admin')->first();
        $pegawai = User::where('username', 'budi')->first();
        $pegawaiId = $pegawai->id;

        $response = $this->actingAs($admin)->delete(route('admin.pegawai.destroy', $pegawaiId));

        $response->assertRedirect(route('admin.pegawai.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $pegawaiId]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->delete(route('admin.pegawai.destroy', $admin->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_non_admin_cannot_delete_pegawai(): void
    {
        $pegawai = User::where('username', 'anggota')->first();
        $target = User::where('username', 'budi')->first();

        $response = $this->actingAs($pegawai)->delete(route('admin.pegawai.destroy', $target->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $target->id]);
    }

    public function test_admin_can_set_hari_kerja_aktif(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->post(route('admin.hari_kerja.store'), [
            'tahun'              => 2026,
            'bulan'              => 5,
            'jumlah_hari_kerja'  => 21,
            'jam_kerja_per_hari' => 7.5,
            'keterangan'         => 'Hari kerja Mei 2026',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hari_kerja_aktifs', [
            'tahun'             => 2026,
            'bulan'             => 5,
            'jumlah_hari_kerja' => 21,
        ]);
    }

    // =========================================================================
    // 3. KETUA TIM ROLE & ALLOCATION RULES
    // =========================================================================

    public function test_ketua_tim_can_access_allocation_form(): void
    {
        $ketua = User::where('username', 'ketua')->first();

        $response = $this->actingAs($ketua)->get(route('alokasi.input'));
        $response->assertStatus(200);
        $response->assertSee('Input Alokasi Pekerjaan');
    }

    public function test_ketua_tim_can_allocate_to_team_member(): void
    {
        $ketua = User::where('username', 'ketua')->first();
        $anggota = User::where('username', 'anggota')->first();

        $response = $this->actingAs($ketua)->post(route('alokasi.store'), [
            'nama_kegiatan'    => 'Pengolahan Survei Susenas Maret 2026',
            'satuan'           => 'Dokumen',
            'target_kuantitas' => 50,
            'asal_tim'         => 'Tim Statistik Sosial',
            'pegawai_id'       => $anggota->id,
            'keterangan'       => 'Selesaikan tepat waktu',
            'estimasi_waktu'   => 25,
            'tanggal_mulai'    => '2026-03-01',
            'tanggal_selesai'  => '2026-03-15',
            'status'           => 'berjalan',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('alokasi_pekerjaans', [
            'nama_kegiatan'   => 'Pengolahan Survei Susenas Maret 2026',
            'pegawai_id'      => $anggota->id,
            'jenis_pekerjaan' => 'utama',
            'created_by'      => $ketua->id,
        ]);
    }

    public function test_ketua_tim_can_allocate_to_self(): void
    {
        $ketua = User::where('username', 'ketua')->first();

        $response = $this->actingAs($ketua)->post(route('alokasi.store'), [
            'nama_kegiatan'    => 'Supervisi Lapangan Susenas 2026',
            'satuan'           => 'Kunjungan',
            'target_kuantitas' => 5,
            'asal_tim'         => 'Tim Statistik Sosial',
            'pegawai_id'       => $ketua->id, // Self allocation
            'keterangan'       => 'Supervisi 5 kecamatan sampel',
            'estimasi_waktu'   => 30,
            'tanggal_mulai'    => '2026-03-05',
            'tanggal_selesai'  => '2026-03-12',
            'status'           => 'terjadwal',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('alokasi_pekerjaans', [
            'nama_kegiatan' => 'Supervisi Lapangan Susenas 2026',
            'pegawai_id'    => $ketua->id,
            'created_by'    => $ketua->id,
        ]);
    }

    public function test_ketua_tim_cannot_allocate_to_another_team_member(): void
    {
        $ketua = User::where('username', 'ketua')->first(); // Tim Sosial
        // Create an outside employee in Tim IPDS
        $ipdsTeam = Team::where('singkatan', 'IPDS')->first();
        $outsidePegawai = User::create([
            'name'      => 'Pegawai Luar Tim',
            'username'  => 'luartim',
            'email'     => 'luartim@bps.go.id',
            'password'  => bcrypt('password'),
            'role'      => 'pegawai',
            'tim_id'    => $ipdsTeam->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($ketua)->post(route('alokasi.store'), [
            'nama_kegiatan'    => 'Tugas Lintas Tim Ilegal',
            'satuan'           => 'Laporan',
            'target_kuantitas' => 1,
            'asal_tim'         => 'Tim Statistik Sosial',
            'pegawai_id'       => $outsidePegawai->id,
            'estimasi_waktu'   => 10,
            'tanggal_mulai'    => '2026-03-01',
            'tanggal_selesai'  => '2026-03-05',
            'status'           => 'terjadwal',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('alokasi_pekerjaans', [
            'nama_kegiatan' => 'Tugas Lintas Tim Ilegal',
        ]);
    }

    // =========================================================================
    // 4. PEGAWAI ROLE & REALISASI RULES
    // =========================================================================

    public function test_pegawai_cannot_create_primary_allocation(): void
    {
        $anggota = User::where('username', 'anggota')->first();

        // Route must be 403 Forbidden
        $response = $this->actingAs($anggota)->get(route('alokasi.input'));
        $response->assertStatus(403);

        $postRes = $this->actingAs($anggota)->post(route('alokasi.store'), [
            'nama_kegiatan'    => 'Tugas Buatan Anggota',
            'satuan'           => 'Dokumen',
            'target_kuantitas' => 10,
            'asal_tim'         => 'Tim Statistik Sosial',
            'pegawai_id'       => $anggota->id,
            'estimasi_waktu'   => 10,
            'tanggal_mulai'    => '2026-03-01',
            'tanggal_selesai'  => '2026-03-05',
            'status'           => 'berjalan',
        ]);
        $postRes->assertStatus(403);
    }

    public function test_pegawai_can_fill_own_realization(): void
    {
        $anggota = User::where('username', 'anggota')->first();
        $task = AlokasiPekerjaan::where('pegawai_id', $anggota->id)->first();

        $response = $this->actingAs($anggota)->post(route('ckp.edit_realisasi', $task->id), [
            'realisasi_kuantitas' => 10,
            'catatan_realisasi'   => 'Telah diselesaikan tepat waktu sesuai standar BPS',
            'link_bukti'          => 'https://drive.google.com/test-evidence',
            'tanggal_realisasi'   => '2026-01-20',
            'status'              => 'selesai',
        ]);

        $response->assertRedirect();
        $task->refresh();
        $this->assertEquals(10, $task->realisasi_kuantitas);
        $this->assertEquals('selesai', $task->status);
        $this->assertEquals(100.0, $task->persentase_kuantitas);
    }

    public function test_pegawai_cannot_fill_other_pegawai_realization(): void
    {
        $anggota = User::where('username', 'anggota')->first();
        $budi = User::where('username', 'budi')->first();
        $budiTask = AlokasiPekerjaan::where('pegawai_id', $budi->id)->first();

        $response = $this->actingAs($anggota)->post(route('ckp.edit_realisasi', $budiTask->id), [
            'realisasi_kuantitas' => 5,
            'tanggal_realisasi'   => '2026-01-20',
            'status'              => 'selesai',
        ]);

        $response->assertStatus(403);
    }

    public function test_pegawai_can_self_input_tambahan_task_via_ckp_saya(): void
    {
        $anggota = User::where('username', 'anggota')->first();

        $response = $this->actingAs($anggota)->post(route('ckp.aksi_tambahan'), [
            'nama_kegiatan'       => 'Kepanitiaan Hari Statistik Nasional 2026',
            'satuan'              => 'Kegiatan',
            'target_kuantitas'    => 1,
            'realisasi_kuantitas' => 1,
            'asal_tim'            => 'Tim Statistik Sosial',
            'keterangan'          => 'Tugas tambahan kepanitiaan',
            'estimasi_waktu'      => 8,
            'tanggal_mulai'       => '2026-09-20',
            'tanggal_selesai'     => '2026-09-26',
            'status'              => 'selesai',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('alokasi_pekerjaans', [
            'nama_kegiatan'   => 'Kepanitiaan Hari Statistik Nasional 2026',
            'pegawai_id'      => $anggota->id,
            'created_by'      => $anggota->id,
            'jenis_pekerjaan' => 'tambahan',
        ]);
    }

    // =========================================================================
    // 5. EVALUATION (PENILAIAN) RULES
    // =========================================================================

    public function test_ketua_tim_can_rate_team_member_primary_task(): void
    {
        $ketua = User::where('username', 'ketua')->first();
        $anggota = User::where('username', 'anggota')->first();
        $task = AlokasiPekerjaan::where('pegawai_id', $anggota->id)
            ->where('jenis_pekerjaan', 'utama')
            ->first();

        $response = $this->actingAs($ketua)->post(route('ckp.nilai_edit', $task->id), [
            'kualitas'         => 92.5,
            'catatan_penilai'  => 'Hasil sangat teliti dan rapi',
            'status_penilaian' => 'dinilai',
        ]);

        $response->assertRedirect();
        $task->refresh();
        $this->assertEquals(92.5, $task->kualitas);
        $this->assertEquals('dinilai', $task->status_penilaian);
        $this->assertEquals($ketua->id, $task->penilai_id);
    }

    public function test_ketua_tim_cannot_rate_own_primary_task(): void
    {
        $ketua = User::where('username', 'ketua')->first();
        $ownTask = AlokasiPekerjaan::where('pegawai_id', $ketua->id)
            ->where('jenis_pekerjaan', 'utama')
            ->first();

        $response = $this->actingAs($ketua)->post(route('ckp.nilai_edit', $ownTask->id), [
            'kualitas'         => 95,
            'catatan_penilai'  => 'Menilai diri sendiri',
            'status_penilaian' => 'dinilai',
        ]);

        $response->assertStatus(403);
    }

    public function test_kepala_bps_can_rate_ketua_tim_primary_task(): void
    {
        $kepala = User::where('username', 'kepala')->first();
        $ketua = User::where('username', 'ketua')->first();
        $task = AlokasiPekerjaan::where('pegawai_id', $ketua->id)
            ->where('jenis_pekerjaan', 'utama')
            ->first();

        $response = $this->actingAs($kepala)->post(route('ckp.nilai_edit', $task->id), [
            'kualitas'         => 95.0,
            'catatan_penilai'  => 'Kinerja koordinasi tim sangat memuaskan',
            'status_penilaian' => 'dinilai',
        ]);

        $response->assertRedirect();
        $task->refresh();
        $this->assertEquals(95.0, $task->kualitas);
        $this->assertEquals($kepala->id, $task->penilai_id);
    }

    public function test_kepala_bps_can_rate_additional_task_of_any_pegawai(): void
    {
        $kepala = User::where('username', 'kepala')->first();
        $anggota = User::where('username', 'anggota')->first();

        // Create an additional task for anggota
        $tambahan = AlokasiPekerjaan::create([
            'nama_kegiatan'       => 'Pekerjaan Tambahan Satgas Anti Korupsi',
            'satuan'              => 'Laporan',
            'target_kuantitas'    => 1,
            'realisasi_kuantitas' => 1,
            'asal_tim'            => 'Subbagian Umum',
            'pegawai_id'          => $anggota->id,
            'created_by'          => $anggota->id,
            'jenis_pekerjaan'     => 'tambahan',
            'estimasi_waktu'      => 12,
            'tanggal_mulai'       => Carbon::parse('2026-02-01'),
            'tanggal_selesai'     => Carbon::parse('2026-02-10'),
            'status'              => 'selesai',
            'tahun'               => 2026,
            'bulan'               => 2,
        ]);

        $response = $this->actingAs($kepala)->post(route('ckp.nilai_edit', $tambahan->id), [
            'kualitas'         => 88.0,
            'catatan_penilai'  => 'Dokumentasi bukti kegiatan lengkap',
            'status_penilaian' => 'dinilai',
        ]);

        $response->assertRedirect();
        $tambahan->refresh();
        $this->assertEquals(88.0, $tambahan->kualitas);
        $this->assertEquals($kepala->id, $tambahan->penilai_id);
    }

    // =========================================================================
    // 6. BUSINESS LOGIC & FORMULA TESTS
    // =========================================================================

    public function test_formula_skor_kinerja_uses_hour_denominator(): void
    {
        // 20 hari kerja * 7.5 jam/hari = 150 jam aktif
        $hariKerja = HariKerjaAktif::updateOrCreate(
            ['tahun' => 2026, 'bulan' => 10],
            ['jumlah_hari_kerja' => 20, 'jam_kerja_per_hari' => 7.5]
        );
        $totalJamAktif = 20 * 7.5; // 150 jam

        $pegawai = User::where('username', 'anggota')->first();

        // Bersihkan data bulan 10 seeder untuk uji presisi
        AlokasiPekerjaan::where('pegawai_id', $pegawai->id)->where('tahun', 2026)->where('bulan', 10)->delete();

        // Add 75 jam estimasi
        AlokasiPekerjaan::create([
            'nama_kegiatan'       => 'Pekerjaan Uji Skor 75 Jam',
            'satuan'              => 'Paket',
            'target_kuantitas'    => 1,
            'realisasi_kuantitas' => 1,
            'asal_tim'            => 'Tim Statistik Sosial',
            'pegawai_id'          => $pegawai->id,
            'created_by'          => $pegawai->id,
            'jenis_pekerjaan'     => 'utama',
            'estimasi_waktu'      => 75,
            'tanggal_mulai'       => Carbon::parse('2026-10-01'),
            'tanggal_selesai'     => Carbon::parse('2026-10-15'),
            'status'              => 'selesai',
            'tahun'               => 2026,
            'bulan'               => 10,
        ]);

        $metrics = KinerjaService::hitungKinerjaPegawai($pegawai->id, 2026, 10);

        // Skor Kinerja = (75 / 150) * 100 = 50.0%
        $this->assertEquals(50.0, $metrics['skor_kinerja']);
        $this->assertEquals(75, $metrics['total_estimasi_jam']);
        $this->assertEquals($totalJamAktif, $metrics['jam_kerja_aktif']);
    }

    public function test_formula_ckp_averages_quantity_and_quality(): void
    {
        $pegawai = User::where('username', 'budi')->first();

        // Bersihkan data bulan 11 untuk uji presisi
        AlokasiPekerjaan::where('pegawai_id', $pegawai->id)
            ->where('tahun', 2026)
            ->where('bulan', 11)
            ->delete();

        // Tugas 1: Target = 10, Realisasi = 8 -> Kuantitas = 80%, Kualitas = 90%
        AlokasiPekerjaan::create([
            'nama_kegiatan'       => 'Tugas 1',
            'satuan'              => 'Dokumen',
            'target_kuantitas'    => 10,
            'realisasi_kuantitas' => 8,
            'asal_tim'            => 'Tim Statistik Sosial',
            'pegawai_id'          => $pegawai->id,
            'created_by'          => $pegawai->id,
            'jenis_pekerjaan'     => 'utama',
            'estimasi_waktu'      => 10,
            'tanggal_mulai'       => Carbon::parse('2026-11-01'),
            'tanggal_selesai'     => Carbon::parse('2026-11-05'),
            'status'              => 'selesai',
            'tahun'               => 2026,
            'bulan'               => 11,
            'kualitas'            => 90,
            'status_penilaian'    => 'dinilai',
        ]);

        // Tugas 2: Target = 20, Realisasi = 20 -> Kuantitas = 100%, Kualitas = 85%
        AlokasiPekerjaan::create([
            'nama_kegiatan'       => 'Tugas 2',
            'satuan'              => 'Dokumen',
            'target_kuantitas'    => 20,
            'realisasi_kuantitas' => 20,
            'asal_tim'            => 'Tim Statistik Sosial',
            'pegawai_id'          => $pegawai->id,
            'created_by'          => $pegawai->id,
            'jenis_pekerjaan'     => 'utama',
            'estimasi_waktu'      => 20,
            'tanggal_mulai'       => Carbon::parse('2026-11-06'),
            'tanggal_selesai'     => Carbon::parse('2026-11-10'),
            'status'              => 'selesai',
            'tahun'               => 2026,
            'bulan'               => 11,
            'kualitas'            => 85,
            'status_penilaian'    => 'dinilai',
        ]);

        $metrics = KinerjaService::hitungKinerjaPegawai($pegawai->id, 2026, 11);

        // Rata-rata Kuantitas = (80 + 100) / 2 = 90%
        // Rata-rata Kualitas  = (90 + 85) / 2 = 87.5%
        // CKP = (90 + 87.5) / 2 = 88.75
        $this->assertEquals(90.0, $metrics['avg_kuantitas']);
        $this->assertEquals(87.5, $metrics['avg_kualitas']);
        $this->assertEquals(88.75, $metrics['nilai_ckp']);
    }

    // =========================================================================
    // 7. EXPORTS: EXCEL & PDF
    // =========================================================================

    public function test_laporan_ckp_export_excel(): void
    {
        $user = User::where('username', 'anggota')->first();

        $response = $this->actingAs($user)->get(route('ckp.export_excel', ['tahun' => 2026, 'bulan' => 1, 'pegawai_id' => $user->id]));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
        $this->assertStringContainsString('LAPORAN CAPAIAN KINERJA PEGAWAI', $response->getContent());
    }

    public function test_laporan_ckp_export_pdf_preview(): void
    {
        $user = User::where('username', 'anggota')->first();

        $response = $this->actingAs($user)->get(route('ckp.export_pdf', ['tahun' => 2026, 'bulan' => 1, 'pegawai_id' => $user->id]));
        $response->assertStatus(200);
        $response->assertSee('BADAN PUSAT STATISTIK');
        $response->assertSee('LAPORAN CAPAIAN KINERJA PEGAWAI');
        $response->assertSee('window.print()');
    }
}
