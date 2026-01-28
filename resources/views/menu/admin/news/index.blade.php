@extends('layouts.app')

@section('title', 'News - NOCIS Admin')
@section('page-title')
    News <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">News Posts</h2>
            <p class="text-gray-600 mt-1">Create and manage news for landing page updates</p>
        </div>
        <a href="{{ route('admin.news.create') }}"
           class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Create News
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-start gap-2">
                <i class="fas fa-check-circle mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">All News</h3>
            <div class="text-sm text-gray-500">
                Total: <span class="font-semibold text-gray-700">{{ $posts->total() }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Title</th>
                        <th class="text-left px-6 py-3 font-semibold">Status</th>
                        <th class="text-left px-6 py-3 font-semibold">Published At</th>
                        <th class="text-left px-6 py-3 font-semibold">Source</th>
                        <th class="text-right px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center">
                                        @if($post->cover_image)
                                            <img src="{{ asset('storage/'.$post->cover_image) }}" class="w-full h-full object-cover" alt="cover">
                                        @else
                                            <i class="fas fa-image text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-900 truncate max-w-[520px]">
                                            {{ $post->title }}
                                        </div>
                                        <div class="text-gray-500 truncate max-w-[520px]">
                                            {{ $post->excerpt ?? Str::limit(strip_tags($post->content ?? ''), 90) }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if($post->is_published)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                        Draft
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d M Y, H:i') : '-' }}
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                <div class="font-semibold">{{ $post->source_name ?? 'NOCIS' }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-[220px]">
                                    {{ $post->source_url ?? '-' }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.news.edit', $post->id) }}"
                                       class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold">
                                        <i class="fas fa-pen mr-1"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.news.destroy', $post->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this news?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg font-semibold border border-red-100">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                No news posts yet. Click <span class="font-semibold">Create News</span> to add one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
