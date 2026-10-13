@extends('layouts.app')

@section('title', 'Tambah Job Category - ' . $event->title)
@section('page-title')
    Job Categories <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.job-categories.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-2 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kategori
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Job Category</h2>
            <p class="text-gray-600 text-sm mt-1">Buat posisi atau kategori pekerjaan baru untuk event <strong>{{ $event->title }}</strong></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow max-w-4xl">
        <form method="POST" action="{{ route('admin.job-categories.store') }}" class="space-y-6">
            @csrf
            @include('menu.admin.job-categories.partials.form-fields')

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.job-categories.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600 flex items-center shadow-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
