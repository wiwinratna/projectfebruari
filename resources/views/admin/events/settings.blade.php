@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-2xl py-6 px-4">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Event Settings</h1>
        <p class="text-gray-600">Kelola informasi dan template kartu untuk event Anda</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.event.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Event Title -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Event</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Event
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title', $event->title) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    />
                </div>
            </div>
        </div>

        <!-- Event Logo -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Logo Event</h2>
            
            <div class="space-y-4">
                @if ($event->logo_path)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <img
                                src="{{ asset('storage/' . ltrim($event->logo_path, '/')) }}"
                                alt="Event Logo"
                                class="h-20 w-auto object-contain"
                            />
                            <p class="text-sm text-gray-600 mt-2">Logo saat ini</p>
                        </div>
                        <button
                            type="button"
                            onclick="removeLogo(event)"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition"
                        >
                            Hapus Logo
                        </button>
                    </div>
                @endif

                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Logo (PNG, JPG, WebP - Max 5MB)
                    </label>
                    <input
                        type="file"
                        id="logo"
                        name="logo"
                        accept="image/png,image/jpeg,image/webp"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <p class="text-xs text-gray-500 mt-2">Format: PNG, JPG, JPEG, WebP. Ukuran maksimal 5MB</p>
                </div>
            </div>
        </div>

        <!-- Card Template -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Template Kartu Access</h2>
            
            <div class="space-y-4">
                @if ($event->card_template_path)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <img
                                src="{{ asset('storage/' . ltrim($event->card_template_path, '/')) }}"
                                alt="Card Template"
                                class="h-40 w-auto object-contain rounded border border-gray-300"
                            />
                            <p class="text-sm text-gray-600 mt-2">
                                Template saat ini
                                @if ($event->card_template_updated_at)
                                    <br/>
                                    <span class="text-xs text-gray-500">
                                        Di-upload {{ $event->card_template_updated_at->format('d/m/Y H:i') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <button
                            type="button"
                            onclick="removeTemplate(event)"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition"
                        >
                            Hapus Template
                        </button>
                    </div>
                @endif

                <div>
                    <label for="card_template" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Template Kartu (PNG, JPG, WebP - Max 10MB)
                    </label>
                    <input
                        type="file"
                        id="card_template"
                        name="card_template"
                        accept="image/png,image/jpeg,image/webp"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <p class="text-xs text-gray-500 mt-2">
                        Format: PNG, JPG, JPEG, WebP. Ukuran maksimal 10MB.
                        <br/>
                        <strong>Catatan:</strong> Setelah upload template, Anda akan diarahkan ke Card Builder untuk menyesuaikan posisi elemen pada kartu.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 justify-between">
            <a href="{{ route('admin.card-layouts.builder') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
                Ke Card Builder
            </a>
            <button
                type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"
            >
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
function removeLogo(event) {
    event.preventDefault();
    
    if (!confirm('Yakin ingin menghapus logo event?')) {
        return;
    }

    fetch('{{ route("admin.event.settings.logo.remove") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            location.reload();
        } else if (data.error) {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

function removeTemplate(event) {
    event.preventDefault();
    
    if (!confirm('Yakin ingin menghapus template kartu?')) {
        return;
    }

    fetch('{{ route("admin.event.settings.template.remove") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            location.reload();
        } else if (data.error) {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}
</script>
@endsection
