@extends('layouts.app')

@section('title', 'Kalender Alokasi Pekerjaan')
@section('page_title', 'Kalender Pekerjaan BPS')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<style>
    .fc-event {
        cursor: pointer;
        border-radius: 8px !important;
        padding: 4px 8px !important;
        font-size: 0.725rem !important;
        font-weight: 700 !important;
        border: 1px solid #BEDAF0 !important;
        background-color: #E8F1FD !important;
        color: #1565C0 !important;
        box-shadow: 0 1px 3px rgba(21, 101, 192, 0.08);
        transition: all 0.2s ease;
        text-align: center;
    }
    .fc-event:hover {
        transform: translateY(-1px);
        background-color: #1565C0 !important;
        color: #ffffff !important;
        border-color: #1565C0 !important;
        box-shadow: 0 4px 12px rgba(21, 101, 192, 0.25);
    }
    html.dark .fc-event {
        background-color: rgba(30, 58, 138, 0.35) !important;
        border-color: rgba(59, 130, 246, 0.4) !important;
        color: #93c5fd !important;
    }
    html.dark .fc-event:hover {
        background-color: #2563eb !important;
        color: #ffffff !important;
    }
    .fc-daygrid-day {
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    .fc-daygrid-day:hover {
        background-color: #f1f5f9;
    }
    html.dark .fc-daygrid-day:hover {
        background-color: rgba(51, 65, 85, 0.4);
    }
    .fc-toolbar-title {
        font-size: 1.125rem !important;
        font-weight: 800 !important;
        color: #0f172a;
    }
    html.dark .fc-toolbar-title {
        color: #f8fafc !important;
    }
    .fc-button-primary {
        background-color: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #475569 !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        text-transform: capitalize !important;
    }
    .fc-button-primary:hover {
        background-color: #f1f5f9 !important;
    }
    .fc-button-active {
        background-color: #1565C0 !important;
        color: white !important;
        border-color: #1565C0 !important;
    }
    .fc-day-today {
        background-color: #eff6ff !important;
    }
    html.dark .fc-day-today {
        background-color: rgba(30, 58, 138, 0.2) !important;
    }
    html.dark .fc-theme-standard td, 
    html.dark .fc-theme-standard th {
        border-color: #334155 !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6" x-data="{ 
    modalOpen: false, 
    modalDateOpen: false,
    selectedDateTitle: '',
    selectedDateStr: '',
    dateEmployees: [],
    detail: {
        nama_kegiatan: '',
        pegawai: '',
        tim: '',
        jenis_pekerjaan: '',
        tanggal_mulai: '',
        tanggal_selesai: '',
        estimasi_waktu: '',
        target: '',
        realisasi: '',
        status: ''
    }
}">

    <!-- Top Header Card -->
    <div class="p-6 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-bps-faint text-bps-blue text-xs font-bold uppercase tracking-wider mb-2 border border-bps-pale">
                <i class="fa-solid fa-calendar-days text-bps-blue"></i>
                <span>Jadwal Operasional BPS</span>
            </div>
            <h2 class="text-lg font-black text-slate-900 dark:text-slate-100">Kalender Kerja Pegawai</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pantau persebaran dan kehadiran penugasan pegawai BPS Kabupaten Manggarai setiap harinya.</p>
        </div>

        <!-- Panduan Aksi -->
        <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-bps-faint border border-bps-pale text-bps-blue text-xs font-bold shadow-xs">
            <i class="fa-regular fa-hand-pointer text-sm animate-bounce"></i>
            <span>Klik tanggal untuk melihat daftar pegawai yang bekerja</span>
        </div>
    </div>

    <!-- Calendar View Container -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-700 shadow-sm">
        <div id="calendar" class="min-h-[650px]"></div>
    </div>

    <!-- Modal Daftar Pegawai Bekerja pada Tanggal (Saat Tanggal Diklik) -->
    <div x-show="modalDateOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalDateOpen = false" class="bg-white dark:bg-slate-800 rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-100 dark:border-slate-700 space-y-5 max-h-[90vh] flex flex-col">
            
            <!-- Modal Header -->
            <div class="flex items-start justify-between pb-3 border-b border-slate-100 dark:border-slate-700 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-bps-faint text-bps-blue flex items-center justify-center font-bold text-lg">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900 dark:text-slate-100">
                            Pegawai yang Bekerja
                        </h3>
                        <p class="text-xs font-bold text-bps-blue mt-0.5" x-text="selectedDateTitle"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <template x-if="dateEmployees.length > 0">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-bps-faint text-bps-blue border border-bps-pale" x-text="dateEmployees.length + ' Pegawai'"></span>
                    </template>
                    <button @click="modalDateOpen = false" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-500 hover:text-slate-700 dark:text-slate-300 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Konten Modal: Daftar Pegawai -->
            <div class="flex-1 overflow-y-auto pr-1 space-y-3">
                <template x-if="dateEmployees.length > 0">
                    <div class="space-y-3">
                        <template x-for="(emp, empIdx) in dateEmployees" :key="empIdx">
                            <div class="p-4 rounded-2xl bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700/80 space-y-3 transition hover:border-bps-blue/40">
                                
                                <!-- Baris Profil Pegawai -->
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <img :src="emp.avatar" :alt="emp.nama" class="w-10 h-10 rounded-xl object-cover ring-2 ring-bps-blue/20 shrink-0" />
                                        <div class="min-w-0">
                                            <p class="text-sm font-black text-slate-900 dark:text-slate-100 truncate" x-text="emp.nama"></p>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-mono" x-text="'NIP. ' + (emp.nip || '-')"></p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 shrink-0 shadow-2xs" x-text="emp.tim"></span>
                                </div>

                                <!-- Daftar Tugas / Pekerjaan Hari Ini -->
                                <div class="pt-2 border-t border-slate-200/60 dark:border-slate-700/60 space-y-1.5">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tugas / Kegiatan Aktif:</p>
                                    <div class="space-y-1.5">
                                        <template x-for="(task, taskIdx) in emp.tasks" :key="taskIdx">
                                            <div @click="detail = task; modalDateOpen = false; modalOpen = true" 
                                                 class="flex items-center justify-between gap-2 p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 hover:border-bps-blue hover:shadow-xs transition cursor-pointer group">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase shrink-0"
                                                          :class="task.jenis_pekerjaan === 'utama' ? 'bg-blue-50 dark:bg-blue-900/30 text-bps-blue border border-blue-200 dark:border-blue-800' : 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800'"
                                                          x-text="task.jenis_pekerjaan === 'utama' ? 'Utama' : 'Tambahan'">
                                                    </span>
                                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-bps-blue transition" x-text="task.nama_kegiatan"></p>
                                                </div>
                                                <div class="flex items-center gap-2 shrink-0">
                                                    <span class="text-[10px] font-semibold text-slate-500" x-text="task.target"></span>
                                                    <i class="fa-solid fa-circle-info text-xs text-slate-400 group-hover:text-bps-blue transition"></i>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>
                </template>

                <!-- State Kosong jika tidak ada yang bekerja -->
                <template x-if="dateEmployees.length === 0">
                    <div class="py-12 text-center text-slate-400 text-xs bg-slate-50/60 dark:bg-slate-900/40 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 space-y-2">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto text-xl">
                            <i class="fa-regular fa-calendar-xmark"></i>
                        </div>
                        <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">Tidak Ada Jadwal Pekerjaan</p>
                        <p class="text-slate-400 dark:text-slate-500 max-w-sm mx-auto">Tidak ada penugasan atau jadwal pekerjaan pegawai BPS yang tercatat aktif pada tanggal ini.</p>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-end shrink-0">
                <button type="button" @click="modalDateOpen = false" class="px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-slate-700 text-white font-bold text-xs hover:bg-slate-800 dark:hover:bg-slate-600 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- Modal Detail Tugas / Pekerjaan -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalOpen = false" class="bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 dark:border-slate-700 space-y-5">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700">
                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider"
                      :class="detail.jenis_pekerjaan === 'utama' ? 'bg-blue-50 dark:bg-blue-900/30 text-bps-blue border border-blue-200 dark:border-blue-800' : 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800'"
                      x-text="detail.jenis_pekerjaan === 'utama' ? 'Pekerjaan Utama' : 'Pekerjaan Tambahan'">
                </span>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div>
                <h3 class="text-base font-black text-slate-900 dark:text-slate-100 leading-snug" x-text="detail.nama_kegiatan"></h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" x-text="'Tim: ' + detail.tim"></p>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 space-y-0.5">
                    <p class="text-[10px] font-extrabold uppercase text-slate-400">Pegawai Penanggungjawab</p>
                    <p class="font-bold text-slate-800 dark:text-slate-200" x-text="detail.pegawai_nama || detail.pegawai"></p>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 space-y-0.5">
                    <p class="text-[10px] font-extrabold uppercase text-slate-400">Estimasi Waktu</p>
                    <p class="font-bold text-slate-800 dark:text-slate-200" x-text="(detail.estimasi_waktu || '0') + ' Jam'"></p>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 space-y-0.5">
                    <p class="text-[10px] font-extrabold uppercase text-slate-400">Target Kuantitas</p>
                    <p class="font-bold text-slate-800 dark:text-slate-200" x-text="detail.target"></p>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 space-y-0.5">
                    <p class="text-[10px] font-extrabold uppercase text-slate-400">Realisasi</p>
                    <p class="font-bold text-emerald-600 dark:text-emerald-400" x-text="detail.realisasi"></p>
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 space-y-1 text-xs">
                <div class="flex justify-between text-slate-600 dark:text-slate-400">
                    <span class="text-slate-400 font-medium">Periode Pengerjaan:</span>
                    <strong class="text-slate-800 dark:text-slate-200" x-text="(detail.tanggal_mulai_formatted || detail.tanggal_mulai) + ' s/d ' + (detail.tanggal_selesai_formatted || detail.tanggal_selesai)"></strong>
                </div>
                <div class="flex justify-between text-slate-600 dark:text-slate-400">
                    <span class="text-slate-400 font-medium">Status Pekerjaan:</span>
                    <span class="font-bold uppercase text-[10px] px-2 py-0.5 rounded bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300" x-text="detail.status"></span>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-between">
                <button type="button" @click="modalOpen = false; modalDateOpen = true" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali ke Daftar Pegawai</span>
                </button>
                <button type="button" @click="modalOpen = false" class="px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-slate-700 text-white font-bold text-xs hover:bg-slate-800 dark:hover:bg-slate-600 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        @php
            $tasksPayload = $alokasi->filter(fn($item) => $item->tanggal_mulai && $item->tanggal_selesai)->map(function($item) {
                $pegawaiName = $item->pegawai ? $item->pegawai->name : 'Tanpa Nama';
                $pegawaiNip = $item->pegawai ? ($item->pegawai->nip ?? '-') : '-';
                $pegawaiJabatan = $item->pegawai ? ($item->pegawai->jabatan ?? 'Pegawai BPS') : 'Pegawai BPS';
                $pegawaiAvatar = $item->pegawai ? $item->pegawai->avatar_url : 'https://ui-avatars.com/api/?name='.urlencode($pegawaiName).'&background=1565C0&color=ffffff&bold=true';
                $timName = $item->asal_tim ?: ($item->tim ? $item->tim->nama_tim : ($item->pegawai && $item->pegawai->tim ? $item->pegawai->tim->nama_tim : '-'));
                
                return [
                    'id' => $item->id,
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'pegawai_id' => $item->pegawai_id ?? $pegawaiName,
                    'pegawai_nama' => $pegawaiName,
                    'pegawai' => $pegawaiName,
                    'pegawai_nip' => $pegawaiNip,
                    'pegawai_jabatan' => $pegawaiJabatan,
                    'avatar' => $pegawaiAvatar,
                    'tim' => $timName,
                    'jenis_pekerjaan' => $item->jenis_pekerjaan,
                    'start' => $item->tanggal_mulai->format('Y-m-d'),
                    'end' => $item->tanggal_selesai->copy()->addDay()->format('Y-m-d'),
                    'tanggal_mulai' => $item->tanggal_mulai->format('d M Y'),
                    'tanggal_selesai' => $item->tanggal_selesai->format('d M Y'),
                    'tanggal_mulai_formatted' => $item->tanggal_mulai->translatedFormat('d M Y'),
                    'tanggal_selesai_formatted' => $item->tanggal_selesai->translatedFormat('d M Y'),
                    'estimasi_waktu' => (string)($item->estimasi_waktu ?? '0'),
                    'target' => $item->target_kuantitas . ' ' . $item->satuan,
                    'realisasi' => $item->realisasi_kuantitas . ' ' . $item->satuan . ' (' . $item->persentase_kuantitas . '%)',
                    'status' => ucfirst($item->status),
                ];
            })->values();
        @endphp

        const allTasks = @json($tasksPayload);

        // Buat pemetaan per tanggal (YYYY-MM-DD) -> daftar pegawai & tugasnya
        const dateMap = {};

        allTasks.forEach(task => {
            let cur = new Date(task.start + 'T00:00:00');
            const end = new Date(task.end + 'T00:00:00');
            while (cur < end) {
                const year = cur.getFullYear();
                const month = String(cur.getMonth() + 1).padStart(2, '0');
                const day = String(cur.getDate()).padStart(2, '0');
                const dStr = year + '-' + month + '-' + day;

                if (!dateMap[dStr]) {
                    dateMap[dStr] = {
                        employeesMap: {}
                    };
                }

                const empKey = task.pegawai_id || task.pegawai_nama;
                if (!dateMap[dStr].employeesMap[empKey]) {
                    dateMap[dStr].employeesMap[empKey] = {
                        id: empKey,
                        nama: task.pegawai_nama,
                        nip: task.pegawai_nip,
                        jabatan: task.pegawai_jabatan,
                        avatar: task.avatar,
                        tim: task.tim,
                        tasks: []
                    };
                }

                dateMap[dStr].employeesMap[empKey].tasks.push(task);
                cur.setDate(cur.getDate() + 1);
            }
        });

        // Tanggal pada kalender dibiarkan polos dan bersih tanpa teks atau event apapun
        function showDateDetails(dateStr) {
            const dateObj = new Date(dateStr + 'T00:00:00');
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            const dateFormatted = dateObj.toLocaleDateString('id-ID', options);

            const entry = dateMap[dateStr];
            const employeesList = entry ? Object.values(entry.employeesMap) : [];

            const alpine = Alpine.$data(document.querySelector('[x-data]'));
            alpine.selectedDateTitle = dateFormatted;
            alpine.selectedDateStr = dateStr;
            alpine.dateEmployees = employeesList;
            alpine.modalDateOpen = true;
        }

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            events: [], // Tanpa event/teks di tanggal, kalender tampil bersih dan interaktif saat tanggal diklik
            dateClick: function(info) {
                showDateDetails(info.dateStr);
            }
        });

        calendar.render();
    });
</script>
@endpush
@endsection
