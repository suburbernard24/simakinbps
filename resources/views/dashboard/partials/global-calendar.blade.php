<!-- resources/views/dashboard/partials/global-calendar.blade.php -->
<div class="p-6 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4 h-full">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700">
        <div>
            <h3 class="text-sm font-extrabold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-bps-blue"></i>
                <span>Kalender Pekerjaan</span>
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Klik tanggal untuk melihat daftar pegawai yang bertugas</p>
        </div>
    </div>

    <div id="calendar" class="w-full text-xs"></div>
    
    <!-- Tabel Pegawai Bekerja pada Tanggal Terpilih -->
    <div id="workingEmployeesContainer" class="hidden mt-4 pt-4 border-t border-slate-200/80 dark:border-slate-700 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h4 class="text-xs font-black text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                    <i class="fa-solid fa-clipboard-user text-bps-blue"></i>
                    <span>Daftar Pegawai yang Bekerja:</span>
                </h4>
                <p id="selectedDateLabel" class="text-[11px] font-bold text-bps-blue mt-0.5"></p>
            </div>
            <button type="button" onclick="document.getElementById('workingEmployeesContainer').classList.add('hidden')" class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-500 hover:text-slate-700 dark:text-slate-300 flex items-center justify-center transition" title="Tutup Tabel">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <div id="tableWrapper" class="overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200/80 dark:border-slate-700 text-[11px] font-extrabold uppercase text-slate-500 dark:text-slate-400 tracking-wider">
                    <tr>
                        <th class="py-2.5 px-3 text-center w-12">No</th>
                        <th class="py-2.5 px-3">Nama Pegawai</th>
                        <th class="py-2.5 px-3">Asal Tim</th>
                        <th class="py-2.5 px-3">Tugas / Kegiatan Aktif</th>
                    </tr>
                </thead>
                <tbody id="workingEmployeesTableBody" class="divide-y divide-slate-100 dark:divide-slate-700/60 bg-white dark:bg-slate-800">
                    <!-- Dynamic Rows -->
                </tbody>
            </table>
        </div>

        <div id="noEmployeesMessage" class="hidden py-8 text-center text-slate-400 text-xs bg-slate-50/60 dark:bg-slate-900/40 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
            <i class="fa-regular fa-calendar-xmark text-2xl mb-1 text-slate-300 dark:text-slate-600 block"></i>
            <span class="font-bold text-slate-700 dark:text-slate-300 block mb-0.5">Tidak Ada Jadwal Pekerjaan</span>
            <span class="text-slate-400 dark:text-slate-500">Tidak ada pegawai yang memiliki jadwal pekerjaan pada tanggal ini.</span>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<style>
    .fc-event {
        cursor: pointer;
        font-size: 11px !important;
        padding: 3px 6px !important;
        border-radius: 8px !important;
        border: 1px solid #BEDAF0 !important;
        background-color: #E8F1FD !important;
        color: #1565C0 !important;
        font-weight: 700 !important;
        text-align: center;
        transition: all 0.2s ease;
    }
    .fc-event:hover {
        background-color: #1565C0 !important;
        color: #ffffff !important;
        border-color: #1565C0 !important;
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
        transition: background-color 0.15s;
    }
    .fc-daygrid-day:hover {
        background-color: #f1f5f9;
    }
    html.dark .fc-daygrid-day:hover {
        background-color: rgba(51, 65, 85, 0.4);
    }
    .fc-daygrid-day.fc-day-selected {
        background-color: #e0f2fe !important;
    }
    html.dark .fc-toolbar-title {
        color: #f8fafc !important;
        font-size: 1rem !important;
    }
    .fc-button-primary {
        background-color: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #475569 !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('calendar')) {
            var calendarEl = document.getElementById('calendar');
            var rawTasksData = {!! json_encode($calendarEvents ?? []) !!};
            
            // Buat pemetaan per tanggal (YYYY-MM-DD) -> daftar pegawai & tugasnya
            var dateMap = {};

            rawTasksData.forEach(function(task) {
                var cur = new Date(task.start + 'T00:00:00');
                var end = new Date(task.end + 'T00:00:00');
                while (cur < end) {
                    var year = cur.getFullYear();
                    var month = String(cur.getMonth() + 1).padStart(2, '0');
                    var day = String(cur.getDate()).padStart(2, '0');
                    var dStr = year + '-' + month + '-' + day;

                    if (!dateMap[dStr]) {
                        dateMap[dStr] = {
                            employeesMap: {}
                        };
                    }

                    var empName = task.pegawai || 'Tanpa Nama';
                    if (!dateMap[dStr].employeesMap[empName]) {
                        dateMap[dStr].employeesMap[empName] = {
                            nama: empName,
                            asal_tim: task.asal_tim || '-',
                            tasks: []
                        };
                    }

                    dateMap[dStr].employeesMap[empName].tasks.push(task.pekerjaan || task.title || '-');
                    cur.setDate(cur.getDate() + 1);
                }
            });

            // Tanggal kalender dibiarkan polos tanpa teks/event apapun
            function showDateTable(clickedDateStr) {
                var dateObj = new Date(clickedDateStr + 'T00:00:00');
                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('selectedDateLabel').innerText = dateObj.toLocaleDateString('id-ID', options);
                
                var entry = dateMap[clickedDateStr];
                var employeesList = entry ? Object.values(entry.employeesMap) : [];

                var tbody = document.getElementById('workingEmployeesTableBody');
                var tableWrapper = document.getElementById('tableWrapper');
                var noMsg = document.getElementById('noEmployeesMessage');
                var container = document.getElementById('workingEmployeesContainer');
                tbody.innerHTML = '';
                
                if (employeesList.length > 0) {
                    employeesList.forEach(function(emp, idx) {
                        var tr = document.createElement('tr');
                        tr.className = 'hover:bg-slate-50/80 dark:hover:bg-slate-700/50 transition';
                        
                        var tasksHtml = emp.tasks.map(function(t) {
                            return '<div class="flex items-center gap-1.5 py-0.5"><span class="w-1.5 h-1.5 rounded-full bg-bps-blue shrink-0"></span><span class="font-medium">' + t + '</span></div>';
                        }).join('');

                        tr.innerHTML = `
                            <td class="py-3 px-3 text-center font-bold text-slate-400 dark:text-slate-500">${idx + 1}</td>
                            <td class="py-3 px-3 font-bold text-slate-900 dark:text-slate-100">${emp.nama}</td>
                            <td class="py-3 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                    ${emp.asal_tim}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-slate-700 dark:text-slate-300 text-xs">${tasksHtml}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                    tableWrapper.classList.remove('hidden');
                    noMsg.classList.add('hidden');
                } else {
                    tableWrapper.classList.add('hidden');
                    noMsg.classList.remove('hidden');
                }

                container.classList.remove('hidden');
                container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                height: 'auto',
                events: [], // Polos tanpa tulisan/event apapun di tanggal
                dateClick: function(info) {
                    showDateTable(info.dateStr);
                }
            });
            
            calendar.render();
        }
    });
</script>
@endpush
