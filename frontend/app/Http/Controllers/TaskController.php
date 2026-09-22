<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TaskController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.api.url', 'http://backend:8080');
    }

    public function index(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        try {
            $response = Http::get("{$this->apiUrl}/api/tasks", ['date' => $date]);
            $tasks = $response->json();
        } catch (\Exception $e) {
            $tasks = [];
        }

        return view('tasks.index', compact('tasks', 'date'));
    }

    public function create()
    {
        try {
            $categories = Http::get("{$this->apiUrl}/api/categories")->json();
        } catch (\Exception $e) {
            $categories = [];
        }

        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration' => 'required|integer|min:1',
        ]);

        try {
            Http::post("{$this->apiUrl}/api/tasks", $request->all());
            return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan tugas'])->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $task = Http::get("{$this->apiUrl}/api/tasks/{$id}")->json();
            $categories = Http::get("{$this->apiUrl}/api/categories")->json();
        } catch (\Exception $e) {
            return redirect()->route('tasks.index');
        }

        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:pending,completed',
        ]);

        try {
            Http::put("{$this->apiUrl}/api/tasks/{$id}", $request->all());
            return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengupdate tugas'])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Http::delete("{$this->apiUrl}/api/tasks/{$id}");
            return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus tugas']);
        }
    }

    public function toggle($id)
    {
        try {
            Http::patch("{$this->apiUrl}/api/tasks/{$id}/status");
            return back()->with('success', 'Status tugas berhasil diubah');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengubah status']);
        }
    }
}
