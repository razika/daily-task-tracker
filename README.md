# DailyTask Tracker

Aplikasi pencatatan tugas harian dengan durasi waktu pengerjaan.

## Tech Stack

- **Frontend:** Laravel 11 + Blade + Tailwind CSS
- **Backend:** Python FastAPI
- **Database:** PostgreSQL 16
- **Container:** Podman Compose

## Features

- CRUD Tugas Harian
- Input durasi waktu (jam mulai - jam selesai)
- Kategori tugas (Kerja, Pribadi, Belajar, Lainnya)
- Laporan harian & mingguan

## Quick Start

```bash
# Clone repository
git clone <repo-url>
cd daily-task-tracker

# Jalankan dengan Podman Compose
podman-compose up -d --build

# Akses aplikasi
# Frontend: http://localhost:8000
# Backend API: http://localhost:8080/docs
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/tasks?date=YYYY-MM-DD | Ambil tugas per tanggal |
| POST | /api/tasks | Tambah tugas |
| PUT | /api/tasks/{id} | Update tugas |
| DELETE | /api/tasks/{id} | Hapus tugas |
| GET | /api/categories | Ambil kategori |
| GET | /api/reports/summary | Laporan |

## Development

```bash
# Jalankan dalam mode development
podman-compose up -d

# Lihat logs
podman-compose logs -f

# Stop
podman-compose down
```
