<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Surat;
use App\Models\SuratDetail;
use App\Models\SuratLampiran;
use App\Models\SuratPegawai;
use App\Models\SuratPenerima;
use App\Models\TemplateSurat;
use App\Models\UnitKerja;
use App\Models\User;
use App\Services\SuratExcelService;
use App\Services\SuratService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    /**
     * Dashboard e-Surat BPS (PRD 10.2)
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Base query depending on role
        $query = Surat::query();
        if (!$user->isApprover()) {
            $query->where('user_id', $user->id);
        }

        $totalSurat = (clone $query)->count();
        $totalDraft = (clone $query)->where('status', 'draft')->count();
        $totalDiajukan = 0;
        $totalMenungguPersetujuan = 0;
        $totalSelesai = (clone $query)->whereIn('status', ['selesai', 'diarsipkan'])->count();
        $totalDikembalikan = 0;

        // Tidak ada antrean persetujuan pimpinan
        $countVerifikasiWaiting = 0;
        $countApprovalWaiting = 0;

        // Recent letters
        $recentSurats = (clone $query)->with(['jenisSurat', 'pemohon', 'unitKerja'])
            ->latest()
            ->take(6)
            ->get();

        // Stats by Jenis Surat
        $statsByJenis = JenisSurat::withCount(['surats' => function ($q) use ($user) {
            if (!$user->isApprover()) {
                $q->where('user_id', $user->id);
            }
        }])->get();

        return view('surat.dashboard', compact(
            'totalSurat',
            'totalDraft',
            'totalDiajukan',
            'totalMenungguPersetujuan',
            'totalSelesai',
            'totalDikembalikan',
            'countVerifikasiWaiting',
            'countApprovalWaiting',
            'recentSurats',
            'statsByJenis'
        ));
    }

    /**
     * Daftar Surat / Surat Saya (PRD 21.1 & 21.2)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $status = $request->query('status');
        $jenis = $request->query('jenis'); // kode jenis or id
        $q = $request->query('q');
        $scope = $request->query('scope', 'all'); // 'mine' or 'all'

        $query = Surat::with(['jenisSurat', 'templateSurat', 'pemohon', 'unitKerja', 'pegawais.pegawai']);

        // Scope filter: Hanya jika secara eksplisit meminta 'mine'
        if ($scope === 'mine') {
            $query->where('user_id', $user->id);
        }

        // Status filter
        if ($status && $status !== 'all') {
            if ($status === 'diajukan') {
                $query->whereIn('status', ['diajukan', 'verifikasi']);
            } else {
                $query->where('status', $status);
            }
        }

        // Jenis surat filter
        if ($jenis) {
            $query->whereHas('jenisSurat', function ($sq) use ($jenis) {
                $sq->where('kode', $jenis)->orWhere('id', $jenis);
            });
        }

        // Search query
        if ($q) {
            $query->where(function ($sq) use ($q) {
                $sq->where('nomor_surat', 'like', "%{$q}%")
                   ->orWhere('perihal', 'like', "%{$q}%")
                   ->orWhereHas('pemohon', function ($uq) use ($q) {
                       $uq->where('name', 'like', "%{$q}%");
                   });
            });
        }

        $surats = $query->latest()->paginate(12)->withQueryString();
        $jenisSurats = JenisSurat::where('aktif', true)->get();

        return view('surat.index', compact('surats', 'jenisSurats', 'status', 'jenis', 'q', 'scope'));
    }

    /**
     * Form Buat Surat (PRD 10.7 & 11-14)
     */
    public function create(Request $request)
    {
        if (!Auth::user()->canCreateSurat()) {
            return redirect()->route('surat.index')->with('error', 'Admin dan Kepala Kantor tidak perlu membuat surat.');
        }

        $selectedJenisId = $request->query('jenis_id');
        $jenisSurats = JenisSurat::where('aktif', true)->get();
        $templates = TemplateSurat::where('aktif', true)->get();
        $unitKerjas = UnitKerja::where('status', true)->get();
        $pegawais = User::where('is_active', true)->where('role', '!=', 'admin')->orderBy('name')->get();

        return view('surat.create', compact('jenisSurats', 'templates', 'unitKerjas', 'pegawais', 'selectedJenisId'));
    }

    /**
     * Simpan Surat (Draft atau Diajukan)
     */
    public function store(Request $request)
    {
        if (!Auth::user()->canCreateSurat()) {
            return redirect()->route('surat.index')->with('error', 'Admin dan Kepala Kantor tidak perlu membuat surat.');
        }

        $request->validate([
            'sumber_surat'        => 'nullable|in:sistem,luar',
            'jenis_surat_id'      => 'required|exists:jenis_surats,id',
            'perihal'             => 'required|string|max:255',
            'tanggal_surat'       => 'required|date',
            'unit_kerja_id'       => 'nullable|exists:unit_kerjas,id',
            'template_surat_id'   => 'nullable|exists:template_surats,id',
            'kode_klasifikasi'    => 'nullable|string|max:50',
            'file_dokumen_surat'  => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:20480',
            'nomor_surat_manual'  => 'nullable|string|max:100',
            'lampiran.*'          => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:10240',
        ]);

        $sumberSurat = $request->input('sumber_surat', 'sistem');

        if ($sumberSurat === 'luar' && !$request->hasFile('file_dokumen_surat')) {
            return back()->withInput()->with('error', 'Silakan unggah berkas naskah surat (PDF/Word/Gambar) yang dibuat di luar sistem.');
        }

        $action = $request->input('action', 'ajukan'); // Default 'ajukan' agar langsung terbit jika user submit
        $status = ($action === 'draft') ? 'draft' : 'selesai';

        $jenisSurat = JenisSurat::findOrFail($request->jenis_surat_id);

        DB::beginTransaction();
        try {
            $fileSuratPath = null;
            $namaFileAsli = null;

            if ($request->hasFile('file_dokumen_surat')) {
                $uploadedDoc = $request->file('file_dokumen_surat');
                $fileSuratPath = $uploadedDoc->store('dokumen_surat', 'public');
                $namaFileAsli = $uploadedDoc->getClientOriginalName();
            }

            $nomorManual = ($sumberSurat === 'luar' && $request->filled('nomor_surat_manual')) 
                ? trim($request->nomor_surat_manual) 
                : null;

            $surat = Surat::create([
                'jenis_surat_id'   => $jenisSurat->id,
                'template_surat_id'=> $request->template_surat_id,
                'user_id'          => Auth::id(),
                'unit_kerja_id'    => $request->unit_kerja_id ?? Auth::user()->unit_kerja_id,
                'tanggal_pengajuan'=> Carbon::now(),
                'tanggal_surat'    => $request->tanggal_surat,
                'perihal'          => $request->perihal,
                'fungsi'           => $request->fungsi ?? UnitKerja::find($request->unit_kerja_id)?->nama,
                'status'           => $status,
                'sumber_surat'     => $sumberSurat,
                'file_surat'       => $fileSuratPath,
                'nama_file_asli'   => $namaFileAsli,
                'nomor_surat'      => $nomorManual,
                'kode_klasifikasi' => $request->kode_klasifikasi ?: ($jenisSurat->kode_klasifikasi_default ?? 'VS.330'),
                'kode_satker'      => '53131',
            ]);

            // Simpan Surat Detail
            SuratDetail::create([
                'surat_id'         => $surat->id,
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'tanggal_selesai'  => $request->tanggal_selesai,
                'tempat_kegiatan'  => $request->tempat_kegiatan,
                'tujuan_kegiatan'  => $request->tujuan_kegiatan,
                'uraian_kegiatan'  => $request->uraian_kegiatan ?: ($sumberSurat === 'luar' ? 'Naskah dinas diunggah dari luar sistem: ' . ($namaFileAsli ?? $request->perihal) : '-'),
                'nomor_sk'         => $request->nomor_sk,
                'tanggal_sk'       => $request->tanggal_sk,
                'pejabat_penetap'  => $request->pejabat_penetap ?? 'Kepala Badan Pusat Statistik Kabupaten Manggarai',
                'dasar_penetapan'  => $request->dasar_penetapan,
                'pihak_pertama'    => $request->pihak_pertama,
                'pihak_kedua'      => $request->pihak_kedua,
                'jabatan_pihak_pertama' => $request->jabatan_pihak_pertama,
                'jabatan_pihak_kedua'   => $request->jabatan_pihak_kedua,
                'keterangan'       => $request->keterangan,
            ]);

            // Simpan Pegawai yang Ditugaskan (Multiple Pegawai - PRD 9)
            if ($request->has('pegawai_ids') && is_array($request->pegawai_ids)) {
                foreach ($request->pegawai_ids as $index => $pegawaiId) {
                    if ($pegawaiId) {
                        SuratPegawai::create([
                            'surat_id'   => $surat->id,
                            'pegawai_id' => $pegawaiId,
                            'peran'      => $request->pegawai_perans[$index] ?? 'Anggota',
                            'urutan'     => $index + 1,
                            'keterangan' => $request->pegawai_keterangans[$index] ?? null,
                        ]);
                    }
                }
            }

            // Simpan Penerima Surat (Jika Surat Keluar)
            if ($request->filled('nama_penerima')) {
                SuratPenerima::create([
                    'surat_id'       => $surat->id,
                    'nama_penerima'  => $request->nama_penerima,
                    'instansi'       => $request->instansi_penerima,
                    'alamat'         => $request->alamat_penerima,
                    'email'          => $request->email_penerima,
                    'jenis_penerima' => $request->jenis_penerima ?? 'Eksternal',
                ]);
            }

            // Upload Lampiran
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $path = $file->store('lampiran_surat', 'public');
                    SuratLampiran::create([
                        'surat_id'   => $surat->id,
                        'nama_file'  => $file->getClientOriginalName(),
                        'file_path'  => $path,
                        'jenis_file' => $file->getClientOriginalExtension(),
                        'ukuran'     => round($file->getSize() / 1024),
                        'uploaded_by'=> Auth::id(),
                    ]);
                }
            }

            // Log Riwayat Status & Penerbitan Nomor Langsung jika selesai
            if ($status === 'selesai') {
                if (empty($surat->nomor_surat)) {
                    $nomorSurat = SuratService::generateNomorSurat($surat);
                    $surat->refresh(); // refresh agar nomor_surat terbaru terbaca
                } else {
                    $nomorSurat = $surat->nomor_surat;
                }
                SuratService::logStatus(
                    $surat,
                    'selesai',
                    Auth::id(),
                    "Surat langsung diterbitkan dengan nomor resmi: {$nomorSurat}"
                );
            } else {
                SuratService::logStatus(
                    $surat,
                    'draft',
                    Auth::id(),
                    'Draft surat disimpan.'
                );
            }

            DB::commit();

            $msg = ($status === 'selesai')
                ? "Surat berhasil diterbitkan dengan nomor resmi: {$surat->nomor_surat}!"
                : 'Draft surat berhasil disimpan.';

            return redirect()->route('surat.show', $surat->id)->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Detail & Preview Surat (PRD 10.7 & 10.11)
     */
    public function show($id)
    {
        $surat = Surat::with([
            'jenisSurat',
            'templateSurat',
            'pemohon',
            'unitKerja',
            'detail',
            'pegawais.pegawai',
            'penerimas',
            'lampirans.uploader',
            'verifikasis.verifikator',
            'statusHistories.user',
            'nomorSuratRecord',
        ])->findOrFail($id);

        // Jika nomor surat belum ada pada surat aktif / diajukan, terbitkan otomatis
        if (empty($surat->nomor_surat) && !$surat->isDraft()) {
            SuratService::generateNomorSurat($surat);
            $surat->refresh();
        }

        $user = Auth::user();

        // Konten surat yang telah dirender variabelnya
        $renderedContent = SuratService::renderContent($surat);

        return view('surat.show', compact('surat', 'renderedContent'));
    }

    /**
     * Edit Surat (Hanya jika Draft atau Dikembalikan - PRD 19)
     */
    public function edit($id)
    {
        $surat = Surat::with(['detail', 'pegawais', 'penerimas', 'lampirans'])->findOrFail($id);
        $user = Auth::user();

        // Proteksi: Hanya pemohon atau admin yang boleh mengedit jika status draft atau dikembalikan
        if (!$surat->canEdit() && !$user->isAdmin()) {
            return redirect()->route('surat.show', $surat->id)->with('error', 'Surat yang sedang diproses atau sudah disetujui tidak dapat diedit.');
        }

        if ($surat->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Anda tidak berwenang mengedit surat ini.');
        }

        $jenisSurats = JenisSurat::where('aktif', true)->get();
        $templates = TemplateSurat::where('aktif', true)->get();
        $unitKerjas = UnitKerja::where('status', true)->get();
        $pegawais = User::where('is_active', true)->where('role', '!=', 'admin')->orderBy('name')->get();

        return view('surat.edit', compact('surat', 'jenisSurats', 'templates', 'unitKerjas', 'pegawais'));
    }

    /**
     * Update Surat
     */
    public function update(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);
        $user = Auth::user();

        if (!$surat->canEdit() && !$user->isAdmin()) {
            return redirect()->route('surat.show', $surat->id)->with('error', 'Surat ini tidak dapat diedit.');
        }

        $request->validate([
            'sumber_surat'        => 'nullable|in:sistem,luar',
            'perihal'             => 'required|string|max:255',
            'tanggal_surat'       => 'required|date',
            'unit_kerja_id'       => 'nullable|exists:unit_kerjas,id',
            'template_surat_id'   => 'nullable|exists:template_surats,id',
            'file_dokumen_surat'  => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:20480',
            'nomor_surat_manual'  => 'nullable|string|max:100',
            'lampiran.*'          => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:10240',
        ]);

        $action = $request->input('action', 'draft');
        $status = ($action === 'ajukan') ? 'selesai' : 'draft';

        DB::beginTransaction();
        try {
            $updateData = [
                'perihal'          => $request->perihal,
                'tanggal_surat'    => $request->tanggal_surat,
                'unit_kerja_id'    => $request->unit_kerja_id,
                'template_surat_id'=> $request->template_surat_id,
                'kode_klasifikasi' => $request->kode_klasifikasi ?? $surat->kode_klasifikasi,
                'status'           => $status,
            ];

            if ($request->has('sumber_surat')) {
                $updateData['sumber_surat'] = $request->sumber_surat;
            }

            if ($request->filled('nomor_surat_manual')) {
                $updateData['nomor_surat'] = trim($request->nomor_surat_manual);
            }

            if ($request->hasFile('file_dokumen_surat')) {
                $uploadedDoc = $request->file('file_dokumen_surat');
                $updateData['file_surat'] = $uploadedDoc->store('dokumen_surat', 'public');
                $updateData['nama_file_asli'] = $uploadedDoc->getClientOriginalName();
            }

            $surat->update($updateData);

            // Update Detail
            if ($surat->detail) {
                $surat->detail->update([
                    'tanggal_kegiatan' => $request->tanggal_kegiatan,
                    'tanggal_selesai'  => $request->tanggal_selesai,
                    'tempat_kegiatan'  => $request->tempat_kegiatan,
                    'tujuan_kegiatan'  => $request->tujuan_kegiatan,
                    'uraian_kegiatan'  => $request->uraian_kegiatan ?: ($surat->isDibuatDiLuar() ? 'Naskah dinas diunggah dari luar sistem' : $surat->detail->uraian_kegiatan),
                    'pejabat_penetap'  => $request->pejabat_penetap,
                    'dasar_penetapan'  => $request->dasar_penetapan,
                    'pihak_pertama'    => $request->pihak_pertama,
                    'pihak_kedua'      => $request->pihak_kedua,
                    'jabatan_pihak_pertama' => $request->jabatan_pihak_pertama,
                    'jabatan_pihak_kedua'   => $request->jabatan_pihak_kedua,
                    'keterangan'       => $request->keterangan,
                ]);
            }

            // Sync Pegawais
            if ($request->has('pegawai_ids')) {
                $surat->pegawais()->delete();
                foreach ($request->pegawai_ids as $index => $pegawaiId) {
                    if ($pegawaiId) {
                        SuratPegawai::create([
                            'surat_id'   => $surat->id,
                            'pegawai_id' => $pegawaiId,
                            'peran'      => $request->pegawai_perans[$index] ?? 'Anggota',
                            'urutan'     => $index + 1,
                            'keterangan' => $request->pegawai_keterangans[$index] ?? null,
                        ]);
                    }
                }
            }

            // Upload Lampiran baru jika ada
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $path = $file->store('lampiran_surat', 'public');
                    SuratLampiran::create([
                        'surat_id'   => $surat->id,
                        'nama_file'  => $file->getClientOriginalName(),
                        'file_path'  => $path,
                        'jenis_file' => $file->getClientOriginalExtension(),
                        'ukuran'     => round($file->getSize() / 1024),
                        'uploaded_by'=> Auth::id(),
                    ]);
                }
            }

            // Log status & generate nomor jika terbit
            if ($status === 'selesai') {
                if (!$surat->nomor_surat) {
                    $nomorSurat = SuratService::generateNomorSurat($surat);
                    $surat->refresh(); // refresh agar nomor_surat terbaru terbaca
                } else {
                    $nomorSurat = $surat->nomor_surat;
                }
                SuratService::logStatus(
                    $surat,
                    'selesai',
                    Auth::id(),
                    "Surat berhasil diterbitkan langsung dengan nomor resmi: {$nomorSurat}"
                );
                $msg = "Surat berhasil diterbitkan dengan nomor resmi: {$nomorSurat}!";
            } else {
                SuratService::logStatus(
                    $surat,
                    'draft',
                    Auth::id(),
                    'Perubahan draft surat disimpan.'
                );
                $msg = 'Surat berhasil diperbarui.';
            }

            DB::commit();

            return redirect()->route('surat.show', $surat->id)->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Terbitkan Surat Langsung (Dari Halaman Detail)
     */
    public function submit($id)
    {
        $surat = Surat::findOrFail($id);

        if ($surat->nomor_surat && $surat->isSelesai()) {
            return back()->with('error', 'Surat ini sudah memiliki nomor resmi dan diterbitkan.');
        }

        DB::beginTransaction();
        try {
            $surat->update([
                'tanggal_pengajuan' => Carbon::now(),
                'status' => 'selesai',
            ]);

            if (empty($surat->nomor_surat)) {
                $nomorSurat = SuratService::generateNomorSurat($surat);
                $surat->refresh(); // refresh agar nomor_surat terbaru terbaca
            } else {
                $nomorSurat = $surat->nomor_surat;
            }
            SuratService::logStatus($surat, 'selesai', Auth::id(), "Surat berhasil diterbitkan langsung dengan nomor resmi: {$nomorSurat}");

            DB::commit();

            return redirect()->route('surat.show', $surat->id)->with('success', "Surat berhasil diterbitkan dengan nomor resmi: {$nomorSurat}!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menerbitkan surat: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Surat Draft
     */
    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);

        if (!$surat->isDraft() && !Auth::user()->isAdmin()) {
            return back()->with('error', 'Hanya surat draft yang dapat dihapus.');
        }

        $surat->delete();
        return redirect()->route('surat.index')->with('success', 'Surat draft berhasil dihapus.');
    }

    /**
     * Dokumen Cetak / Preview Resmi Standar BPS (PRD 10.11 & 25)
     */
    public function cetak(Request $request, $id)
    {
        $surat = Surat::with([
            'jenisSurat',
            'templateSurat',
            'pemohon',
            'unitKerja',
            'detail',
            'pegawais.pegawai',
            'penerimas',
            'nomorSuratRecord',
        ])->findOrFail($id);

        // Pastikan nomor surat selalu ada saat halaman cetak dibuka
        if (empty($surat->nomor_surat)) {
            SuratService::generateNomorSurat($surat);
            $surat->refresh();
        }

        // Sinkronisasi nomor surat resmi ke berkas Word (.docx) jika naskah dibuat di luar sistem
        if ($surat->isDibuatDiLuar() && $surat->file_surat) {
            SuratService::syncDocxNomorSurat($surat);
        }

        // Jika surat diunggah dalam format PDF asli, tampilkan langsung berkas PDF untuk dicetak
        if ($surat->isDibuatDiLuar() && $surat->file_surat) {
            $path = storage_path('app/public/' . $surat->file_surat);
            if (file_exists($path)) {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if ($ext === 'pdf') {
                    return response()->file($path, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . ($surat->nama_file_asli ?: 'naskah_surat.pdf') . '"'
                    ]);
                }
            }
        }

        $kepala = User::getKepala() ?? User::where('role', 'kepala_bps')->first();
        $renderedContent = SuratService::renderContent($surat);

        return view('surat.cetak', compact('surat', 'kepala', 'renderedContent'));
    }

    /**
     * Download Dokumen Naskah Surat Asli (Untuk surat yang diupload dari luar)
     */
    public function downloadDokumenSurat($id)
    {
        $surat = Surat::findOrFail($id);
        if (!$surat->file_surat) {
            return back()->with('error', 'Dokumen naskah surat tidak tersedia.');
        }

        $path = storage_path('app/public/' . $surat->file_surat);
        if (!file_exists($path)) {
            return back()->with('error', 'File dokumen surat tidak ditemukan di server.');
        }

        // Pastikan nomor surat resmi tersinkronisasi di file docx sebelum diunduh
        if (!empty($surat->nomor_surat)) {
            SuratService::syncDocxNomorSurat($surat);
        }

        return response()->download($path, $surat->nama_file_asli ?: basename($path));
    }

    /**
     * Download Lampiran
     */
    public function downloadLampiran($id)
    {
        $lampiran = SuratLampiran::findOrFail($id);
        $path = storage_path('app/public/' . $lampiran->file_path);

        if (!file_exists($path)) {
            return back()->with('error', 'File lampiran tidak ditemukan di server.');
        }

        return response()->download($path, $lampiran->nama_file);
    }

    /**
     * Download template Excel (.xlsx) untuk membuat surat dari data Excel.
     * Kolom template menyesuaikan jenis surat (?jenis=ST|SK|B|UND|BA|ND).
     */
    public function downloadTemplateExcel(Request $request)
    {
        $kode = strtoupper((string) $request->query('jenis', 'ST'));
        $path = SuratExcelService::buildTemplateXlsx($kode);

        return response()->download($path, "template_surat_{$kode}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Buat surat secara otomatis dari data Excel (mail merge).
     * Setiap baris Excel diisikan ke template surat sistem.
     */
    public function importExcel(Request $request)
    {
        $user = Auth::user();
        if (!$user->canCreateSurat()) {
            return redirect()->route('surat.index')->with('error', 'Anda tidak memiliki hak akses untuk membuat surat.');
        }

        $request->validate([
            'file_excel'        => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
            'jenis_surat_id'    => 'required|exists:jenis_surats,id',
            'template_surat_id' => 'nullable|exists:template_surats,id',
            'unit_kerja_id'     => 'nullable|exists:unit_kerjas,id',
            'kode_klasifikasi'  => 'nullable|string|max:50',
            'mode_excel'        => 'nullable|in:per_baris,gabung',
            'status_default'    => 'nullable|in:selesai,draft',
        ], [
            'file_excel.required' => 'Silakan pilih file Excel berisi data surat.',
            'file_excel.mimes'    => 'File harus berformat Excel (.xlsx, .xls) atau CSV.',
        ]);

        $file = $request->file('file_excel');

        try {
            $rows = SuratExcelService::readRows($file->getRealPath(), $file->getClientOriginalExtension());
            if (empty($rows)) {
                return back()->withInput()->with('error', 'File Excel kosong atau formatnya tidak dapat dibaca. Simpan ulang sebagai .xlsx lalu coba lagi.');
            }

            $mapped = SuratExcelService::mapRecords($rows);
            if (empty($mapped['records'])) {
                return back()->withInput()->with('error', 'Tidak ada baris data di bawah judul kolom pada file Excel.');
            }

            $result = SuratExcelService::createSurats($mapped['records'], [
                'jenis_surat_id'    => $request->jenis_surat_id,
                'template_surat_id' => $request->template_surat_id,
                'unit_kerja_id'     => $request->unit_kerja_id,
                'kode_klasifikasi'  => $request->kode_klasifikasi,
                'mode'              => $request->input('mode_excel', 'per_baris'),
                'status'            => $request->input('status_default', 'selesai'),
            ], $user);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }

        $warnings = $result['warnings'];
        if (!empty($mapped['unknown'])) {
            $warnings[] = 'Kolom berikut tidak dikenali dan diabaikan: ' . implode(', ', $mapped['unknown']) . '.';
        }

        $jumlah = count($result['surats']);
        if ($jumlah === 0) {
            return back()->withInput()
                ->with('error', 'Tidak ada surat yang dibuat. Periksa kembali isi file Excel.')
                ->with('import_warnings', $warnings);
        }

        $redirect = $jumlah === 1
            ? redirect()->route('surat.show', $result['surats'][0]->id)
            : redirect()->route('surat.index', ['scope' => 'mine']);

        return $redirect
            ->with('success', "Berhasil membuat {$jumlah} surat otomatis dari data Excel.")
            ->with('import_warnings', $warnings);
    }

    /**
     * @deprecated Digantikan oleh SuratExcelService::readRows()
     */
    private function legacyImportUnused(Request $request, $file)
    {
        $user = Auth::user();
        $rows = $this->parseSpreadsheetRows($file);

        if (empty($rows)) {
            return back()->with('error', 'File Excel kosong atau format tidak dapat dibaca.');
        }

        // Ambil baris pertama untuk cek header
        $firstRow = $rows[0];
        $startIndex = 0;
        $firstCell = strtolower(trim((string)($firstRow[0] ?? '')));
        if (str_contains($firstCell, 'perihal') || str_contains($firstCell, 'judul') || str_contains($firstCell, 'subject')) {
            $startIndex = 1; // Lewati header
        }

        $defaultJenis = JenisSurat::where('aktif', true)->first();
        $allJenis = JenisSurat::where('aktif', true)->get();
        $importedCount = 0;
        $statusTarget = $request->input('status_default', 'selesai');

        DB::beginTransaction();
        try {
            for ($i = $startIndex; $i < count($rows); $i++) {
                $row = $rows[$i];
                $perihal = trim((string)($row[0] ?? ''));
                if (empty($perihal)) {
                    continue;
                }

                $rawDate = trim((string)($row[1] ?? ''));
                $tanggalSurat = Carbon::now()->format('Y-m-d');
                if (!empty($rawDate)) {
                    try {
                        if (is_numeric($rawDate) && (float)$rawDate > 30000) {
                            $tanggalSurat = Carbon::createFromTimestampUTC(((float)$rawDate - 25569) * 86400)->format('Y-m-d');
                        } else {
                            $tanggalSurat = Carbon::parse($rawDate)->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                        $tanggalSurat = Carbon::now()->format('Y-m-d');
                    }
                }

                $jenisStr = trim((string)($row[2] ?? ''));
                $matchedJenis = null;
                if (!empty($jenisStr)) {
                    $matchedJenis = $allJenis->first(function ($j) use ($jenisStr) {
                        return strcasecmp($j->kode, $jenisStr) === 0 || stripos($j->nama, $jenisStr) !== false;
                    });
                }
                $jenisSurat = $matchedJenis ?: $defaultJenis;

                $nomorManual = trim((string)($row[3] ?? '')) ?: null;
                $uraian = trim((string)($row[4] ?? '')) ?: $perihal;
                $kodeKlasifikasi = trim((string)($row[5] ?? '')) ?: ($jenisSurat?->kode_klasifikasi_default ?? 'VS.330');

                $surat = Surat::create([
                    'jenis_surat_id'   => $jenisSurat?->id,
                    'user_id'          => $user->id,
                    'unit_kerja_id'    => $user->unit_kerja_id,
                    'tanggal_pengajuan'=> Carbon::now(),
                    'tanggal_surat'    => $tanggalSurat,
                    'perihal'          => $perihal,
                    'fungsi'           => $user->unitKerja?->nama ?? 'BPS Kab. Manggarai',
                    'status'           => $statusTarget,
                    'sumber_surat'     => 'luar',
                    'nomor_surat'      => $nomorManual,
                    'kode_klasifikasi' => $kodeKlasifikasi,
                    'kode_satker'      => '53131',
                ]);

                SuratDetail::create([
                    'surat_id'        => $surat->id,
                    'uraian_kegiatan' => $uraian,
                    'pejabat_penetap' => 'Kepala Badan Pusat Statistik Kabupaten Manggarai',
                ]);

                if ($statusTarget === 'selesai') {
                    if (empty($surat->nomor_surat)) {
                        $nomorSurat = SuratService::generateNomorSurat($surat);
                        $surat->refresh();
                    } else {
                        $nomorSurat = $surat->nomor_surat;
                    }
                    SuratService::logStatus($surat, 'selesai', $user->id, "Surat berhasil diimpor dari Excel dengan nomor resmi: {$nomorSurat}");
                } else {
                    SuratService::logStatus($surat, 'draft', $user->id, 'Draft surat diimpor dari file Excel.');
                }

                $importedCount++;
            }

            DB::commit();

            if ($importedCount === 0) {
                return back()->with('warning', 'Tidak ada baris data surat yang valid untuk diimpor.');
            }

            return redirect()->route('surat.index')->with('success', "Berhasil mengunggah {$importedCount} naskah surat dari file Excel!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses file Excel: ' . $e->getMessage());
        }
    }

    /**
     * Parse file Excel (.xlsx, .xls) atau CSV menjadi array baris data.
     */
    private function parseSpreadsheetRows($file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $rows = [];

        if (in_array($extension, ['csv', 'txt'])) {
            $handle = fopen($file->getRealPath(), 'r');
            if ($handle) {
                $firstLine = fgets($handle);
                rewind($handle);
                $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

                while (($data = fgetcsv($handle, 2000, $delimiter)) !== false) {
                    $rows[] = array_map('trim', $data);
                }
                fclose($handle);
            }
        } elseif ($extension === 'xlsx' && class_exists('ZipArchive')) {
            $zip = new \ZipArchive();
            if ($zip->open($file->getRealPath()) === true) {
                $sharedStrings = [];
                $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
                if ($sharedStringsXml) {
                    $xml = @simplexml_load_string($sharedStringsXml);
                    if ($xml) {
                        foreach ($xml->si as $si) {
                            if (isset($si->t)) {
                                $sharedStrings[] = (string)$si->t;
                            } elseif (isset($si->r)) {
                                $tParts = [];
                                foreach ($si->r as $r) {
                                    $tParts[] = (string)($r->t ?? '');
                                }
                                $sharedStrings[] = implode('', $tParts);
                            } else {
                                $sharedStrings[] = '';
                            }
                        }
                    }
                }

                $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
                if ($sheetXml) {
                    $xml = @simplexml_load_string($sheetXml);
                    if ($xml && isset($xml->sheetData->row)) {
                        foreach ($xml->sheetData->row as $row) {
                            $rowData = [];
                            foreach ($row->c as $c) {
                                $val = (string)($c->v ?? '');
                                $type = (string)($c['t'] ?? '');
                                if ($type === 's' && isset($sharedStrings[(int)$val])) {
                                    $val = $sharedStrings[(int)$val];
                                } elseif ($type === 'inlineStr' && isset($c->is->t)) {
                                    $val = (string)$c->is->t;
                                }
                                $rowData[] = trim($val);
                            }
                            if (!empty(array_filter($rowData, fn($v) => $v !== ''))) {
                                $rows[] = $rowData;
                            }
                        }
                    }
                }
                $zip->close();
            }
        } elseif ($extension === 'xls') {
            $content = file_get_contents($file->getRealPath());
            if (str_contains($content, '<table') || str_contains($content, '<tr')) {
                @preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $content, $trMatches);
                if (!empty($trMatches[1])) {
                    foreach ($trMatches[1] as $tr) {
                        @preg_match_all('/<t[dh][^>]*>(.*?)<\/t[dh]>/is', $tr, $tdMatches);
                        if (!empty($tdMatches[1])) {
                            $rowData = array_map(fn($v) => trim(strip_tags(html_entity_decode($v))), $tdMatches[1]);
                            if (!empty(array_filter($rowData, fn($v) => $v !== ''))) {
                                $rows[] = $rowData;
                            }
                        }
                    }
                }
            }
        }

        return $rows;
    }
}
