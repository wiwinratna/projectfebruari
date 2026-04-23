@extends('layouts.app')

@section('title', 'Manage Certificate Layouts')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Certificate Layouts</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $event->title }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.certificate-layouts.builder') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                + Create / Edit Draft
            </a>
            <a href="{{ route('admin.certificates.index') }}"
               class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                Certificate List
            </a>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
        <strong>Layout Rules:</strong> Layouts with <strong>Draft</strong> status can be edited in the builder.
        Once <strong>Published</strong>, the layout is locked and cannot be changed to ensure certificate integrity. 
        To modify a design, duplicate an existing layout into a new draft.
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-sm font-medium text-emerald-700">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">All Layouts</h2>
            <span class="text-xs font-semibold text-gray-400">{{ $layouts->count() }} layouts</span>
        </div>

        @if($layouts->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <i class="fas fa-drafting-compass text-2xl text-gray-300"></i>
                </div>
                <p class="text-gray-400 text-sm">No layouts found. Create your first layout in the builder.</p>
                <a href="{{ route('admin.certificate-layouts.builder') }}"
                   class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition">
                    Open Builder
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($layouts as $layout)
                    <div class="px-6 py-5 flex items-center gap-4 hover:bg-gray-50/50 transition">
                        {{-- Status badge --}}
                        <div class="flex-shrink-0">
                            @if($layout->isLocked())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <i class="fas fa-lock text-[8px]"></i>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Draft
                                </span>
                            @endif
                        </div>

                        {{-- Active indicator --}}
                        @if($layout->is_active)
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[10px] font-bold bg-blue-600 text-white shadow-lg shadow-blue-100">
                                    Active
                                </span>
                            </div>
                        @endif

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 truncate">{{ $layout->name }}</div>
                            <div class="text-[10px] uppercase tracking-widest font-black text-gray-400 mt-1">
                                Version {{ $layout->version }} &bull; Created {{ $layout->created_at->format('d M Y') }}
                                @if($layout->duplicated_from)
                                    &bull; Source: #{{ $layout->duplicated_from }}
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex-shrink-0 flex gap-2">
                            @if($layout->isLocked())
                                {{-- Unpublish button --}}
                                <form method="POST" action="{{ route('admin.certificate-layouts.unpublish', $layout) }}"
                                      onsubmit="return confirm('Revert this layout to draft? You will be able to edit it again.')">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-white border border-amber-200 text-amber-600 rounded-lg text-xs font-semibold hover:bg-amber-50 transition shadow-sm">
                                        Back to Draft
                                    </button>
                                </form>
                            @else
                                {{-- Publish button --}}
                                <form method="POST" action="{{ route('admin.certificate-layouts.publish', $layout) }}"
                                      onsubmit="return confirm('Publish this layout? It will be locked and cannot be changed.')">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition shadow-sm">
                                        Publish
                                    </button>
                                </form>

                                <a href="{{ route('admin.certificate-layouts.builder') }}"
                                   class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition shadow-sm">
                                    Edit
                                </a>
                            @endif

                            {{-- Duplicate button --}}
                            <form method="POST" action="{{ route('admin.certificate-layouts.duplicate', $layout) }}">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition shadow-sm">
                                    Duplicate
                                </button>
                            </form>

                            <a href="{{ route('admin.certificate-layouts.preview-sample') }}" target="_blank"
                               class="px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition shadow-sm">
                                Preview
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
