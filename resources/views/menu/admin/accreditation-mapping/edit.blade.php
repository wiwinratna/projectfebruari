@extends('layouts.app')

@section('title', 'Edit Accreditation Mapping - ARISE Admin')
@section('page-title')
Accreditation Mapping <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Mapping</h2>
        </div>
        <a href="{{ route('admin.accreditation-mapping.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form method="POST" action="{{ route('admin.accreditation-mapping.update', $acc->id) }}">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 border-b border-gray-200">
                @include('menu.admin.accreditation-mapping.partials.form-fields', [
                    'mode' => 'edit',
                    'selectedIds' => $selectedIds,
                    'acc' => $acc,
                    'jobCategories' => $jobCategories,
                ])
            </div>

            <div class="px-6 py-4 flex justify-end gap-2">
                <a href="{{ route('admin.accreditation-mapping.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600">
                    Update Mapping
                </button>
            </div>
        </form>
    </div>

</div>
@endsection