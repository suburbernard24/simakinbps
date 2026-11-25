<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NatasLandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_branding_and_removes_search(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // 1. Branding NATAS
        $response->assertSee('NATAS BPS MANGGARAI');
        $response->assertSee('Navigasi Akses Terpadu Layanan Statistik BPS Manggarai');
        $response->assertSee('Satu Ruang, Beragam Layanan Statistik.');

        // 2. Logo WebP
        $response->assertSee('LogoFE_natas.webp');

        // 3. Tombol search di hero dihilangkan
        $response->assertDontSee('Cari publikasi, data, atau indikator...');
        $response->assertDontSee('Cari Data');
    }

    public function test_landing_page_does_not_render_ulasan_natas(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Section Ulasan NATAS dihilangkan
        $response->assertDontSee('ulasan-natas');
        $response->assertDontSeeText('Mengenal NATAS BPS Kabupaten Manggarai');
    }

    public function test_landing_page_does_not_render_data_strategis_booklet(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Section Data Strategis booklet dihilangkan
        $response->assertDontSee('booklet-statistik-kabupaten-manggarai-2021');
        $response->assertDontSee('data-purpose="strategic-data"', false);
    }

    public function test_landing_page_renders_4_kotak_layanan_bps(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // 1. Layanan Konsultasi / Permintaan Data
        $response->assertSee('Layanan Konsultasi / Permintaan Data');
        $response->assertSee('bukaModalKonsultasi()', false);
        $response->assertSee('Siaga Layanan 24 Jam / 7 Hari');
        $response->assertSee('Di Luar Jam Layanan (24/7)');

        // 2. Layanan Perpustakaan Digital OPAC
        $response->assertSee('Layanan Perpustakaan Digital');
        $response->assertSee('https://perpustakaan.bps.go.id/opac/');

        // 3. Layanan Rekomendasi Statistik ROMANTIK
        $response->assertSee('Layanan Rekomendasi Statistik');
        $response->assertSee('https://romantik.web.bps.go.id/');

        // 4. Layanan Pembelian Data Mikro SILASTIK
        $response->assertSee('Pembelian Data Mikro / Peta');
        $response->assertSee('https://silastik.bps.go.id/v3/index.php/site/login/');
    }

    public function test_landing_page_renders_buku_tamu_digilib(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Buku Tamu Digital PST BPS
        $response->assertSee('Buku Tamu Digital Pelayanan Statistik Terpadu');
        $response->assertSee('https://perpustakaan.bps.go.id/digilib/guestbook');
        $response->assertSee('bukaModalBukuTamu()', false);
    }

    public function test_landing_page_renders_infografis_6_kotak_and_quotes(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Infografis 6 Kotak
        $response->assertSee('Infografis 6 Data Utama &amp; Terbaru', false);

        // Kutipan / Quote
        $response->assertSee('Kutipan Inspiratif &amp; Integritas Data', false);
    }

    public function test_landing_page_does_not_render_kegiatan_section(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Section Kegiatan tidak ditampilkan
        $response->assertDontSee('Kegiatan Terkini BPS Manggarai');
        $response->assertDontSee('recent-activities');
    }

    public function test_landing_page_renders_indikator_carousel_at_top_with_arrow_navigation(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Indikator carousel at top with slider navigation
        $response->assertSee('Indikator Terkini Kabupaten Manggarai');
        $response->assertSee('scrollIndicatorCarousel(1)', false);
        $response->assertSee('scrollIndicatorCarousel(-1)', false);
        $response->assertSee('indicator-carousel');
    }
}
