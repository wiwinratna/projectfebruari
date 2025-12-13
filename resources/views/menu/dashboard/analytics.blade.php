@extends('layouts.app')

@section('title', 'Analytics Dashboard - NOCIS')
@section('page-title')
    Analytics Dashboard <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-8">
    {{-- Header & Filter --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 mb-2">Event Analytics</h1>
            <p class="text-gray-500">Analyze application performance and recruitment insights.</p>
        </div>
        <div class="w-full md:w-auto">
            <form action="{{ route('admin.analytics') }}" method="GET" class="flex items-center gap-2">
                <select name="event_id" onchange="this.form.submit()" class="w-full md:w-64 rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    @foreach($allEvents as $evt)
                        <option value="{{ $evt->id }}" {{ $selectedEvent && $selectedEvent->id == $evt->id ? 'selected' : '' }}>
                            {{ $evt->title }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @if($selectedEvent)
        {{-- Event Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-[#FBDAD4] mr-4 flex items-center justify-center text-red-500">
                    <i class="fas fa-users text-lg"></i>
                </div>
                <div>
                    <p class="text-3xl font-semibold text-gray-900">{{ $eventAnalytics['total_applications'] }}</p>
                    <p class="text-gray-500 text-sm">Total Applications</p>
                </div>
            </div>
            
            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-[#FBDAD4] mr-4 flex items-center justify-center text-red-500">
                    <i class="fas fa-briefcase text-lg"></i>
                </div>
                <div>
                    <p class="text-3xl font-semibold text-gray-900">{{ count($eventAnalytics['top_positions']) }}</p>
                    <p class="text-gray-500 text-sm">Active Roles</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-[#FBDAD4] mr-4 flex items-center justify-center text-red-500">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
                <div>
                    <p class="text-3xl font-semibold text-gray-900">{{ $eventAnalytics['total_filled'] }}</p>
                    <p class="text-gray-500 text-sm">Positions Filled</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-[#FBDAD4] mr-4 flex items-center justify-center text-red-500">
                    <i class="fas fa-percentage text-lg"></i>
                </div>
                <div>
                    @php
                        $utilization = $eventAnalytics['total_slots'] > 0 ? round(($eventAnalytics['total_filled'] / $eventAnalytics['total_slots']) * 100, 1) : 0;
                    @endphp
                    <p class="text-3xl font-semibold text-gray-900">{{ $utilization }}%</p>
                    <p class="text-gray-500 text-sm">Fulfillment Rate</p>
                </div>
            </div>
        </div>

        {{-- Deep Insights Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Positions Chart --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                            <i class="fas fa-trophy mr-2"></i> Top Jobs
                        </p>
                        <h3 class="text-xl font-semibold text-gray-900">Most Popular Positions</h3>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="topPositionsChart"></canvas>
                </div>
            </div>

            {{-- Daily Trends Chart --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                            <i class="fas fa-chart-line mr-2"></i> Trends
                        </p>
                        <h3 class="text-xl font-semibold text-gray-900">Daily Applications</h3>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="dailyTrendsChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Category Breakdown Row --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-layer-group mr-2"></i> Categories
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Applications by Category</h3>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="h-64">
                    <canvas id="categoriesChart"></canvas>
                </div>
                <div class="space-y-3 max-h-64 overflow-y-auto pr-2">
                    @foreach($eventAnalytics['categories'] as $cat)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <span class="font-medium text-gray-700">{{ $cat->category_name }}</span>
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $cat->application_count }} Apps</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    @else
        <div class="text-center py-20 bg-white rounded-3xl border border-gray-100">
            <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900">No Event Selected</h3>
            <p class="text-gray-500 mt-2">Please select an event from the dropdown above to view analytics.</p>
        </div>
    @endif
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const colors = ['#EF4444', '#F97316', '#F59E0B', '#EAB308', '#84CC16', '#10B981', '#06B6D4', '#3B82F6', '#6366F1', '#8B5CF6'];

    @if($selectedEvent)
        // 1. Top Positions Bar Chart
        const topPosData = @json($eventAnalytics['top_positions']);
        new Chart(document.getElementById('topPositionsChart'), {
            type: 'bar',
            data: {
                labels: topPosData.map(d => d.job_title),
                datasets: [{
                    label: 'Applications',
                    data: topPosData.map(d => d.application_count),
                    backgroundColor: colors.slice(0, 5),
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { ticks: { autoSkip: false, maxRotation: 45, minRotation: 45 } }
                }
            }
        });

        // 2. Daily Trends Line Chart
        const trendData = @json($eventAnalytics['daily_trends']);
        new Chart(document.getElementById('dailyTrendsChart'), {
            type: 'line',
            data: {
                labels: trendData.map(d => d.date),
                datasets: [{
                    label: 'New Applications',
                    data: trendData.map(d => d.count),
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // 3. Categories Doughnut Chart
        const catData = @json($eventAnalytics['categories']);
        new Chart(document.getElementById('categoriesChart'), {
            type: 'doughnut',
            data: {
                labels: catData.map(d => d.category_name),
                datasets: [{
                    data: catData.map(d => d.application_count),
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { position: 'right', labels: { usePointStyle: true, padding: 20 } } 
                },
                cutout: '60%'
            }
        });
    @endif
</script>
@endsection