@extends('layouts.app')

@section('title', 'Daftar Tugas - DailyTask Tracker')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Daftar Tugas</h1>
            <p class="text-slate-500 mt-1">Kelola tugas harian kamu</p>
        </div>
        <div class="flex items-center space-x-3">
            <form action="{{ route('tasks.index') }}" method="GET" class="flex items-center space-x-2">
                <input type="date" name="date" value="{{ $date }}" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                <button type="submit" class="bg-slate-100 hover:bg-slate-200 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">Filter</button>
            </form>
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center space-x-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg shadow-blue-500/25 transition-all text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah</span>
            </a>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-slate-50/80">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Durasi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($tasks as $task)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ $task['status'] === 'completed' ? 'bg-slate-50/30' : '' }}">
                        <td class="px-6 py-4">
                            <form action="{{ route('tasks.toggle', $task['id']) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-all {{ $task['status'] === 'completed' ? 'bg-emerald-500 border-emerald-500' : 'border-slate-300 hover:border-blue-500' }}">
                                    @if($task['status'] === 'completed')
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium {{ $task['status'] === 'completed' ? 'text-slate-400 line-through' : 'text-slate-900' }}">{{ $task['title'] }}</div>
                            @if(!empty($task['description']))
                                <div class="text-sm text-slate-400 mt-0.5">{{ Str::limit($task['description'], 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-1 text-sm text-slate-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ substr($task['start_time'], 0, 5) }} - {{ substr($task['end_time'], 0, 5) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $task['duration'] }} menit
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if(isset($task['category']))
                                <span class="px-2.5 py-1 text-xs font-medium rounded-lg" style="background-color: {{ $task['category']['color'] }}15; color: {{ $task['category']['color'] }}">
                                    {{ $task['category']['name'] }}
                                </span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('tasks.edit', $task['id']) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('tasks.destroy', $task['id']) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <p class="text-slate-500 font-medium">Tidak ada tugas untuk tanggal ini</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
