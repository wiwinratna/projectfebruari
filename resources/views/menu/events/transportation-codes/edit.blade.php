@extends('layouts.app')

@section('title', 'Edit Kode Transportasi - ' . $event->title)
@section('page-title')
Edit Kode Transportasi <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Transportation Code</h2>
            <p class="text-gray-600 mt-1">Update transportation code information</p>
        </div>
        <a href="{{ route('admin.master-data.transportation-codes.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Transportation Code Information</h3>
        </div>

        <form action="{{ route('admin.master-data.transportation-codes.update', $transportationCode) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Row 1 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="kode" name="kode"
                        value="{{ old('kode', $transportationCode->kode) }}"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               @error('kode') border-red-500 @enderror"
                        placeholder="e.g., T2" required>
                    @error('kode') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-sm mt-1">Unique code identifier shown on the card (if enabled).</p>
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <input type="text" id="keterangan" name="keterangan"
                        value="{{ old('keterangan', $transportationCode->keterangan) }}" maxlength="1000"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                               @error('keterangan') border-red-500 @enderror"
                        placeholder="e.g., City shuttle (hotel ↔ venue)">
                    @error('keterangan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-sm mt-1">Short explanation for admins.</p>
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="icon_key" class="block text-sm font-medium text-gray-700 mb-2">Icon (optional)</label>

                    <select id="icon_key" name="icon_key"
                        class="ts-select w-full @error('icon_key') border-red-500 @enderror">
                        <option value=""></option>
                        @foreach(config('icon_catalog') as $key => $label)
                            <option value="{{ $key }}"
                                @selected(old('icon_key', $transportationCode->icon_key) === $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    @error('icon_key') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-sm mt-1">
                        If set, the card can display an icon. If not set, the system can fall back to showing the code.
                    </p>

                    {{-- Tiny preview --}}
                    <div class="mt-3 text-sm text-gray-700 flex items-center gap-2">
                        <span class="text-gray-500">Preview:</span>
                        <span id="transport-preview" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-50 border border-gray-200">
                            <span class="text-gray-400">—</span>
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display</label>

                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="show_icon" value="1"
                                class="rounded border-gray-300 text-red-500 focus:ring-red-500"
                                @checked(old('show_icon', $transportationCode->show_icon ?? 1) == 1)>
                            <span class="text-sm text-gray-700">Show icon</span>
                        </label>

                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="show_code" value="1"
                                class="rounded border-gray-300 text-red-500 focus:ring-red-500"
                                @checked(old('show_code', $transportationCode->show_code ?? 1) == 1)>
                            <span class="text-sm text-gray-700">Show code</span>
                        </label>
                    </div>

                    <p class="text-gray-500 text-sm mt-2">
                        Tip: Most events use icon + code. If icon is missing, code is used.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.master-data.transportation-codes.index') }}"
                   class="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Update
                </button>
            </div>
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
  .ts-control .ts-placeholder { color: #9ca3af !important; } /* gray-400 */
  .ts-dropdown{
    border-radius: .5rem !important;
    border-color: #e5e7eb !important;
    overflow: hidden;
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
      placeholder: 'Search icon…',
      render: {
        no_results: function (data, escape) {
          return '<div class="p-3 text-sm text-gray-500">No results for: <b>' + escape(data.input) + '</b></div>';
        }
      }
    });
  });

  const iconSelect = document.getElementById('icon_key');
  const codeInput  = document.getElementById('kode');
  const showIcon   = document.querySelector('input[name="show_icon"]');
  const showCode   = document.querySelector('input[name="show_code"]');
  const preview    = document.getElementById('transport-preview');

  function renderPreview(){
    const icon = iconSelect?.value || '';
    const code = (codeInput?.value || '').trim();
    const canIcon = showIcon?.checked;
    const canCode = showCode?.checked;

    preview.innerHTML = '';

    const wrap = (html) => {
      const span = document.createElement('span');
      span.innerHTML = html;
      return span;
    };

    if (icon && canIcon) {
    const url = `{{ url('/admin/icon-svg-inline') }}/${encodeURIComponent(icon)}`;

    fetch(url)
        .then(r => r.ok ? r.text() : Promise.reject())
        .then(svgHtml => {
        preview.innerHTML = '';
        preview.appendChild(wrap(svgHtml));

        if (canCode && code) preview.appendChild(wrap(`<span class="text-gray-700">${code}</span>`));
        if (canCode && !code) preview.appendChild(wrap(`<span class="text-gray-400">(no code)</span>`));
        })
        .catch(() => {
        preview.innerHTML = '<span class="text-gray-400">—</span>';
        });

    return;
    }

    if (canCode && code) {
      preview.appendChild(wrap(`<span class="text-gray-700 font-medium">${code}</span>`));
      return;
    }

    preview.appendChild(wrap(`<span class="text-gray-400">—</span>`));
  }

  [iconSelect, codeInput, showIcon, showCode].forEach(el => {
    if (!el) return;
    el.addEventListener('change', renderPreview);
    el.addEventListener('input', renderPreview);
  });

  renderPreview();
});
</script>
@endpush