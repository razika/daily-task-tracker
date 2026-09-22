@extends('layouts.app')

@section('title', 'Edit Tugas - DailyTask Tracker')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center space-x-2 text-slate-500 hover:text-slate-700 text-sm font-medium mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span>Kembali</span>
        </a>
        <h1 class="text-3xl font-bold text-slate-900">Edit Tugas</h1>
        <p class="text-slate-500 mt-1">Update detail tugas</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-8">
        <form action="{{ route('tasks.update', $task['id']) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Judul Tugas</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task['title']) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 placeholder-slate-400 transition-all">
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi <span class="text-slate-400 font-normal">(opsional)</span></label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 placeholder-slate-400 transition-all resize-none">{{ old('description', $task['description'] ?? '') }}</textarea>
            </div>

            <div>
                <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                <select name="category_id" id="category_id"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 transition-all bg-white">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category['id'] }}" {{ old('category_id', $task['category_id'] ?? '') == $category['id'] ? 'selected' : '' }}>
                            {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal</label>
                <input type="date" name="date" id="date" value="{{ old('date', $task['date']) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="block text-sm font-semibold text-slate-700 mb-2">Jam Mulai</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time', substr($task['start_time'], 0, 5)) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 transition-all">
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-semibold text-slate-700 mb-2">Jam Selesai</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time', substr($task['end_time'], 0, 5)) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 transition-all">
                </div>
            </div>

            <div>
                <label for="duration" class="block text-sm font-semibold text-slate-700 mb-2">Durasi <span class="text-slate-400 font-normal">(menit)</span></label>
                <input type="number" name="duration" id="duration" value="{{ old('duration', $task['duration']) }}" min="1" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 transition-all">
            </div>

            <div>
                <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-900 transition-all bg-white">
                    <option value="pending" {{ old('status', $task['status']) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ old('status', $task['status']) === 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('tasks.index') }}" class="px-6 py-3 border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50 font-medium transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium shadow-lg shadow-blue-500/25 transition-all">
                    Update Tugas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('start_time').addEventListener('change', calculateDuration);
    document.getElementById('end_time').addEventListener('change', calculateDuration);

    function calculateDuration() {
        const start = document.getElementById('start_time').value;
        const end = document.getElementById('end_time').value;
        if (start && end) {
            const [sh, sm] = start.split(':').map(Number);
            const [eh, em] = end.split(':').map(Number);
            let duration = (eh * 60 + em) - (sh * 60 + sm);
            if (duration < 0) duration += 24 * 60;
            document.getElementById('duration').value = duration;
        }
    }
</script>
@endsection
