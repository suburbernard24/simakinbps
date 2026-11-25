<?php

namespace Tests\Feature;

use App\Models\AlokasiMitra;
use App\Models\Mitra;
use App\Models\PengaturanHonorMitra;
use App\Models\PenilaianMitra;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KinerjaMitraTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $kepala;
    protected User $ketua;
    protected User $anggota;
    protected Team $tim;
    protected Mitra $mitra;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin   = User::where('username', 'admin')->first();
        $this->kepala  = User::where('username', 'kepala')->first();
        $this->ketua   = User::where('username', 'ketua')->first();
        $this->anggota = User::where('username', 'anggota')->first();
        $this->tim     = Team::first();
        $this->mitra   = Mitra::first();

        // Clear pre-existing seeded allocations for clean quota assertions in test suite
        AlokasiMitra::where('mitra_id', $this->mitra->id)->delete();
    }

    // =========================================================================
    // 1. RBAC & ROLE ACCESS TESTS
    // =========================================================================

    public function test_admin_can_access_and_set_maksimal_honor_mitra(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.honor_mitra.index'));
        $response->assertStatus(200);
        $response->assertSee('Batas Maksimal Honor Mitra');

        $responsePost = $this->actingAs($this->admin)->post(route('admin.honor_mitra.store'), [
            'maksimal_honor' => '5.000.000',
            'tahun'          => 2026,
            'bulan'          => 10,
            'keterangan'     => 'Uji Batas Honor',
        ]);

        $responsePost->assertRedirect();
        $this->assertDatabaseHas('pengaturan_honor_mitras', [
            'maksimal_honor' => 5000000,
            'tahun'          => 2026,
            'bulan'          => 10,
        ]);
        $this->assertEquals(5000000, PengaturanHonorMitra::getMaksimalHonor(2026, 10));
    }

    public function test_ketua_tim_can_manage_master_mitra(): void
    {
        $response = $this->actingAs($this->ketua)->get(route('kinerja-mitra.master_mitra.index'));
        $response->assertStatus(200);
        $response->assertSee('Master Data Mitra');

        $responsePost = $this->actingAs($this->ketua)->post(route('kinerja-mitra.master_mitra.store'), [
            'sobat_id'       => '5310-M999',
            'nama'           => 'Mitra Uji Baru',
            'nik'            => '5310123456789999',
            'jenis_kelamin'  => 'Laki-laki',
            'provinsi'       => 'Nusa Tenggara Timur',
            'kabupaten'      => 'Kabupaten Manggarai',
            'posisi_default' => 'Petugas Pencacah Lapangan (PCL)',
        ]);

        $responsePost->assertRedirect(route('kinerja-mitra.master_mitra.index'));
        $this->assertDatabaseHas('mitras', [
            'sobat_id' => '5310-M999',
            'nama'     => 'Mitra Uji Baru',
        ]);
    }

    public function test_admin_cannot_access_master_mitra(): void
    {
        // Admin forbidden from Ketua Tim master-mitra route
        $response = $this->actingAs($this->admin)->get(route('kinerja-mitra.master_mitra.index'));
        $response->assertStatus(403);
    }

    public function test_anggota_tim_cannot_access_master_mitra(): void
    {
        // Anggota Tim forbidden from Ketua Tim master-mitra route
        $response = $this->actingAs($this->anggota)->get(route('kinerja-mitra.master_mitra.index'));
        $response->assertStatus(403);
    }

    public function test_admin_cannot_allocate_or_rate_mitra(): void
    {
        // Admin forbidden from non-admin kinerja-mitra routes
        $responseAlokasi = $this->actingAs($this->admin)->get(route('kinerja-mitra.alokasi.index'));
        $responseAlokasi->assertStatus(403);

        $responseNilai = $this->actingAs($this->admin)->get(route('kinerja-mitra.penilaian.index'));
        $responseNilai->assertStatus(403);
    }

    public function test_ketua_tim_can_access_alokasi_and_penilaian_mitra(): void
    {
        $responseAlokasi = $this->actingAs($this->ketua)->get(route('kinerja-mitra.alokasi.index'));
        $responseAlokasi->assertStatus(200);
        $responseAlokasi->assertSee('Alokasi Pekerjaan Mitra');

        $responseNilai = $this->actingAs($this->ketua)->get(route('kinerja-mitra.penilaian.index'));
        $responseNilai->assertStatus(200);
        $responseNilai->assertSee('Penilaian Kinerja Mitra');
    }

    public function test_anggota_tim_cannot_access_alokasi_mitra_returns_403(): void
    {
        // Anggota Tim tries to access alokasi: MUST BE 403
        $response = $this->actingAs($this->anggota)->get(route('kinerja-mitra.alokasi.index'));
        $response->assertStatus(403);

        $responsePost = $this->actingAs($this->anggota)->post(route('kinerja-mitra.alokasi.store'), [
            'mitra_id'         => $this->mitra->id,
            'nama_kegiatan'    => 'Kegiatan Ilegal Anggota',
            'kegiatan'         => 'Rincian',
            'posisi'           => 'PCL',
            'satuan'           => 'Dokumen',
            'target_kuantitas' => 5,
            'honor'            => 1000000,
            'tanggal_mulai'    => '2026-10-01',
            'tanggal_selesai'  => '2026-10-15',
        ]);
        $responsePost->assertStatus(403);
    }

    public function test_anggota_tim_can_access_penilaian_mitra(): void
    {
        $response = $this->actingAs($this->anggota)->get(route('kinerja-mitra.penilaian.index'));
        $response->assertStatus(200);
        $response->assertSee('Penilaian Kinerja Mitra');
    }

    public function test_kepala_bps_is_viewer_only_and_cannot_create_alokasi_or_rate(): void
    {
        // Kepala can view catalog and detail
        $responseDetail = $this->actingAs($this->kepala)->get(route('kinerja-mitra.mitra.show', $this->mitra->id));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('Detail Informasi Mitra');

        // Kepala can view monitoring penilaian
        $responseNilai = $this->actingAs($this->kepala)->get(route('kinerja-mitra.penilaian.index'));
        $responseNilai->assertStatus(200);

        // Kepala cannot access alokasi index/store (403)
        $responseAlokasi = $this->actingAs($this->kepala)->get(route('kinerja-mitra.alokasi.index'));
        $responseAlokasi->assertStatus(403);

        $responsePostAlokasi = $this->actingAs($this->kepala)->post(route('kinerja-mitra.alokasi.store'), [
            'mitra_id' => $this->mitra->id,
        ]);
        $responsePostAlokasi->assertStatus(403);

        // Kepala cannot rate mitra (POST rejected with 403)
        $alokasi = AlokasiMitra::first();
        $responsePostNilai = $this->actingAs($this->kepala)->post(route('kinerja-mitra.penilaian.store'), [
            'alokasi_mitra_id' => $alokasi->id,
            'bintang'          => 5,
            'komentar'         => 'Komentar kepala yang seharusnya ditolak sistem.',
        ]);
        $responsePostNilai->assertStatus(403);
    }

    // =========================================================================
    // 2. VALIDASI BATAS MAKSIMAL HONOR MITRA
    // =========================================================================

    public function test_alokasi_within_honor_limit_is_accepted(): void
    {
        // Set honor limit: Rp 4.500.000 for 2026-11
        PengaturanHonorMitra::updateOrCreate(
            ['tahun' => 2026, 'bulan' => 11],
            ['maksimal_honor' => 4500000]
        );

        $response = $this->actingAs($this->ketua)->post(route('kinerja-mitra.alokasi.store'), [
            'mitra_id'         => $this->mitra->id,
            'nama_kegiatan'    => 'Survei Pertanian November',
            'kegiatan'         => 'Pencacahan Ubinan Padi',
            'posisi'           => 'Petugas Pencacah Lapangan (PCL)',
            'satuan'           => 'Plot',
            'target_kuantitas' => 5,
            'honor'            => '3.000.000',
            'tanggal_mulai'    => '2026-11-01',
            'tanggal_selesai'  => '2026-11-15',
            'status'           => 'terjadwal',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('alokasi_mitras', [
            'mitra_id'      => $this->mitra->id,
            'nama_kegiatan' => 'Survei Pertanian November',
            'honor'         => 3000000,
            'tahun'         => 2026,
            'bulan'         => 11,
        ]);
    }

    public function test_alokasi_exceeding_honor_limit_is_rejected_with_quota_message(): void
    {
        // Set honor limit: Rp 4.500.000 for 2026-11
        PengaturanHonorMitra::updateOrCreate(
            ['tahun' => 2026, 'bulan' => 11],
            ['maksimal_honor' => 4500000]
        );

        // Pre-allocate Rp 3.000.000
        AlokasiMitra::create([
            'mitra_id'         => $this->mitra->id,
            'tim_id'           => $this->tim->id,
            'created_by'       => $this->ketua->id,
            'nama_kegiatan'    => 'Survei Pertama',
            'kegiatan'         => 'Rincian',
            'posisi'           => 'PCL',
            'satuan'           => 'Dokumen',
            'target_kuantitas' => 1,
            'honor'            => 3000000,
            'tanggal_mulai'    => '2026-11-01',
            'tanggal_selesai'  => '2026-11-10',
            'tahun'            => 2026,
            'bulan'            => 11,
        ]);

        // Attempting to allocate Rp 2.000.000 (3M + 2M = 5M > 4.5M limit)
        $response = $this->actingAs($this->ketua)->from(route('kinerja-mitra.alokasi.index'))->post(route('kinerja-mitra.alokasi.store'), [
            'mitra_id'         => $this->mitra->id,
            'nama_kegiatan'    => 'Survei Kedua yang Melebihi Kuota',
            'kegiatan'         => 'Rincian',
            'posisi'           => 'PCL',
            'satuan'           => 'Dokumen',
            'target_kuantitas' => 1,
            'honor'            => 2000000,
            'tanggal_mulai'    => '2026-11-12',
            'tanggal_selesai'  => '2026-11-20',
        ]);

        $response->assertRedirect(route('kinerja-mitra.alokasi.index'));
        $response->assertSessionHas('error');
        $errorMessage = session('error');
        $this->assertStringContainsString('melebihi batas maksimal', $errorMessage);
        $this->assertStringContainsString('1.500.000', $errorMessage); // remaining quota mentioned

        $this->assertDatabaseMissing('alokasi_mitras', [
            'nama_kegiatan' => 'Survei Kedua yang Melebihi Kuota',
        ]);
    }

    public function test_alokasi_melebihi_batas_bisa_disimpan_dengan_konfirmasi_dan_kelebihan_tidak_dibayar(): void
    {
        // Batas honor: Rp 1.000.000 untuk 2026-11
        PengaturanHonorMitra::updateOrCreate(
            ['tahun' => 2026, 'bulan' => 11, 'jenis_mitra' => PengaturanHonorMitra::JENIS_PENDATAAN],
            ['maksimal_honor' => 1000000]
        );

        // Alokasi pekerjaan 1 sebesar Rp 600.000 (sisa kuota menjadi Rp 400.000)
        AlokasiMitra::create([
            'mitra_id'            => $this->mitra->id,
            'tim_id'              => $this->tim->id,
            'created_by'          => $this->ketua->id,
            'nama_kegiatan'       => 'Pekerjaan Pertama 600rb',
            'kegiatan'            => 'Pencacahan',
            'posisi'              => 'Petugas Pencacah Lapangan (PCL)',
            'jenis_mitra'         => PengaturanHonorMitra::JENIS_PENDATAAN,
            'satuan'              => 'Dokumen',
            'target_kuantitas'    => 1,
            'harga_satuan'        => 600000,
            'honor'               => 600000,
            'honor_dibayar'       => 600000,
            'honor_tidak_dibayar' => 0,
            'melebihi_batas'      => false,
            'tanggal_mulai'       => '2026-11-01',
            'tanggal_selesai'     => '2026-11-10',
            'tahun'               => 2026,
            'bulan'               => 11,
        ]);

        $this->assertEquals(400000, $this->mitra->sisaKuotaHonor(2026, 11));

        // Alokasi pekerjaan 2 sebesar Rp 600.000 dengan konfirmasi pop up disetujui (konfirmasi_melebihi_batas = 1)
        // Melebihi kuota 400rb sebesar 200rb
        $response = $this->actingAs($this->ketua)->post(route('kinerja-mitra.alokasi.store'), [
            'mitra_id'                  => $this->mitra->id,
            'posisi'                    => 'Petugas Pencacah Lapangan (PCL)',
            'jenis_mitra'               => 'pendataan_lapangan',
            'konfirmasi_melebihi_batas' => '1',
            'rows'                      => [
                [
                    'kegiatan'        => 'Pekerjaan Kedua Melebihi Batas',
                    'volume'          => 1,
                    'satuan'          => 'Dokumen',
                    'harga_satuan'    => '600000',
                    'tanggal_mulai'   => '2026-11-15',
                    'tanggal_selesai' => '2026-11-20',
                ]
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Pastikan alokasi tersimpan dengan rincian honor_dibayar = 400.000 dan honor_tidak_dibayar = 200.000
        $this->assertDatabaseHas('alokasi_mitras', [
            'mitra_id'            => $this->mitra->id,
            'kegiatan'            => 'Pekerjaan Kedua Melebihi Batas',
            'honor'               => 600000,
            'honor_dibayar'       => 400000,
            'honor_tidak_dibayar' => 200000,
            'melebihi_batas'      => true,
            'tahun'               => 2026,
            'bulan'               => 11,
        ]);

        // Kuota sisa honor mitra sekarang adalah 0 karena 600rb + 400rb = 1.000.000 (maksimal tercapai)
        $this->assertEquals(0, $this->mitra->sisaKuotaHonor(2026, 11));
    }

    // =========================================================================
    // 3. VALIDASI PENILAIAN MITRA (BINTANG 1–5, KOMENTAR >= 20 KARAKTER)
    // =========================================================================

    public function test_penilaian_requires_bintang_between_1_and_5(): void
    {
        $alokasi = AlokasiMitra::first();

        // Bintang 0 ditolak
        $response0 = $this->actingAs($this->ketua)->post(route('kinerja-mitra.penilaian.store'), [
            'alokasi_mitra_id' => $alokasi->id,
            'bintang'          => 0,
            'komentar'         => 'Komentar sudah cukup panjang lebih dari dua puluh karakter.',
        ]);
        $response0->assertSessionHasErrors('bintang');

        // Bintang 6 ditolak
        $response6 = $this->actingAs($this->ketua)->post(route('kinerja-mitra.penilaian.store'), [
            'alokasi_mitra_id' => $alokasi->id,
            'bintang'          => 6,
            'komentar'         => 'Komentar sudah cukup panjang lebih dari dua puluh karakter.',
        ]);
        $response6->assertSessionHasErrors('bintang');
    }

    public function test_penilaian_with_comment_less_than_20_chars_is_rejected(): void
    {
        $alokasi = AlokasiMitra::first();

        // 12 karakter -> ditolak
        $response = $this->actingAs($this->ketua)->post(route('kinerja-mitra.penilaian.store'), [
            'alokasi_mitra_id' => $alokasi->id,
            'bintang'          => 5,
            'komentar'         => 'Bagus sekali', // 12 karakter
        ]);

        $response->assertSessionHasErrors('komentar');
    }

    public function test_penilaian_valid_is_saved_successfully(): void
    {
        $alokasi = AlokasiMitra::first();
        $komentarValid = 'Petugas bekerja sangat teliti, cekatan, dan seluruh dokumen pencacahan terverifikasi lengkap.';

        $response = $this->actingAs($this->ketua)->post(route('kinerja-mitra.penilaian.store'), [
            'alokasi_mitra_id' => $alokasi->id,
            'bintang'          => 5,
            'komentar'         => $komentarValid,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('penilaian_mitras', [
            'alokasi_mitra_id' => $alokasi->id,
            'penilai_id'       => $this->ketua->id,
            'bintang'          => 5,
            'komentar'         => $komentarValid,
        ]);
    }

    public function test_anggota_tim_can_rate_mitra(): void
    {
        $alokasi = AlokasiMitra::first();
        $komentarValid = 'Koordinasi lapangan dengan anggota tim lain sangat kooperatif dan memuaskan.';

        $response = $this->actingAs($this->anggota)->post(route('kinerja-mitra.penilaian.store'), [
            'alokasi_mitra_id' => $alokasi->id,
            'bintang'          => 4,
            'komentar'         => $komentarValid,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('penilaian_mitras', [
            'alokasi_mitra_id' => $alokasi->id,
            'penilai_id'       => $this->anggota->id,
            'bintang'          => 4,
            'komentar'         => $komentarValid,
        ]);
    }

    public function test_ketua_tim_can_delete_mitra(): void
    {
        $mitraToDelete = Mitra::create([
            'sobat_id'  => '9999-TEST',
            'nama'      => 'Mitra Uji Hapus',
            'provinsi'  => 'NTT',
            'kabupaten' => 'Manggarai',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->ketua)->delete(route('kinerja-mitra.master_mitra.destroy', $mitraToDelete->id));

        $response->assertRedirect(route('kinerja-mitra.master_mitra.index'));
        $this->assertDatabaseMissing('mitras', ['id' => $mitraToDelete->id]);
    }

    public function test_ketua_tim_can_delete_alokasi_mitra(): void
    {
        $alokasiToDelete = AlokasiMitra::create([
            'mitra_id'         => $this->mitra->id,
            'tim_id'           => $this->tim->id,
            'created_by'       => $this->ketua->id,
            'nama_kegiatan'    => 'Survei Uji Hapus Alokasi',
            'kegiatan'         => 'Rincian',
            'posisi'           => 'PCL',
            'satuan'           => 'Dokumen',
            'target_kuantitas' => 1,
            'honor'            => 500000,
            'tanggal_mulai'    => '2026-10-01',
            'tanggal_selesai'  => '2026-10-05',
            'tahun'            => 2026,
            'bulan'            => 10,
        ]);

        $response = $this->actingAs($this->ketua)->delete(route('kinerja-mitra.alokasi.destroy', $alokasiToDelete->id));

        $response->assertRedirect(route('kinerja-mitra.alokasi.index', ['tahun' => 2026, 'bulan' => 10]));
        $this->assertDatabaseMissing('alokasi_mitras', ['id' => $alokasiToDelete->id]);
    }
}
