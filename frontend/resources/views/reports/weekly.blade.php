@extends('layouts.app')

@section('title', 'Laporan Mingguan - DailyTask Tracker')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Mingguan</h1>
            <p class="text-gray-500">Ringkasan aktivitas minggu ini</p>
        </div>
        <a href="{{ route('reports.index') }}" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg font-medium">
            Lihat Laporan Harian
        </a>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form action="{{ route('reports.weekly') }}" method="GET" class="flex items-center space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="mt-1 block rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="mt-1 block rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="pt-5">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Total Tugas</div>
            <div class="text-3xl font-bold text-gray-900">{{ $report['total_tasks'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Total Durasi</div>
            <div class="text-3xl font-bold text-blue-600">
                {{ floor($report['total_duration'] / 60) }}j {{ $report['total_duration'] % 60 }}m
            </div>
        </div>
    </div>

    <!-- Daily Breakdown -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Rincian Harian</h2>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selesai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pending</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($report['daily_summaries'] as $day)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($day['date'])->format('d M Y (D)') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $day['total_tasks'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                            {{ $day['completed_tasks'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">
                            {{ $day['pending_tasks'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ floor($day['total_duration'] / 60) }}j {{ $day['total_duration'] % 60 }}m
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada data untuk periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
