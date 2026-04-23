@extends('layouts.app')

@section('title', 'Certificates - ' . $event->title)

@section('content')
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Certificate Results</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $event->title }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.certificate-layouts.index') }}"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                    Manage Layouts
                </a>
            </div>
        </div>

        {{-- Status Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-400 font-semibold">Published Certificates</div>
                        <div class="text-3xl font-bold text-emerald-600 mt-1">{{ $publishedCount }}</div>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-400 font-semibold">Cancelled Certificates</div>
                        <div class="text-3xl font-bold text-red-600 mt-1">{{ $cancelledCount }}</div>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-500">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Auto-Publish Call to Action --}}
        @if($canGenerate && $eligibleCount > 0)
            <div class="bg-gradient-to-r from-red-600 to-rose-700 rounded-2xl p-6 text-white shadow-lg shadow-red-200 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <h3 class="text-lg font-bold">Ready to Publish Certificates</h3>
                    <p class="text-red-100 text-sm mt-1">
                        There are <strong>{{ $eligibleCount }}</strong> eligible participants.
                        Click the button to generate and publish certificates for all of them automatically.
                    </p>
                </div>
                <button onclick="confirmAutoPublish()"
                    class="flex-shrink-0 whitespace-nowrap px-6 py-2.5 bg-white text-red-700 font-bold rounded-xl hover:bg-red-50 transition-all shadow-xl text-sm">
                    Publish Certificates
                </button>
            </div>
        @endif

        {{-- Search Area --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.certificates.index') }}" class="flex gap-3 flex-wrap">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="q" value="{{ $q }}" placeholder="Search volunteer name..."
                        class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <select name="status"
                    class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                    <option value="">All Status</option>
                    <option value="published" {{ $statusFilter === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="cancelled" {{ $statusFilter === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit"
                    class="px-6 py-2 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-black transition">
                    Search
                </button>
            </form>
        </div>

        {{-- Results Table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500">
                        <tr>
                            <th class="text-left px-6 py-3.5">Volunteer Name</th>
                            <th class="text-left px-4 py-3.5">Role / Position</th>
                            <th class="text-center px-4 py-3.5">Status</th>
                            <th class="text-right px-6 py-3.5">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($certificates as $cert)
                            @php $payload = $cert->payload ?? []; @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-3.5">
                                    <div class="font-semibold text-gray-900 text-[13px]">{{ $payload['volunteer_name'] ?? '—' }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5 font-mono">{{ $cert->cert_code }}</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="text-gray-600 text-[13px]">{{ $payload['role_label'] ?? '—' }}</div>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($cert->status === 'published')
                                        <span class="inline-flex items-center justify-center min-w-[80px] px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest bg-emerald-50 text-emerald-700">
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center min-w-[80px] px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest bg-red-50 text-red-700">
                                            Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex justify-end items-center gap-2">
                                        @if($cert->status === 'published')
                                            <a href="{{ route('admin.certificates.preview', $cert) }}" target="_blank"
                                                class="inline-flex justify-center items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition w-24">
                                                <i class="fas fa-eye"></i> Preview
                                            </a>
                                        @else
                                            <button disabled
                                                class="inline-flex justify-center items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-400 rounded-lg text-xs font-medium cursor-not-allowed w-24">
                                                <i class="fas fa-eye-slash"></i> Preview
                                            </button>
                                        @endif

                                        @if($cert->status === 'published')
                                            <button
                                                onclick="confirmCancel('{{ $cert->id }}', '{{ addslashes($payload['volunteer_name'] ?? '') }}')"
                                                class="inline-flex justify-center items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100 transition w-24">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        @else
                                            <form action="{{ route('admin.certificates.restore', $cert) }}" method="POST" class="contents">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex justify-center items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-medium hover:bg-emerald-100 transition w-24">
                                                    <i class="fas fa-redo"></i> Restore
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center text-gray-500">
                                    <div
                                        class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                        <i class="fas fa-certificate text-2xl text-gray-300"></i>
                                    </div>
                                    @if($eligibleCount > 0)
                                        Ready to publish certificates. Use the button above to start.
                                    @else
                                        No certificates found for this event.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Confirmation Modal for Cancellation --}}
    <div id="cancel-modal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Cancel certificate?</h3>
                <p class="text-sm text-gray-500">
                    Certificate for <strong id="cancel-name" class="text-gray-900"></strong> will be cancelled and no longer
                    considered active. Are you sure you want to proceed?
                </p>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex gap-3">
                <button onclick="closeCancelModal()"
                    class="flex-1 px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-bold text-gray-700 hover:bg-gray-50 text-sm transition font-medium">
                    No
                </button>
                <form id="cancel-form" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-600 rounded-lg font-bold text-white hover:bg-red-700 text-sm transition-all shadow-lg shadow-red-100">
                        Yes, Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Auto-Publish Modal --}}
    <div id="publish-modal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-paper-plane text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Publish Certificates?</h3>
                <p class="text-gray-500 text-sm">
                    System will automatically generate and publish certificates for <strong>{{ $eligibleCount }}</strong>
                    eligible participants.
                </p>
                <div id="publish-status" class="hidden mt-4 p-3 rounded-xl text-sm font-medium"></div>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button id="btn-publish-cancel" onclick="closePublishModal()"
                    class="flex-1 px-4 py-2.5 bg-white border border-gray-300 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition">
                    Wait
                </button>
                <button id="btn-publish-confirm" onclick="executeAutoPublish()"
                    class="flex-1 px-4 py-2.5 bg-red-600 rounded-xl font-bold text-white hover:bg-red-700 transition shadow-lg shadow-red-100">
                    Yes, Publish
                </button>
            </div>
        </div>
    </div>

    <script>
        function confirmCancel(id, name) {
            document.getElementById('cancel-name').textContent = name;
            document.getElementById('cancel-form').action = `/admin/certificates/${id}/cancel`;
            document.getElementById('cancel-modal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancel-modal').classList.add('hidden');
        }

        function confirmAutoPublish() {
            document.getElementById('publish-modal').classList.remove('hidden');
        }

        function closePublishModal() {
            if (!document.getElementById('btn-publish-confirm').disabled) {
                document.getElementById('publish-modal').classList.add('hidden');
            }
        }

        function executeAutoPublish() {
            const btn = document.getElementById('btn-publish-confirm');
            const cancelBtn = document.getElementById('btn-publish-cancel');
            const status = document.getElementById('publish-status');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Publishing...';
            cancelBtn.classList.add('hidden');

            fetch('{{ route("admin.certificates.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({}),
            })
                .then(r => r.json())
                .then(data => {
                    status.classList.remove('hidden');
                    if (data.success) {
                        status.className = 'mt-6 p-4 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200';
                        status.textContent = data.message;
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        status.className = 'mt-6 p-4 rounded-xl text-sm font-bold bg-red-50 text-red-700 border border-red-200';
                        status.textContent = data.error || 'Failed to publish.';
                        btn.disabled = false;
                        btn.textContent = 'Yes, Publish';
                        cancelBtn.classList.remove('hidden');
                    }
                })
                .catch(() => {
                    status.classList.remove('hidden');
                    status.className = 'mt-6 p-4 rounded-xl text-sm font-bold bg-red-50 text-red-700 border border-red-200';
                    status.textContent = 'Network error.';
                    btn.disabled = false;
                    btn.textContent = 'Yes, Publish';
                    cancelBtn.classList.remove('hidden');
                });
        }

        // Modal escapes
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeCancelModal();
                closePublishModal();
            }
        });

        // Outside click
        document.getElementById('cancel-modal').addEventListener('click', (e) => {
            if (e.target.id === 'cancel-modal') closeCancelModal();
        });
    </script>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out;
        }
    </style>
@endsection