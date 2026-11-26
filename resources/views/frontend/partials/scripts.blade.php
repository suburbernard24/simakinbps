<!-- BEGIN: BackToTopFloating -->
<a aria-label="Kembali ke atas" class="fixed bottom-6 right-6 w-11 h-11 rounded-md bg-[#123B63] hover:bg-[#1E5B91] text-white border border-white/20 flex items-center justify-center transition-colors z-40" href="#" onclick="event.preventDefault(); window.scrollTo({top: 0, behavior: 'smooth'});">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 10l7-7m0 0l7 7m-7-7v18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</a>
<!-- END: BackToTopFloating -->

<!-- JavaScript Functions -->
<script>
// =========================================================================
// TOAST NOTIFICATIONS & CLIPBOARD
// =========================================================================
function showToast(title, body) {
  const toast = document.getElementById('toast-notif');
  if (!toast) return;
  document.getElementById('toast-title').textContent = title;
  document.getElementById('toast-body').textContent = body;
  toast.classList.remove('hidden');
  setTimeout(() => {
    toast.classList.add('hidden');
  }, 3200);
}

function salinTeksData(teks) {
  navigator.clipboard.writeText(teks).then(() => {
    showToast('Data Berhasil Disalin!', teks);
  }).catch(() => {
    showToast('Data Berhasil Disalin!', teks);
  });
}

function salinLinkTabel(url, judul) {
  navigator.clipboard.writeText(url).then(() => {
    showToast('Tautan Disalin!', judul);
  }).catch(() => {
    showToast('Tautan Disalin!', url);
  });
}

// =========================================================================
// DATA STRATEGIS FILTER (NTT BPS STYLE)
// =========================================================================
function filterDataStrategis(cat, btn) {
  document.querySelectorAll('.data-filter-btn').forEach(b => {
    b.className = 'data-filter-btn px-4 py-2 rounded-md text-xs font-medium bg-white border border-slate-200 text-slate-700 hover:bg-[#F4F7FA] transition-colors';
  });
  btn.className = 'data-filter-btn px-4 py-2 rounded-md text-xs font-semibold bg-[#123B63] text-white transition-colors';

  const cards = document.querySelectorAll('.data-card-item');
  cards.forEach(card => {
    if (cat === 'semua' || card.classList.contains('data-cat-' + cat)) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

// =========================================================================
// INFOGRAFIS & PUBLIKASI FILTER
// =========================================================================
function filterInfografis(cat, btn) {
  document.querySelectorAll('.info-filter-btn').forEach(b => {
    b.className = 'info-filter-btn px-4 py-2 min-h-[38px] rounded-lg text-xs font-medium bg-[#F4F7FA] text-slate-700 hover:bg-slate-200/70 transition-colors';
  });
  btn.className = 'info-filter-btn px-4 py-2 min-h-[38px] rounded-lg text-xs font-semibold bg-[#123B63] text-white transition-colors';

  const cards = document.querySelectorAll('.info-card-item');
  cards.forEach(card => {
    if (cat === 'semua' || card.classList.contains('info-cat-' + cat)) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

function filterPubCategory(cat, btn) {
  document.querySelectorAll('.pub-filter-btn').forEach(b => {
    b.className = 'pub-filter-btn px-4 py-2 min-h-[40px] rounded-md text-xs font-medium bg-white border border-slate-200 text-slate-700 hover:bg-[#F4F7FA] transition-colors';
  });
  btn.className = 'pub-filter-btn px-4 py-2 min-h-[40px] rounded-md text-xs font-semibold bg-[#123B63] text-white transition-colors';

  const cards = document.querySelectorAll('.pub-card-item');
  cards.forEach(card => {
    if (cat === 'semua' || card.classList.contains('pub-cat-' + cat)) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

// =========================================================================
// INDIKATOR CAROUSEL SCROLLER (> UNTUK GESER KE SAMPING)
// =========================================================================
function scrollIndicatorCarousel(direction) {
  const container = document.getElementById('indicator-carousel');
  if (container) {
    const card = container.querySelector('.indicator-card-item');
    const scrollAmount = card ? (card.offsetWidth + 20) : 320;
    container.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
  }
}

// =========================================================================
// QUOTES SLIDER
// =========================================================================
function switchQuoteSlide(index) {
  document.querySelectorAll('.quote-slide').forEach((slide, idx) => {
    if (idx === index) {
      slide.classList.remove('hidden');
    } else {
      slide.classList.add('hidden');
    }
  });
  document.querySelectorAll('.quote-dot').forEach((dot, idx) => {
    if (idx === index) {
      dot.className = 'quote-dot w-2.5 h-2.5 rounded-full bg-[#1E5B91] scale-125 transition-colors';
    } else {
      dot.className = 'quote-dot w-2.5 h-2.5 rounded-full bg-slate-300 transition-colors';
    }
  });
}

// =========================================================================
// BOOKING KONSULTASI 24/7 (LOGIC JAM LAYANAN VS LUAR JAM LAYANAN)
// =========================================================================
function cekJamLayananWITA() {
  // Ambil waktu WITA (UTC+8)
  const now = new Date();
  const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
  const witaTime = new Date(utc + (3600000 * 8));
  
  const day = witaTime.getDay(); // 0: Minggu, 6: Sabtu, 1-5: Senin-Jumat
  const hours = witaTime.getHours();
  const minutes = witaTime.getMinutes();
  const currentMinutes = hours * 60 + minutes;

  // Jam operasional PST BPS Manggarai: Senin-Jumat, 08:00 - 15:30 WITA
  const openMinutes = 8 * 60;
  const closeMinutes = 15 * 60 + 30;

  const isWeekday = (day >= 1 && day <= 5);
  const isOfficeHours = isWeekday && (currentMinutes >= openMinutes && currentMinutes <= closeMinutes);

  const clockEl = document.getElementById('label-live-clock');
  if (clockEl) {
    const pad = n => n < 10 ? '0' + n : n;
    const timeStr = `${pad(hours)}:${pad(minutes)} WITA`;
    clockEl.textContent = `${timeStr} (${isOfficeHours ? 'Dalam Jam Kerja' : 'Luar Jam Kerja - Siaga 24/7'})`;
  }

  const radioDalam = document.getElementById('radio-jam-dalam');
  const radioLuar = document.getElementById('radio-jam-luar');

  if (radioDalam && radioLuar) {
    if (isOfficeHours) {
      radioDalam.checked = true;
    } else {
      radioLuar.checked = true;
    }
  }
  updateInfoJamLayanan();
}

function updateInfoJamLayanan() {
  const radioLuar = document.getElementById('radio-jam-luar');
  const bannerLuar = document.getElementById('banner-luar-jam');
  if (bannerLuar && radioLuar) {
    if (radioLuar.checked) {
      bannerLuar.classList.remove('hidden');
    } else {
      bannerLuar.classList.add('hidden');
    }
  }
}

function bukaModalKonsultasi() {
  const modal = document.getElementById('modal-booking-konsultasi');
  if (modal) {
    modal.classList.remove('hidden');
    cekJamLayananWITA();
  }
}

function tutupModalKonsultasi() {
  const modal = document.getElementById('modal-booking-konsultasi');
  if (modal) modal.classList.add('hidden');
}

function simpanBookingKonsultasi() {
  const nama = document.getElementById('konsul-nama')?.value || 'Pemohon';
  const waktu = document.querySelector('input[name="waktu_layanan"]:checked')?.value;
  const kode = 'BK-' + Date.now().toString().slice(-6);

  tutupModalKonsultasi();

  const pesan = (waktu === 'luar')
    ? `Booking siaga 24/7 #${kode} atas nama ${nama} tercatat! Petugas PST akan menghubungi via WA pada hari kerja berikutnya.`
    : `Booking konsultasi #${kode} atas nama ${nama} berhasil dibuat! Petugas PST akan segera merespons.`;

  showToast('Booking Berhasil Terkirim!', pesan);
}

// =========================================================================
// BUKU TAMU DIGITAL (INLINE & MODAL)
// =========================================================================
function bukaModalBukuTamu() {
  const modal = document.getElementById('modal-buku-tamu');
  if (modal) modal.classList.remove('hidden');
}

function tutupModalBukuTamu() {
  const modal = document.getElementById('modal-buku-tamu');
  if (modal) modal.classList.add('hidden');
}

function setTamuRating(val) {
  document.getElementById('tamu-rating-val').value = val;
  const stars = document.querySelectorAll('.rating-star');
  const textEl = document.getElementById('rating-text');
  
  const labels = {
    1: 'Sangat Tidak Puas (1/5)',
    2: 'Kurang Puas (2/5)',
    3: 'Cukup Puas (3/5)',
    4: 'Puas (4/5)',
    5: 'Sangat Puas (5/5)'
  };

  stars.forEach((star, idx) => {
    const starVal = idx + 1;
    if (starVal <= val) {
      star.className = 'text-amber-400 text-lg transition rating-star';
    } else {
      star.className = 'text-slate-300 hover:text-amber-400 text-lg transition rating-star';
    }
  });

  if (textEl && labels[val]) {
    textEl.textContent = labels[val];
  }
}

function simpanBukuTamuLengkap() {
  const nama = document.getElementById('tamu-nama-lengkap')?.value || 'Pengunjung';
  const instansi = document.getElementById('tamu-instansi-lengkap')?.value || '';
  tutupModalBukuTamu();
  showToast('Terima Kasih!', `Kunjungan ${nama} (${instansi}) telah tercatat di Buku Tamu Digital PST BPS Manggarai.`);
}

function simpanBukuTamuInline() {
  const nama = document.getElementById('inline-tamu-nama')?.value || 'Pengunjung';
  const instansi = document.getElementById('inline-tamu-instansi')?.value || '';
  showToast('Buku Tamu Tercatat!', `Terima kasih ${nama} (${instansi}). Kehadiran Anda resmi tercatat di NATAS BPS.`);
  document.getElementById('form-inline-buku-tamu')?.reset();
}

function simpanBukuTamu() {
  const nama = document.getElementById('tamu-nama')?.value || 'Pengunjung';
  tutupModalBukuTamu();
  showToast('Terima Kasih!', `Kunjungan ${nama} telah tercatat di Buku Tamu Digital NATAS BPS.`);
}

// =========================================================================
// API TOKEN MODAL
// =========================================================================
function bukaModalApi() {
  const modal = document.getElementById('modal-api-token');
  if (modal) modal.classList.remove('hidden');
}

function bukaModalApiToken() {
  bukaModalApi();
}

function tutupModalApiToken() {
  const modal = document.getElementById('modal-api-token');
  if (modal) modal.classList.add('hidden');
}

// Inisialisasi awal saat DOM siap
document.addEventListener('DOMContentLoaded', function() {
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      tutupModalKonsultasi();
      tutupModalBukuTamu();
      tutupModalApiToken();
    }
  });

  // Smooth scroll dengan easing untuk anchor links
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!prefersReducedMotion) {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        if (!targetId || targetId === '#') return;
        const targetEl = document.querySelector(targetId);
        if (!targetEl) return;

        e.preventDefault();
        const headerOffset = 72;
        const startY = window.pageYOffset;
        const targetY = targetEl.getBoundingClientRect().top + startY - headerOffset;
        const distance = targetY - startY;
        const duration = 550;
        let startTime = null;

        function easeOutCubic(t) {
          return 1 - Math.pow(1 - t, 3);
        }

        function step(timestamp) {
          if (!startTime) startTime = timestamp;
          const elapsed = timestamp - startTime;
          const progress = Math.min(elapsed / duration, 1);
          window.scrollTo(0, startY + distance * easeOutCubic(progress));
          if (progress < 1) {
            window.requestAnimationFrame(step);
          }
        }

        window.requestAnimationFrame(step);
      });
    });
  }
});
</script>
