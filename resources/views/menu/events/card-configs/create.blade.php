@extends('layouts.app')

@section('title', 'Tambah Konfigurasi Kartu Akses')
@section('page-title')
Tambah Konfigurasi <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">
    {{ session('admin_event_name', 'Event') }}
</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Konfigurasi Kartu Akses</h2>
            <p class="text-gray-600 mt-1">Pilih accreditation mapping lalu tentukan paket aksesnya</p>
        </div>
        <a href="{{ route('admin.card-configs.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Konfigurasi Kartu Akses</h3>
        </div>

        <form method="POST" action="{{ route('admin.card-configs.store') }}" class="p-6 space-y-6">
            @include('menu.events.card-configs.form')
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/css/tom-select.css">
<style>
  .ts-control{
    border-radius: .5rem !important;
    border-color: #d1d5db !important;
    padding: .6rem .75rem !important;
    box-shadow: none !important;
  }
  .ts-control:focus-within{
    border-color: transparent !important;
    box-shadow: 0 0 0 2px rgba(239,68,68,.35) !important;
  }
  .ts-dropdown{
    border-radius: .5rem !important;
    border-color: #e5e7eb !important;
    overflow: hidden;
  }
  .ts-dropdown .option.active{
    background: rgba(239,68,68,.08) !important;
    color: #111827 !important;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('select.ts-select').forEach((el) => {
    new TomSelect(el, {
      create: false,
      allowEmptyOption: true,
      placeholder: el.querySelector('option[value=""]')?.textContent?.trim() ?? 'Pilih...',
      render: {
        no_results: function(data, escape) {
          return '<div class="p-3 text-sm text-gray-500">Tidak ada hasil untuk: <b>' + escape(data.input) + '</b></div>';
        }
      }
    });
  });

  const setupFilter = (inputId, itemSelector, clearBtnId) => {
    const input = document.getElementById(inputId);
    const clearBtn = document.getElementById(clearBtnId);
    if (!input) return;

    const apply = () => {
      const q = (input.value || '').trim().toLowerCase();
      document.querySelectorAll(itemSelector).forEach(el => {
        const hay = (el.dataset.search || '');
        el.style.display = hay.includes(q) ? '' : 'none';
      });
    };

    input.addEventListener('input', apply);
    if (clearBtn) clearBtn.addEventListener('click', () => { input.value=''; apply(); input.focus(); });
    apply();
  };

  setupFilter('venueSearch', '.venue-item', 'venueClear');
  setupFilter('zoneSearch', '.zone-item', 'zoneClear');
  setupFilter('accommodationSearch', '.accommodation-item', 'accommodationClear');
});
</script>
@endpush
