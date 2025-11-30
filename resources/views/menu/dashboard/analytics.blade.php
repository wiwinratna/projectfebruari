@extends('layouts.app')

@section('title', 'Analytics Dashboard - NOCIS')
@section('page-title')
    Analytics Dashboard <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-8">
    {{-- Page Header --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 mb-2">Analytics Dashboard</h1>
                <p class="text-gray-500">Analisis aplikasi dan performa event berdasarkan kategori posisi</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Terakhir diperbarui</p>
                <p class="text-lg font-semibold text-gray-900">{{ now()->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Overall Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                <i class="fas fa-calendar-alt text-red-500 text-sm"></i>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $overallStats['total_events'] }}</p>
                <p class="text-gray-500 text-xs sm:text-sm">Total Events</p>
            </div>
        </div>
        
        <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                <i class="fas fa-users text-red-500 text-sm"></i>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $overallStats['total_applications'] }}</p>
                <p class="text-gray-500 text-xs sm:text-sm">Total Applications</p>
            </div>
        </div>
        
        <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                <i class="fas fa-bullseye text-red-500 text-sm"></i>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $overallStats['total_slots'] }}</p>
                <p class="text-gray-500 text-xs sm:text-sm">Total Slots</p>
            </div>
        </div>
        
        <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                <i class="fas fa-chart-line text-red-500 text-sm"></i>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $overallStats['average_utilization'] }}%</p>
                <p class="text-gray-500 text-xs sm:text-sm">Avg. Utilization</p>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top Categories Chart --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i> Top Categories
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Kategori Terpopuler</h3>
                </div>
            </div>
            <div class="h-80">
                <canvas id="topCategoriesChart" width="400" height="200"></canvas>
            </div>
        </div>

        {{-- Monthly Trends Chart --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-chart-line mr-2"></i> Trends
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Aplikasi Bulanan</h3>
                </div>
            </div>
            <div class="h-80">
                <canvas id="monthlyTrendsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Event Analytics --}}
    <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-analytics mr-2"></i> Event Analysis
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Analisis Aplikasi per Event</h3>
                </div>
            </div>
            
            <div class="space-y-8">
                @forelse ($eventAnalytics as $index => $eventData)
                    <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $eventData['event']->title }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ $eventData['event']->start_at->format('d M Y') }} - 
                                    {{ $eventData['event']->venue ?? 'Tempat belum ditentukan' }}
                                </p>
                            </div>
                            <div class="mt-2 lg:mt-0 flex space-x-4 text-sm">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                                    {{ $eventData['total_applications'] }} Aplikasi
                                </span>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">
                                    {{ $eventData['total_filled'] }}/{{ $eventData['total_slots'] }} Terisi
                                </span>
                            </div>
                        </div>
                        
                        @if(count($eventData['category_stats']) > 0)
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-3">Aplikasi per Kategori</h5>
                                    <div class="h-64">
                                        <canvas id="eventChart{{ $index }}" width="300" height="150"></canvas>
                                    </div>
                                </div>
                                
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-3">Detail Kategori</h5>
                                    <div class="space-y-2 max-h-64 overflow-y-auto">
                                        @foreach ($eventData['category_stats'] as $category)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $category['category_name'] }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $category['slots_filled'] }}/{{ $category['slots_total'] }} slot • 
                                                        {{ $category['utilization_rate'] }}%利用率
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-semibold text-lg text-gray-900">{{ $category['applications'] }}</p>
                                                    <p class="text-xs text-gray-500">aplikasi</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-chart-bar text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada data aplikasi untuk event ini</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                        <p class="text-lg font-medium text-gray-500">Belum ada event untuk dianalisis</p>
                        <p class="text-sm text-gray-400">Buat event terlebih dahulu untuk melihat analytics</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Color palette
const colors = [
    '#EF4444', '#F97316', '#F59E0B', '#EAB308', '#84CC16', '#22C55E',
    '#10B981', '#14B8A6', '#06B6D4', '#0EA5E9', '#3B82F6', '#6366F1'
];

// Top Categories Chart
const topCategoriesCtx = document.getElementById('topCategoriesChart').getContext('2d');
const topCategoriesData = @json($topCategories);

new Chart(topCategoriesCtx, {
    type: 'doughnut',
    data: {
        labels: topCategoriesData.map(item => item.category_name),
        datasets: [{
            data: topCategoriesData.map(item => item.total_applications),
            backgroundColor: colors.slice(0, topCategoriesData.length),
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});

// Monthly Trends Chart
const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
const monthlyTrendsData = @json($monthlyTrends);

new Chart(monthlyTrendsCtx, {
    type: 'line',
    data: {
        labels: monthlyTrendsData.map(item => item.month),
        datasets: [{
            label: 'Applications',
            data: monthlyTrendsData.map(item => item.applications),
            borderColor: '#EF4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Event-specific charts
@foreach ($eventAnalytics as $index => $eventData)
@if(count($eventData['category_stats']) > 0)
const eventChart{{ $index }}Ctx = document.getElementById('eventChart{{ $index }}').getContext('2d');
const eventChart{{ $index }}Data = @json($eventData['category_stats']);

new Chart(eventChart{{ $index }}Ctx, {
    type: 'bar',
    data: {
        labels: eventChart{{ $index }}Data.map(item => item.category_name),
        datasets: [{
            label: 'Applications',
            data: eventChart{{ $index }}Data.map(item => item.applications),
            backgroundColor: colors.slice(0, eventChart{{ $index }}Data.length),
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            },
            x: {
                ticks: {
                    maxRotation: 45
                }
            }
        }
    }
});
@endif
@endforeach
</script>
@endsection