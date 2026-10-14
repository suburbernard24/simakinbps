<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\FrontendInfografis;
use App\Models\FrontendQuote;
use App\Models\FrontendKegiatan;
use App\Models\FrontendIndikator;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendContentCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->admin = User::where('username', 'admin')->first();
    }

    public function test_admin_can_access_frontend_management_with_all_tabs()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.frontend.index', ['tab' => 'infografis']));
        $response->assertStatus(200);
        $response->assertSee('Kelola Galeri Infografis Beranda');
        $response->assertSee('Kelola Quotes of the Day');
        $response->assertSee('Kelola Berita');
        $response->assertSee('Kelola Indikator Makro Carousel');
    }

    public function test_admin_can_crud_infografis()
    {
        // 1. Store
        $storeRes = $this->actingAs($this->admin)->post(route('admin.frontend.infografis.store'), [
            'judul'       => 'Infografis Uji Coba Baru',
            'kategori'    => 'transportasi',
            'badge_text'  => 'UJI COBA',
            'bg_gradient' => 'from-blue-600 to-indigo-700',
            'icon'        => 'truck',
            'sumber'      => 'BPS Manggarai Test',
            'tahun'       => '2026',
            'link_url'    => 'https://manggaraikab.bps.go.id',
            'urutan'      => 10,
            'is_active'   => 1,
        ]);
        $storeRes->assertRedirect(route('admin.frontend.index', ['tab' => 'infografis']));

        $info = FrontendInfografis::where('judul', 'Infografis Uji Coba Baru')->first();
        $this->assertNotNull($info);
        $this->assertEquals('transportasi', $info->kategori);

        // 2. Update
        $updateRes = $this->actingAs($this->admin)->post(route('admin.frontend.infografis.update', $info->id), [
            'judul'       => 'Infografis Uji Coba Terupdate',
            'kategori'    => 'ekonomi',
            'badge_text'  => 'TERUPDATE',
            'bg_gradient' => 'from-teal-600 to-cyan-700',
            'icon'        => 'chart-pie',
            'urutan'      => 11,
            'is_active'   => 1,
        ]);
        $updateRes->assertRedirect(route('admin.frontend.index', ['tab' => 'infografis']));
        $info->refresh();
        $this->assertEquals('Infografis Uji Coba Terupdate', $info->judul);
        $this->assertEquals('ekonomi', $info->kategori);

        // 3. Destroy
        $delRes = $this->actingAs($this->admin)->delete(route('admin.frontend.infografis.destroy', $info->id));
        $delRes->assertRedirect(route('admin.frontend.index', ['tab' => 'infografis']));
        $this->assertNull(FrontendInfografis::find($info->id));
    }

    public function test_admin_can_crud_quotes()
    {
        // 1. Store
        $storeRes = $this->actingAs($this->admin)->post(route('admin.frontend.quotes.store'), [
            'nama'      => 'Tokoh Inspiratif',
            'jabatan'   => 'Statistisi Ahli',
            'quote'     => 'Kutipan inspiratif untuk pengujian otomatis.',
            'urutan'    => 5,
            'is_active' => 1,
        ]);
        $storeRes->assertRedirect(route('admin.frontend.index', ['tab' => 'quotes']));

        $q = FrontendQuote::where('nama', 'Tokoh Inspiratif')->first();
        $this->assertNotNull($q);

        // 2. Update
        $updateRes = $this->actingAs($this->admin)->post(route('admin.frontend.quotes.update', $q->id), [
            'nama'      => 'Tokoh Inspiratif Update',
            'jabatan'   => 'Kepala Seksi',
            'quote'     => 'Kutipan terupdate.',
            'urutan'    => 6,
            'is_active' => 1,
        ]);
        $updateRes->assertRedirect(route('admin.frontend.index', ['tab' => 'quotes']));
        $q->refresh();
        $this->assertEquals('Tokoh Inspiratif Update', $q->nama);

        // 3. Destroy
        $delRes = $this->actingAs($this->admin)->delete(route('admin.frontend.quotes.destroy', $q->id));
        $delRes->assertRedirect(route('admin.frontend.index', ['tab' => 'quotes']));
        $this->assertNull(FrontendQuote::find($q->id));
    }

    public function test_admin_can_crud_kegiatan()
    {
        // 1. Store
        $storeRes = $this->actingAs($this->admin)->post(route('admin.frontend.kegiatan.store'), [
            'judul'           => 'Kegiatan FGD Uji Coba',
            'kategori'        => 'FGD Khusus',
            'tanggal_display' => '10 Oktober 2026',
            'deskripsi'       => 'Deskripsi lengkap pelaksanaan kegiatan FGD bersama dinas terkait.',
            'badge_color'     => 'orange',
            'icon'            => 'comments',
            'urutan'          => 8,
            'is_active'       => 1,
        ]);
        $storeRes->assertRedirect(route('admin.frontend.index', ['tab' => 'kegiatan']));

        $keg = FrontendKegiatan::where('judul', 'Kegiatan FGD Uji Coba')->first();
        $this->assertNotNull($keg);

        // 2. Update
        $updateRes = $this->actingAs($this->admin)->post(route('admin.frontend.kegiatan.update', $keg->id), [
            'judul'           => 'Kegiatan FGD Uji Coba Revisi',
            'kategori'        => 'FGD Final',
            'tanggal_display' => '12 Oktober 2026',
            'deskripsi'       => 'Deskripsi revisi.',
            'badge_color'     => 'teal',
            'icon'            => 'check-circle',
            'urutan'          => 9,
            'is_active'       => 1,
        ]);
        $updateRes->assertRedirect(route('admin.frontend.index', ['tab' => 'kegiatan']));
        $keg->refresh();
        $this->assertEquals('Kegiatan FGD Uji Coba Revisi', $keg->judul);

        // 3. Destroy
        $delRes = $this->actingAs($this->admin)->delete(route('admin.frontend.kegiatan.destroy', $keg->id));
        $delRes->assertRedirect(route('admin.frontend.index', ['tab' => 'kegiatan']));
        $this->assertNull(FrontendKegiatan::find($keg->id));
    }

    public function test_admin_can_crud_indikator()
    {
        // 1. Store
        $storeRes = $this->actingAs($this->admin)->post(route('admin.frontend.indikator.store'), [
            'judul'      => 'Indikator Uji Coba',
            'sub_judul'  => '(y on y)',
            'nilai'      => '7,25',
            'periode'    => '2026',
            'badge_text' => 'Tabel Dinamis',
            'link_tabel' => '#datapokok',
            'icon'       => 'chart-line',
            'urutan'     => 12,
            'is_active'  => 1,
        ]);
        $storeRes->assertRedirect(route('admin.frontend.index', ['tab' => 'indikator']));

        $ind = FrontendIndikator::where('judul', 'Indikator Uji Coba')->first();
        $this->assertNotNull($ind);

        // 2. Update
        $updateRes = $this->actingAs($this->admin)->post(route('admin.frontend.indikator.update', $ind->id), [
            'judul'      => 'Indikator Uji Coba Terupdate',
            'sub_judul'  => '(q to q)',
            'nilai'      => '7,50',
            'periode'    => 'Triwulan III 2026',
            'badge_text' => 'Rilis Resmi',
            'link_tabel' => 'https://manggaraikab.bps.go.id',
            'icon'       => 'chart-line',
            'urutan'     => 13,
            'is_active'  => 1,
        ]);
        $updateRes->assertRedirect(route('admin.frontend.index', ['tab' => 'indikator']));
        $ind->refresh();
        $this->assertEquals('Indikator Uji Coba Terupdate', $ind->judul);
        $this->assertEquals('7,50', $ind->nilai);

        // 3. Destroy
        $delRes = $this->actingAs($this->admin)->delete(route('admin.frontend.indikator.destroy', $ind->id));
        $delRes->assertRedirect(route('admin.frontend.index', ['tab' => 'indikator']));
        $this->assertNull(FrontendIndikator::find($ind->id));
    }
}
