<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlokasiController;
use App\Http\Controllers\AlokasiMitraController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CkpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HonorMitraController;
use App\Http\Controllers\KinerjaController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PenilaianMitraController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratMasterController;
use Illuminate\Support\Facades\Route;

// Redirect root: landing page for guests, dashboard for authenticated
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Central Dashboard Route (Multi-role router)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =========================================================================
    // PROFILE & SETTINGS (Semua Role: Admin, Kepala BPS, Ketua Tim, Pegawai)
    // =========================================================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::match(['post', 'put'], '/update', [ProfileController::class, 'update'])->name('update');
        Route::match(['post', 'put'], '/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/foto', [ProfileController::class, 'uploadFoto'])->name('foto');
        Route::delete('/foto', [ProfileController::class, 'hapusFoto'])->name('foto.destroy');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    });

    // =========================================================================
    // B. ADMINISTRATOR ROUTES (RBAC: Strictly role:admin)
    // =========================================================================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Manage Pegawai
        Route::get('/pegawai', [AdminController::class, 'pegawaiIndex'])->name('pegawai.index');
        Route::get('/pegawai/tambah', [AdminController::class, 'pegawaiCreate'])->name('pegawai.create');
        Route::post('/pegawai', [AdminController::class, 'pegawaiStore'])->name('pegawai.store');
        Route::get('/pegawai/template-excel', [AdminController::class, 'pegawaiDownloadTemplate'])->name('pegawai.template');
        Route::post('/pegawai/import-excel', [AdminController::class, 'pegawaiImportExcel'])->name('pegawai.import');
        Route::get('/pegawai/{id}/edit', [AdminController::class, 'pegawaiEdit'])->name('pegawai.edit');
        Route::post('/pegawai/{id}/update', [AdminController::class, 'pegawaiUpdate'])->name('pegawai.update');
        Route::post('/pegawai/{id}/toggle-status', [AdminController::class, 'pegawaiToggleStatus'])->name('pegawai.toggle');
        Route::delete('/pegawai/{id}', [AdminController::class, 'pegawaiDestroy'])->name('pegawai.destroy');

        // Manage Tim
        Route::get('/tim', [AdminController::class, 'timIndex'])->name('tim.index');
        Route::post('/tim', [AdminController::class, 'timStore'])->name('tim.store');
        Route::post('/tim/{id}/update', [AdminController::class, 'timUpdate'])->name('tim.update');
        Route::delete('/tim/{id}', [AdminController::class, 'timDestroy'])->name('tim.destroy');

        // Manage Tampilan Frontend
        Route::get('/frontend', [AdminController::class, 'frontendIndex'])->name('frontend.index');
        Route::post('/frontend', [AdminController::class, 'frontendUpdate'])->name('frontend.update');
        Route::post('/frontend/reset-image', [AdminController::class, 'frontendResetImage'])->name('frontend.reset_image');

        // Galeri Foto Frontend
        Route::post('/frontend/galeri', [AdminController::class, 'galeriStore'])->name('frontend.galeri.store');
        Route::post('/frontend/galeri/{id}/update', [AdminController::class, 'galeriUpdate'])->name('frontend.galeri.update');
        Route::delete('/frontend/galeri/{id}', [AdminController::class, 'galeriDestroy'])->name('frontend.galeri.destroy');
        Route::post('/frontend/galeri/{id}/toggle', [AdminController::class, 'galeriToggle'])->name('frontend.galeri.toggle');

        // Input Hari Kerja Aktif Bulanan
        Route::get('/hari-kerja', [AdminController::class, 'hariKerjaIndex'])->name('hari_kerja.index');
        Route::post('/hari-kerja', [AdminController::class, 'hariKerjaStore'])->name('hari_kerja.store');

        // Manage Frontend User Publik
        Route::get('/user-publik', [AdminController::class, 'userPublikIndex'])->name('user_publik.index');
        Route::post('/user-publik', [AdminController::class, 'userPublikStore'])->name('user_publik.store');
        Route::delete('/user-publik/{id}', [AdminController::class, 'userPublikDestroy'])->name('user_publik.destroy');

        // Pengaturan Honor Mitra
        Route::get('/honor-mitra', [HonorMitraController::class, 'index'])->name('honor_mitra.index');
        Route::post('/honor-mitra', [HonorMitraController::class, 'store'])->name('honor_mitra.store');
    });

    // =========================================================================
    // H & I & J & P. ALOKASI PEKERJAAN (RBAC: Non-admin only)
    // =========================================================================
    Route::prefix('alokasi')->name('alokasi.')->middleware('role:non-admin')->group(function () {
        
        // Input Alokasi: HANYA KETUA TIM
        Route::middleware('role:ketua_tim')->group(function () {
            Route::get('/input', [AlokasiController::class, 'input'])->name('input');
            Route::post('/input', [AlokasiController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AlokasiController::class, 'edit'])->name('edit');
            Route::post('/{id}/update', [AlokasiController::class, 'update'])->name('update');
            Route::delete('/{id}', [AlokasiController::class, 'destroy'])->name('hapus');
            // Import Excel (banyak anggota)
            Route::get('/template-excel', [AlokasiController::class, 'downloadTemplate'])->name('template');
            Route::post('/import-excel', [AlokasiController::class, 'importExcel'])->name('import');
        });

        // Read & Monitoring (Ketua Tim, Kepala BPS, Pegawai)
        Route::get('/tim', [AlokasiController::class, 'tim'])->name('tim');
        Route::get('/pegawai', [AlokasiController::class, 'pegawai'])->name('pegawai');
        Route::get('/kalender', [AlokasiController::class, 'kalender'])->name('kalender');
    });

    // =========================================================================
    // M. GRAFIK PERBANDINGAN SKOR KINERJA
    // =========================================================================
    Route::prefix('kinerja')->name('kinerja.')->middleware('role:non-admin')->group(function () {
        Route::get('/grafik', [KinerjaController::class, 'grafik'])->name('grafik');
    });

    // =========================================================================
    // N & Q. CKP (CKP Saya, Realisasi, Penilaian, Laporan & Cetak)
    // =========================================================================
    Route::prefix('ckp')->name('ckp.')->middleware('role:non-admin')->group(function () {
        // CKP Saya
        Route::get('/', [CkpController::class, 'index'])->name('index');
        Route::post('/edit-realisasi/{id}', [CkpController::class, 'ckp_edit'])->name('edit_realisasi');

        // Pekerjaan Tambahan
        Route::get('/tambah-tambahan', [CkpController::class, 'ckp_tambah'])->name('tambah');
        Route::post('/aksi-tambahan', [CkpController::class, 'ckp_aksi_tambahan'])->name('aksi_tambahan');
        Route::get('/tambahan/{id}/edit', [CkpController::class, 'ckp_edit_tambahan'])->name('edit_tambahan');
        Route::post('/tambahan/{id}/update', [CkpController::class, 'ckp_update'])->name('update_tambahan');
        Route::delete('/tambahan/{id}', [CkpController::class, 'ckp_hapus_tambahan'])->name('hapus_tambahan');

        // Penilaian CKP (Khusus Penilai: Ketua Tim & Kepala BPS)
        Route::middleware('role:penilai')->group(function () {
            Route::get('/penilaian', [CkpController::class, 'ckp_nilai'])->name('nilai');
            Route::post('/penilaian/{id}/simpan', [CkpController::class, 'ckp_nilai_edit'])->name('nilai_edit');
        });

        // Laporan CKP Bulanan & Export
        Route::get('/laporan', [CkpController::class, 'laporan'])->name('laporan');
        Route::get('/export-excel', [CkpController::class, 'exportExcel'])->name('export_excel');
        Route::get('/export-pdf', [CkpController::class, 'exportPdf'])->name('export_pdf');

        // Dokumen Cetak Resmi BPS
        Route::get('/cetak/realisasi/{tahun}/{bulan}', [CkpController::class, 'ckp_cetak'])->name('cetak');
        Route::get('/cetak/target/{tahun}/{bulan}', [CkpController::class, 'ckp_t_cetak'])->name('t_cetak');

        // Monitoring Pegawai
        Route::get('/monitoring', [CkpController::class, 'ckp_user'])->name('monitoring');
        Route::get('/monitoring/{id_user}', [CkpController::class, 'ckp_user_detail'])->name('user_detail');
        Route::get('/{id}', [CkpController::class, 'show'])->name('show');
    });

    // =========================================================================
    // E-SURAT BPS (Daftar Surat, Buat Surat, Cetak, Import Excel, Master Data)
    // Admin & Kepala BPS: melihat/memantau & menyetujui | Ketua Tim & Pegawai: membuat surat
    // =========================================================================
    Route::prefix('surat')->name('surat.')->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('surat.index');
        })->name('dashboard');

        Route::get('/daftar', [SuratController::class, 'index'])->name('index');
        Route::get('/buat', [SuratController::class, 'create'])->name('create');
        Route::post('/simpan', [SuratController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [SuratController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [SuratController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [SuratController::class, 'update'])->name('update');
        Route::delete('/hapus/{id}', [SuratController::class, 'destroy'])->name('destroy');
        Route::post('/ajukan/{id}', [SuratController::class, 'submit'])->name('submit');
        Route::get('/cetak/{id}', [SuratController::class, 'cetak'])->name('cetak');
        Route::get('/lampiran/download/{id}', [SuratController::class, 'downloadLampiran'])->name('lampiran.download');
        Route::get('/dokumen/download/{id}', [SuratController::class, 'downloadDokumenSurat'])->name('dokumen.download');
        Route::post('/import-excel', [SuratController::class, 'importExcel'])->name('import_excel');
        Route::get('/template-excel', [SuratController::class, 'downloadTemplateExcel'])->name('template_excel');

        // Master Data Surat (Admin Only)
        Route::prefix('master')->name('master.')->middleware('role:admin')->group(function () {
            Route::get('/unit-kerja', [SuratMasterController::class, 'unitKerjaIndex'])->name('unit_kerja.index');
            Route::post('/unit-kerja', [SuratMasterController::class, 'unitKerjaStore'])->name('unit_kerja.store');
            Route::put('/unit-kerja/{id}', [SuratMasterController::class, 'unitKerjaUpdate'])->name('unit_kerja.update');
            Route::delete('/unit-kerja/{id}', [SuratMasterController::class, 'unitKerjaDestroy'])->name('unit_kerja.destroy');

            Route::get('/jenis-surat', [SuratMasterController::class, 'jenisSuratIndex'])->name('jenis_surat.index');
            Route::post('/jenis-surat', [SuratMasterController::class, 'jenisSuratStore'])->name('jenis_surat.store');
            Route::put('/jenis-surat/{id}', [SuratMasterController::class, 'jenisSuratUpdate'])->name('jenis_surat.update');

            Route::get('/template', [SuratMasterController::class, 'templateIndex'])->name('template.index');
            Route::post('/template', [SuratMasterController::class, 'templateStore'])->name('template.store');
            Route::put('/template/{id}', [SuratMasterController::class, 'templateUpdate'])->name('template.update');

            Route::get('/penomoran', [SuratMasterController::class, 'penomoranIndex'])->name('penomoran.index');
        });
    });

    // =========================================================================
    // KINERJA MITRA (Sobat BPS: Detail Informasi Mitra, Alokasi & Penilaian)
    // =========================================================================
    Route::prefix('kinerja-mitra')->name('kinerja-mitra.')->middleware('role:non-admin')->group(function () {
        // Katalog & Detail Informasi Mitra (Ketua Tim, Anggota Tim, Kepala BPS)
        Route::get('/mitra', [MitraController::class, 'index'])->name('mitra.index');
        Route::get('/mitra/{id}', [MitraController::class, 'show'])->name('mitra.show');

        // Master Data Mitra: HANYA KETUA TIM (Input, Edit, Hapus, Import)
        Route::prefix('master-mitra')->name('master_mitra.')->middleware('role:ketua_tim')->group(function () {
            Route::get('/', [MitraController::class, 'masterIndex'])->name('index');
            Route::post('/', [MitraController::class, 'masterStore'])->name('store');
            Route::get('/template-excel', [MitraController::class, 'downloadTemplate'])->name('template');
            Route::post('/import-excel', [MitraController::class, 'importExcel'])->name('import');
            Route::post('/{id}/update', [MitraController::class, 'masterUpdate'])->name('update');
            Route::post('/{id}/toggle-status', [MitraController::class, 'masterToggleStatus'])->name('toggle');
            Route::delete('/{id}', [MitraController::class, 'masterDestroy'])->name('destroy');
        });

        // Alokasi Pekerjaan Mitra: HANYA KETUA TIM
        Route::prefix('alokasi')->name('alokasi.')->middleware('role:ketua_tim')->group(function () {
            Route::get('/', [AlokasiMitraController::class, 'index'])->name('index');
            Route::post('/', [AlokasiMitraController::class, 'store'])->name('store');
            Route::get('/cek-kuota', [AlokasiMitraController::class, 'cekKuota'])->name('cek-kuota');
            Route::get('/template-excel', [AlokasiMitraController::class, 'downloadTemplate'])->name('template');
            Route::post('/import-excel', [AlokasiMitraController::class, 'importExcel'])->name('import');
            Route::get('/{id}/edit', [AlokasiMitraController::class, 'edit'])->name('edit');
            Route::post('/{id}/update', [AlokasiMitraController::class, 'update'])->name('update');
            Route::delete('/{id}', [AlokasiMitraController::class, 'destroy'])->name('destroy');
        });

        // Penilaian Kinerja Mitra
        Route::prefix('penilaian')->name('penilaian.')->group(function () {
            // Read & Monitoring (Seluruh Non-Admin, termasuk Kepala BPS sebagai viewer)
            Route::get('/', [PenilaianMitraController::class, 'index'])->name('index');
            // Simpan Penilaian: HANYA PENILAI MITRA (Ketua Tim & Anggota Tim)
            Route::post('/', [PenilaianMitraController::class, 'store'])->middleware('role:penilai_mitra')->name('store');
        });
    });

});
