<!-- BEGIN: BackToTopFloating -->
<a aria-label="Kembali ke atas" class="fixed bottom-6 right-6 w-11 h-11 rounded-full bg-bps-700 hover:bg-bps-600 text-white shadow-xl flex items-center justify-center transition-all hover:scale-110 active:scale-95 z-40" href="#">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</a>
<!-- END: BackToTopFloating -->

<!-- JavaScript Functions -->
<script>
function showToast(title, body) {
  const toast = document.getElementById('toast-notif');
  if (!toast) return;
  document.getElementById('toast-title').textContent = title;
  document.getElementById('toast-body').textContent = body;
  toast.classList.remove('hidden');
  setTimeout(() => {
    toast.classList.add('hidden');
  }, 2800);
}

function salinLinkTabel(url, judul) {
  navigator.clipboard.writeText(url).then(() => {
    showToast('Tautan Disalin!', judul);
  }).catch(() => {
    showToast('Tautan Disalin!', url);
  });
}

function salinTeksData(teks) {
  navigator.clipboard.writeText(teks).then(() => {
    showToast('Data Berhasil Disalin!', teks);
  }).catch(() => {
    showToast('Data Berhasil Disalin!', teks);
  });
}

function handleHeroSearch() {
  const query = document.getElementById('hero-search-query').value.trim();
  const category = document.getElementById('hero-search-category').value;
  if (!query) {
    document.getElementById('publikasi').scrollIntoView({ behavior: 'smooth' });
    return;
  }
  showToast('Mencari Data...', `Kata kunci: "${query}" (${category})`);
  document.getElementById('publikasi').scrollIntoView({ behavior: 'smooth' });
}

function filterPubCategory(cat, btn) {
  document.querySelectorAll('.pub-filter-btn').forEach(b => {
    b.className = 'pub-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors';
  });
  btn.className = 'pub-filter-btn px-5 py-2 rounded-full text-xs font-bold bg-bps-700 text-white shadow-sm shadow-bps-700/20';

  const cards = document.querySelectorAll('.pub-card-item');
  cards.forEach(card => {
    if (cat === 'semua' || card.classList.contains('pub-cat-' + cat)) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

function filterInfografis(cat, btn) {
  document.querySelectorAll('.info-filter-btn').forEach(b => {
    b.className = 'info-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors';
  });
  btn.className = 'info-filter-btn px-5 py-2 rounded-full text-xs font-bold bg-bps-700 text-white shadow-sm';

  const cards = document.querySelectorAll('.info-card-item');
  cards.forEach(card => {
    if (cat === 'semua' || card.classList.contains('info-cat-' + cat)) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

function scrollIndicatorCarousel(direction) {
  const container = document.getElementById('indicator-carousel');
  if (container) {
    container.scrollBy({ left: direction * 280, behavior: 'smooth' });
  }
}

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
      dot.className = 'quote-dot w-2.5 h-2.5 rounded-full bg-white scale-125 transition-all';
    } else {
      dot.className = 'quote-dot w-2.5 h-2.5 rounded-full bg-white/40 transition-all';
    }
  });
}

function bukaModalBukuTamu() {
  const modal = document.getElementById('modal-buku-tamu');
  if (modal) modal.classList.remove('hidden');
}

function tutupModalBukuTamu() {
  const modal = document.getElementById('modal-buku-tamu');
  if (modal) modal.classList.add('hidden');
}

function simpanBukuTamu() {
  const nama = document.getElementById('tamu-nama')?.value || 'Pengunjung';
  tutupModalBukuTamu();
  showToast('Terima Kasih!', `Kunjungan ${nama} telah tercatat di Buku Tamu Digital PST.`);
}

function bukaModalApiToken() {
  const modal = document.getElementById('modal-api-token');
  if (modal) modal.classList.remove('hidden');
}

function tutupModalApiToken() {
  const modal = document.getElementById('modal-api-token');
  if (modal) modal.classList.add('hidden');
}
</script>
