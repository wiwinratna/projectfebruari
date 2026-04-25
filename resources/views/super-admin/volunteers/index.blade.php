@extends('layouts.app')

@section('title', 'Manage Volunteers - NOCIS')
@section('page-title')
    Manage Volunteers <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-8">

    {{-- Greeting + Stat Cards (sama persis style dashboard) --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h1 class="text-2xl font-semibold text-gray-900 mb-2">Volunteer Overview</h1>
        <p class="text-gray-500 mb-6">Ringkasan data volunteer, pendaftaran, dan statistik profil.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="flex items-center p-4 bg-[#F0F4FF] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#DBEAFE] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $totalVolunteers }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Total Volunteer</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-[#F0FDF4] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#D1FAE5] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-id-card text-emerald-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $withProfile }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Profil Lengkap</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-paper-plane text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $withApplications }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Sudah Melamar</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-[#FFFBEB] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FEF3C7] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-file-alt text-amber-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $totalApplications }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Total Lamaran</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-[#F5F3FF] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#EDE9FE] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-check-double text-violet-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $acceptedRate }}%</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Accepted Rate</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row 1: Trend + Per Event --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Tren Pendaftaran --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-blue-600 font-semibold flex items-center">
                        <i class="fas fa-chart-line mr-2"></i> Tren Pendaftaran
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Volunteer Baru</h3>
                </div>
                <span class="text-xs text-gray-400">6 bulan terakhir</span>
            </div>
            <div style="height:240px"><canvas id="chartRegistration"></canvas></div>
        </div>

        {{-- Lamaran per Event --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-calendar-check mr-2"></i> Lamaran per Event
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Breakdown Status</h3>
                </div>
                <span class="text-xs text-gray-400">stacked bar</span>
            </div>
            <div style="height:240px"><canvas id="chartPerEvent"></canvas></div>
        </div>
    </div>

    {{-- Charts Row 2: Domisili + Profile + Status --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Domisili --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="mb-4">
                <p class="text-sm uppercase tracking-wide text-blue-600 font-semibold flex items-center">
                    <i class="fas fa-map-marker-alt mr-2"></i> Distribusi Domisili
                </p>
                <p class="text-xs text-gray-400 mt-1">Berdasarkan alamat volunteer</p>
            </div>
            <div style="height:220px"><canvas id="chartDomicile"></canvas></div>
        </div>

        {{-- Profile Completion --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="mb-4">
                <p class="text-sm uppercase tracking-wide text-emerald-600 font-semibold flex items-center">
                    <i class="fas fa-user-check mr-2"></i> Profile Completion
                </p>
                <p class="text-xs text-gray-400 mt-1">Distribusi kelengkapan profil</p>
            </div>
            <div class="flex items-center gap-5 mt-2">
                <div style="width:140px;height:140px;flex-shrink:0"><canvas id="chartProfile"></canvas></div>
                <div class="space-y-3 flex-1">
                    @php $pColors=['#EF4444','#F59E0B','#3B82F6','#10B981']; $pi=0; @endphp
                    @foreach($profileDistribution as $range => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background:{{ $pColors[$pi++] }}"></span>
                                <span class="text-sm text-gray-600">{{ $range }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Status Lamaran --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="mb-4">
                <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                    <i class="fas fa-chart-pie mr-2"></i> Status Lamaran
                </p>
                <p class="text-xs text-gray-400 mt-1">Semua lamaran volunteer</p>
            </div>
            <div class="flex items-center gap-5 mt-2">
                <div style="width:140px;height:140px;flex-shrink:0"><canvas id="chartAppStatus"></canvas></div>
                <div class="space-y-3 flex-1">
                    @php $sMap=['pending'=>['Pending','#F59E0B'],'accepted'=>['Accepted','#10B981'],'rejected'=>['Rejected','#EF4444'],'reviewed'=>['Reviewed','#3B82F6']]; @endphp
                    @foreach($sMap as $k=>$m)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background:{{ $m[1] }}"></span>
                                <span class="text-sm text-gray-600">{{ $m[0] }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $appStatusData[$k] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Education Level --}}
    @if($educationData->isNotEmpty())
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm uppercase tracking-wide text-blue-600 font-semibold flex items-center">
                    <i class="fas fa-graduation-cap mr-2"></i> Tingkat Pendidikan
                </p>
                <h3 class="text-xl font-semibold text-gray-900">Pendidikan Terakhir</h3>
            </div>
        </div>
        <div style="height:180px"><canvas id="chartEducation"></canvas></div>
    </div>
    @endif

    {{-- Search & Filter --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('super-admin.volunteers.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau username..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
            </div>
            <div class="flex gap-2 flex-wrap">
                <select name="filter" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm bg-white">
                    <option value="">Semua Profil</option>
                    <option value="complete" @selected(request('filter')==='complete')>Profil Lengkap</option>
                    <option value="incomplete" @selected(request('filter')==='incomplete')>Profil Belum Lengkap</option>
                </select>
                <select name="sort" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm bg-white">
                    <option value="latest" @selected(request('sort')==='latest')>Terbaru</option>
                    <option value="oldest" @selected(request('sort')==='oldest')>Terlama</option>
                    <option value="name" @selected(request('sort')==='name')>Nama A-Z</option>
                </select>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors text-sm">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                @if(request()->hasAny(['search','filter','sort']))
                    <a href="{{ route('super-admin.volunteers.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors text-sm flex items-center">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
        </div>
    @endif

    {{-- Volunteer Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Volunteer <span class="text-sm font-normal text-gray-500 ml-1">({{ $volunteers->total() }} data)</span></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volunteer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lamaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($volunteers as $volunteer)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-[#DBEAFE] flex items-center justify-center mr-3 flex-shrink-0 overflow-hidden">
                                        @if($volunteer->profile?->profile_photo)
                                            <img src="{{ asset('storage/' . $volunteer->profile->profile_photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-blue-700 font-semibold text-sm">{{ strtoupper(substr($volunteer->name,0,2)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $volunteer->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $volunteer->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-600">{{ $volunteer->email }}</p>
                                <p class="text-xs text-gray-500">{{ $volunteer->profile?->phone ?? '—' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php $c = $volunteer->profile_completion; @endphp
                                <div class="flex items-center space-x-2">
                                    <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all {{ $c>=80?'bg-emerald-500':($c>=50?'bg-yellow-500':'bg-red-400') }}" style="width:{{ $c }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium {{ $c>=80?'text-emerald-600':($c>=50?'text-yellow-600':'text-red-500') }}">{{ $c }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php $ac = $volunteer->applications->count(); @endphp
                                @if($ac > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ $ac }} lamaran
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                        Belum ada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $volunteer->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('super-admin.volunteers.show', $volunteer) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('super-admin.volunteers.edit', $volunteer) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('super-admin.volunteers.delete', $volunteer) }}" class="inline-block" onsubmit="return confirm('Hapus volunteer ini? Aksi ini tidak bisa dibatalkan.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p class="text-sm">Tidak ada volunteer ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($volunteers->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $volunteers->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
    const gridClr='rgba(0,0,0,.05)';

    // 1. Registration Trend
    new Chart(document.getElementById('chartRegistration'),{type:'line',data:{
        labels:{!! json_encode($registrationTrend->pluck('label')) !!},
        datasets:[{label:'Volunteer Baru',data:{!! json_encode($registrationTrend->pluck('count')) !!},
            borderColor:'#3B82F6',backgroundColor:'rgba(59,130,246,.08)',borderWidth:2.5,fill:true,tension:.4,
            pointRadius:5,pointBackgroundColor:'#fff',pointBorderColor:'#3B82F6',pointBorderWidth:2,pointHoverRadius:7}]
    },options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},
        scales:{y:{beginAtZero:true,ticks:{stepSize:1,precision:0},grid:{color:gridClr}},x:{grid:{display:false}}}}});

    // 2. Per Event (stacked)
    @if($perEventData->isNotEmpty())
    new Chart(document.getElementById('chartPerEvent'),{type:'bar',data:{
        labels:{!! json_encode($perEventData->pluck('title')) !!},
        datasets:[
            {label:'Accepted',data:{!! json_encode($perEventData->pluck('accepted')) !!},backgroundColor:'#10B981',borderRadius:3,barPercentage:.55},
            {label:'Pending',data:{!! json_encode($perEventData->pluck('pending')) !!},backgroundColor:'#F59E0B',borderRadius:3,barPercentage:.55},
            {label:'Rejected',data:{!! json_encode($perEventData->pluck('rejected')) !!},backgroundColor:'#EF4444',borderRadius:3,barPercentage:.55}
        ]
    },options:{responsive:true,maintainAspectRatio:false,
        plugins:{legend:{position:'bottom',labels:{boxWidth:10,padding:15,font:{size:11}}}},
        scales:{x:{stacked:true,grid:{display:false}},y:{stacked:true,beginAtZero:true,ticks:{stepSize:1,precision:0},grid:{color:gridClr}}}}});
    @else
    document.getElementById('chartPerEvent').parentElement.innerHTML='<div class="flex flex-col items-center justify-center h-full text-gray-400"><i class="fas fa-inbox text-3xl mb-2"></i><p class="text-sm">Belum ada data</p></div>';
    @endif

    // 3. Domicile (horizontal)
    new Chart(document.getElementById('chartDomicile'),{type:'bar',data:{
        labels:{!! json_encode($domicileData->keys()->values()) !!},
        datasets:[{data:{!! json_encode($domicileData->values()->values()) !!},
            backgroundColor:['#3B82F6','#6366F1','#8B5CF6','#A855F7','#D946EF','#EC4899','#F59E0B','#10B981','#94A3B8'],borderRadius:4,barPercentage:.6}]
    },options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},
        scales:{x:{beginAtZero:true,ticks:{stepSize:1,precision:0},grid:{color:gridClr}},y:{grid:{display:false}}}}});

    // 4. Profile (doughnut)
    new Chart(document.getElementById('chartProfile'),{type:'doughnut',data:{
        labels:{!! json_encode(array_keys($profileDistribution)) !!},
        datasets:[{data:{!! json_encode(array_values($profileDistribution)) !!},backgroundColor:['#EF4444','#F59E0B','#3B82F6','#10B981'],borderWidth:0,hoverOffset:6}]
    },options:{responsive:true,maintainAspectRatio:false,cutout:'65%',plugins:{legend:{display:false}}}});

    // 5. App Status (doughnut)
    new Chart(document.getElementById('chartAppStatus'),{type:'doughnut',data:{
        labels:['Pending','Accepted','Rejected','Reviewed'],
        datasets:[{data:[{{ $appStatusData['pending']??0 }},{{ $appStatusData['accepted']??0 }},{{ $appStatusData['rejected']??0 }},{{ $appStatusData['reviewed']??0 }}],
            backgroundColor:['#F59E0B','#10B981','#EF4444','#3B82F6'],borderWidth:0,hoverOffset:6}]
    },options:{responsive:true,maintainAspectRatio:false,cutout:'65%',plugins:{legend:{display:false}}}});

    // 6. Education (bar)
    @if($educationData->isNotEmpty())
    new Chart(document.getElementById('chartEducation'),{type:'bar',data:{
        labels:{!! json_encode($educationData->keys()->values()) !!},
        datasets:[{data:{!! json_encode($educationData->values()->values()) !!},
            backgroundColor:['#3B82F6','#6366F1','#8B5CF6','#10B981','#F59E0B','#EF4444','#EC4899'],borderRadius:6,barPercentage:.4}]
    },options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},
        scales:{y:{beginAtZero:true,ticks:{stepSize:1,precision:0},grid:{color:gridClr}},x:{grid:{display:false}}}}});
    @endif
});
</script>
@endpush
