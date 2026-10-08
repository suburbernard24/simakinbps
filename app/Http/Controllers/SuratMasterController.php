<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\NomorSurat;
use App\Models\TemplateSurat;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class SuratMasterController extends Controller
{
    // =========================================================================
    // 1. MASTER UNIT KERJA / FUNGSI (PRD 10.4)
    // =========================================================================
    public function unitKerjaIndex()
    {
        $unitKerjas = UnitKerja::withCount('pegawais', 'surats')->orderBy('kode')->get();
        return view('surat.master.unit_kerja', compact('unitKerjas'));
    }

    public function unitKerjaStore(Request $request)
    {
        $request->validate([
            'kode'       => 'required|string|max:50|unique:unit_kerjas,kode',
            'nama'       => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        UnitKerja::create([
            'kode'       => strtoupper(trim($request->kode)),
            'nama'       => $request->nama,
            'keterangan' => $request->keterangan,
            'status'     => true,
        ]);

        return back()->with('success', 'Unit Kerja / Fungsi berhasil ditambahkan.');
    }

    public function unitKerjaUpdate(Request $request, $id)
    {
        $unitKerja = UnitKerja::findOrFail($id);

        $request->validate([
            'kode'       => 'required|string|max:50|unique:unit_kerjas,kode,' . $id,
            'nama'       => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $unitKerja->update([
            'kode'       => strtoupper(trim($request->kode)),
            'nama'       => $request->nama,
            'keterangan' => $request->keterangan,
            'status'     => $request->has('status'),
        ]);

        return back()->with('success', 'Data Unit Kerja berhasil diperbarui.');
    }

    public function unitKerjaDestroy($id)
    {
        $unitKerja = UnitKerja::withCount('surats')->findOrFail($id);
        if ($unitKerja->surats_count > 0) {
            return back()->with('error', 'Tidak dapat menghapus unit kerja yang sudah memiliki arsip surat.');
        }

        $unitKerja->delete();
        return back()->with('success', 'Unit Kerja berhasil dihapus.');
    }

    // =========================================================================
    // 2. MASTER JENIS SURAT (PRD 10.5)
    // =========================================================================
    public function jenisSuratIndex()
    {
        $jenisSurats = JenisSurat::withCount('templates', 'surats')->orderBy('nama')->get();
        return view('surat.master.jenis_surat', compact('jenisSurats'));
    }

    public function jenisSuratStore(Request $request)
    {
        $request->validate([
            'kode'                     => 'required|string|max:50|unique:jenis_surats,kode',
            'nama'                     => 'required|string|max:255',
            'format_nomor'             => 'required|string|max:255',
            'kode_klasifikasi_default' => 'required|string|max:50',
            'deskripsi'                => 'nullable|string',
        ]);

        JenisSurat::create([
            'kode'                     => strtoupper(trim($request->kode)),
            'nama'                     => $request->nama,
            'format_nomor'             => $request->format_nomor,
            'kode_klasifikasi_default' => $request->kode_klasifikasi_default,
            'deskripsi'                => $request->deskripsi,
            'aktif'                    => true,
        ]);

        return back()->with('success', 'Jenis Surat berhasil ditambahkan.');
    }

    public function jenisSuratUpdate(Request $request, $id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);

        $request->validate([
            'kode'                     => 'required|string|max:50|unique:jenis_surats,kode,' . $id,
            'nama'                     => 'required|string|max:255',
            'format_nomor'             => 'required|string|max:255',
            'kode_klasifikasi_default' => 'required|string|max:50',
            'deskripsi'                => 'nullable|string',
        ]);

        $jenisSurat->update([
            'kode'                     => strtoupper(trim($request->kode)),
            'nama'                     => $request->nama,
            'format_nomor'             => $request->format_nomor,
            'kode_klasifikasi_default' => $request->kode_klasifikasi_default,
            'deskripsi'                => $request->deskripsi,
            'aktif'                    => $request->has('aktif'),
        ]);

        return back()->with('success', 'Jenis Surat berhasil diperbarui.');
    }

    // =========================================================================
    // 3. MASTER TEMPLATE SURAT (PRD 10.6)
    // =========================================================================
    public function templateIndex()
    {
        $templates = TemplateSurat::with('jenisSurat')->withCount('surats')->latest()->get();
        $jenisSurats = JenisSurat::where('aktif', true)->get();
        return view('surat.master.template', compact('templates', 'jenisSurats'));
    }

    public function templateStore(Request $request)
    {
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'kode_template'  => 'required|string|max:50|unique:template_surats,kode_template',
            'nama_template'  => 'required|string|max:255',
            'kop_surat'      => 'nullable|string',
            'isi_template'   => 'required|string',
            'penutup'        => 'nullable|string',
        ]);

        TemplateSurat::create([
            'jenis_surat_id' => $request->jenis_surat_id,
            'kode_template'  => strtoupper(trim($request->kode_template)),
            'nama_template'  => $request->nama_template,
            'kop_surat'      => $request->kop_surat,
            'isi_template'   => $request->isi_template,
            'penutup'        => $request->penutup,
            'aktif'          => true,
        ]);

        return back()->with('success', 'Template Surat berhasil ditambahkan.');
    }

    public function templateUpdate(Request $request, $id)
    {
        $template = TemplateSurat::findOrFail($id);

        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'kode_template'  => 'required|string|max:50|unique:template_surats,kode_template,' . $id,
            'nama_template'  => 'required|string|max:255',
            'kop_surat'      => 'nullable|string',
            'isi_template'   => 'required|string',
            'penutup'        => 'nullable|string',
        ]);

        $template->update([
            'jenis_surat_id' => $request->jenis_surat_id,
            'kode_template'  => strtoupper(trim($request->kode_template)),
            'nama_template'  => $request->nama_template,
            'kop_surat'      => $request->kop_surat,
            'isi_template'   => $request->isi_template,
            'penutup'        => $request->penutup,
            'aktif'          => $request->has('aktif'),
        ]);

        return back()->with('success', 'Template Surat berhasil diperbarui.');
    }

    // =========================================================================
    // 4. MASTER / PENGATURAN PENOMORAN (PRD 10.10 & 16)
    // =========================================================================
    public function penomoranIndex()
    {
        $jenisSurats = JenisSurat::with('nomorSurats')->get();
        $nomorRecords = NomorSurat::with(['jenisSurat', 'surat'])->latest()->paginate(20);

        return view('surat.master.penomoran', compact('jenisSurats', 'nomorRecords'));
    }
}
