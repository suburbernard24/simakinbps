<!-- JavaScript Functions -->
<script>
function showToast(title, body) {
  const toast = document.getElementById('toast-notif');
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
    b.className = 'pub-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200';
  });
  btn.className = 'pub-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-sm';

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
    b.className = 'info-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200';
  });
  btn.className = 'info-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-sm';

  const cards = document.querySelectorAll('.info-card-item');
  cards.forEach(card => {
    if (cat === 'semua' || card.classList.contains('info-cat-' + cat)) {
      card.style.display = 'block';
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

function bukaModalBukuTamu() {
  document.getElementById('modal-buku-tamu').classList.remove('hidden');
}

function tutupModalBukuTamu() {
  document.getElementById('modal-buku-tamu').classList.add('hidden');
}

function simpanBukuTamu() {
  const nama = document.getElementById('tamu-nama').value;
  tutupModalBukuTamu();
  showToast('Terima Kasih!', `Kunjungan ${nama} telah tercatat di Buku Tamu Digital PST.`);
}

function bukaModalApiToken() {
  document.getElementById('modal-api-token').classList.remove('hidden');
}

function tutupModalApiToken() {
  document.getElementById('modal-api-token').classList.add('hidden');
}
</script>
