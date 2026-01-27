@extends('layouts.app')

@section('title', 'Create Worker Opening - NOCIS')
@section('page-title')
    Create Worker Opening
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <form method="POST" action="{{ route('admin.workers.store') }}" class="space-y-6">
            @csrf
            @include('menu.workers.partials.form-fields')

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.workers.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600">
                    Save Opening
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showFlashMessage(message, type = 'status') {
        // Create flash message directly in DOM
        const flashContainer = document.getElementById('flash-container') || createFlashContainer();
        
        // Prevent duplicate messages
        const existingMessages = flashContainer.querySelectorAll('.flash-message');
        for (let msg of existingMessages) {
            if (msg.textContent.trim() === message.trim()) {
                return; // Don't show duplicate
            }
        }
        
        const iconMap = {
            'status': 'fas fa-check-circle',
            'error': 'fas fa-exclamation-circle',
            'warning': 'fas fa-exclamation-triangle'
        };
        
        const classMap = {
            'status': 'bg-green-500 text-white',
            'error': 'bg-red-500 text-white',
            'warning': 'bg-yellow-500 text-white'
        };
        
        const flashMessage = document.createElement('div');
        flashMessage.className = `flash-message ${classMap[type]} shadow-lg rounded-lg px-4 py-3 text-sm flex items-start gap-3 transition duration-300 ease-out`;
        flashMessage.setAttribute('data-timeout', '4500');
        flashMessage.setAttribute('role', 'alert');
        flashMessage.innerHTML = `
            <i class="${iconMap[type]} mt-0.5"></i>
            <div class="flex-1">${message}</div>
            <button type="button" class="text-white/70 hover:text-white transition" data-flash-close aria-label="Close notification">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Set initial styles for animation
        flashMessage.style.opacity = '0';
        flashMessage.style.transform = 'translateX(100%)';
        
        flashContainer.appendChild(flashMessage);
        
        // Auto hide after timeout
        setTimeout(() => hideFlashMessage(flashMessage), 4500);
        
        // Manual close button
        flashMessage.querySelector('[data-flash-close]').addEventListener('click', () => {
            hideFlashMessage(flashMessage);
        });
        
        // Show with animation
        requestAnimationFrame(() => {
            flashMessage.style.opacity = '1';
            flashMessage.style.transform = 'translateX(0)';
        });
    }
    
    function createFlashContainer() {
        // Use the existing flash container from server-side, don't create new one
        const existingContainer = document.getElementById('flash-container');
        if (existingContainer) {
            return existingContainer;
        }
        
        // If no existing container, create one
        const container = document.createElement('div');
        container.id = 'flash-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
        return container;
    }
    
    function hideFlashMessage(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }
    
    // Check for URL flash parameters
    function checkUrlFlashMessages() {
        const urlParams = new URLSearchParams(window.location.search);
        const flash = urlParams.get('flash');
        const name = urlParams.get('name');
        
        if (flash === 'created' && name) {
            showFlashMessage(`Worker opening "${name}" created successfully!`, 'status');
            // Remove parameters from URL without reload
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        } else if (flash === 'updated' && name) {
            showFlashMessage(`Worker opening "${name}" updated successfully!`, 'status');
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
    }
    
    // Check URL flash messages on page load
    document.addEventListener('DOMContentLoaded', checkUrlFlashMessages);
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const eventSelect = document.querySelector('select[name="event_id"]');
  const listEl = document.getElementById('accessCodesList');

  const searchEl = document.getElementById('accessCodesSearch');
  const btnAll = document.getElementById('accessCodesSelectAll');
  const btnClear = document.getElementById('accessCodesClearAll');
  const countEl = document.getElementById('accessCodesCount');

  const seedSelectedEl = document.getElementById('accessCodesSelectedSeed');
  const seedEventEl = document.getElementById('accessCodesEventSeed');

  let allCodes = [];        // hasil fetch event
  let currentEventId = '';  // event yang sedang aktif

  const seededSelected = (() => {
    try { return JSON.parse(seedSelectedEl?.value || '[]'); }
    catch { return []; }
  })();

  function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, (m) => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
    }[m]));
  }

  function getCheckedIds() {
    return Array.from(listEl.querySelectorAll('input[name="access_code_ids[]"]:checked'))
      .map(i => Number(i.value))
      .filter(Boolean);
  }

  function setCount() {
    const n = getCheckedIds().length;
    if (countEl) countEl.textContent = `${n} selected`;
  }

  function renderEmpty(msg) {
    listEl.innerHTML = `<p class="text-sm text-gray-500 p-2">${msg}</p>`;
    setCount();
  }

  function filterCodes(q) {
    const s = (q || '').trim().toLowerCase();
    if (!s) return allCodes;
    return allCodes.filter(c => {
      const code = (c.code ?? '').toLowerCase();
      const label = (c.label ?? '').toLowerCase();
      return code.includes(s) || label.includes(s);
    });
  }

  function renderList() {
    const q = searchEl?.value || '';
    const data = filterCodes(q);
    const checkedSet = new Set(getCheckedIds());

    if (!currentEventId) {
      renderEmpty('Pilih event dulu untuk menampilkan daftar kode akses.');
      return;
    }
    if (!allCodes.length) {
      renderEmpty('Event ini belum memiliki kode akses.');
      return;
    }
    if (!data.length) {
      renderEmpty('Tidak ada hasil untuk pencarian ini.');
      return;
    }

    listEl.innerHTML = data.map(c => {
      const checked = checkedSet.has(c.id) ? 'checked' : '';
      return `
        <label class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 cursor-pointer">
          <input
            type="checkbox"
            name="access_code_ids[]"
            value="${c.id}"
            ${checked}
            class="rounded border-gray-300 text-red-600 focus:ring-red-500">
          <span class="px-2 py-0.5 rounded text-xs font-semibold text-white"
                style="background-color:${c.color_hex || '#EF4444'}">
            ${escapeHtml(c.code)}
          </span>
          <span class="text-sm text-gray-700">${escapeHtml(c.label)}</span>
        </label>
      `;
    }).join('');

    // bind checkbox change to update count
    listEl.querySelectorAll('input[name="access_code_ids[]"]').forEach(cb => {
      cb.addEventListener('change', setCount);
    });

    setCount();
  }

  function applySeededChecked() {
    // seed centang hanya yang ada di event ini
    const ids = new Set(allCodes.map(c => c.id));
    const wanted = seededSelected.filter(id => ids.has(id));

    // render dulu semua (tanpa search)
    if (searchEl) searchEl.value = '';
    listEl.innerHTML = allCodes.map(c => {
      const checked = wanted.includes(c.id) ? 'checked' : '';
      return `
        <label class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 cursor-pointer">
          <input
            type="checkbox"
            name="access_code_ids[]"
            value="${c.id}"
            ${checked}
            class="rounded border-gray-300 text-red-600 focus:ring-red-500">
          <span class="px-2 py-0.5 rounded text-xs font-semibold text-white"
                style="background-color:${c.color_hex || '#EF4444'}">
            ${escapeHtml(c.code)}
          </span>
          <span class="text-sm text-gray-700">${escapeHtml(c.label)}</span>
        </label>
      `;
    }).join('');

    listEl.querySelectorAll('input[name="access_code_ids[]"]').forEach(cb => {
      cb.addEventListener('change', setCount);
    });

    setCount();
  }

  async function loadAccessCodes(eventId, preserveChecked = true) {
    currentEventId = String(eventId || '');
    if (!currentEventId) {
      allCodes = [];
      renderEmpty('Pilih event dulu untuk menampilkan daftar kode akses.');
      return;
    }

    const preserved = preserveChecked ? getCheckedIds() : [];

    renderEmpty('Memuat kode akses...');

    try {
      const url = `/admin/events/${currentEventId}/access-codes`;
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('Failed');
      allCodes = await res.json();

      // kalau preserve, centang yang sebelumnya dicentang tapi hanya yg available
      if (preserved.length) {
        const avail = new Set(allCodes.map(c => c.id));
        const keep = new Set(preserved.filter(id => avail.has(id)));

        if (searchEl) searchEl.value = '';
        listEl.innerHTML = allCodes.map(c => {
          const checked = keep.has(c.id) ? 'checked' : '';
          return `
            <label class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 cursor-pointer">
              <input type="checkbox" name="access_code_ids[]" value="${c.id}" ${checked}
                class="rounded border-gray-300 text-red-600 focus:ring-red-500">
              <span class="px-2 py-0.5 rounded text-xs font-semibold text-white"
                style="background-color:${c.color_hex || '#EF4444'}">
                ${escapeHtml(c.code)}
              </span>
              <span class="text-sm text-gray-700">${escapeHtml(c.label)}</span>
            </label>
          `;
        }).join('');

        listEl.querySelectorAll('input[name="access_code_ids[]"]').forEach(cb => {
          cb.addEventListener('change', setCount);
        });

        setCount();
      } else {
        // first load (edit) pakai seed
        applySeededChecked();
      }
    } catch (e) {
      allCodes = [];
      renderEmpty('Gagal memuat kode akses. Coba refresh halaman.');
    }
  }

  // search realtime
  searchEl?.addEventListener('input', () => renderList());

  // select all (yang lagi tampil sesuai search)
  btnAll?.addEventListener('click', () => {
    const q = searchEl?.value || '';
    const visible = filterCodes(q);
    const visibleIds = new Set(visible.map(c => c.id));
    listEl.querySelectorAll('input[name="access_code_ids[]"]').forEach(cb => {
      if (visibleIds.has(Number(cb.value))) cb.checked = true;
    });
    setCount();
  });

  // clear all
  btnClear?.addEventListener('click', () => {
    listEl.querySelectorAll('input[name="access_code_ids[]"]').forEach(cb => cb.checked = false);
    setCount();
  });

  // initial load (edit)
  const initialEventId = (seedEventEl?.value || eventSelect?.value || '').toString();
  if (initialEventId) loadAccessCodes(initialEventId, false);

  // change event realtime
  eventSelect?.addEventListener('change', (e) => {
    // saran: kalau ganti event, akses lama harus dibuang biar ga nyangkut
    // jadi preserveChecked = false
    loadAccessCodes(e.target.value, false);
  });
});
</script>

@endsection