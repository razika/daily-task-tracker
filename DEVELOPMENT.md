# DailyTask Tracker - Panduan Pengembangan

## Daftar Isi

1. [Struktur Project](#struktur-project)
2. [Git Branching Workflow](#git-branching-workflow)
3. [Environment Management](#environment-management)
4. [Development Workflow](#development-workflow)
5. [Deployment Guide](#deployment-guide)
6. [Database Migration](#database-migration)
7. [Troubleshooting](#troubleshooting)

---

## Struktur Project

```
daily-task-tracker/
├── frontend/              # Laravel 11 (PHP 8.3)
│   ├── app/Http/Controllers/
│   ├── resources/views/
│   ├── routes/
│   └── Dockerfile
├── backend/               # Python FastAPI
│   ├── app/
│   ├── requirements.txt
│   └── Dockerfile
├── database/
│   └── init.sql           # Schema PostgreSQL
├── podman-compose.yml     # Container orchestration
├── .gitignore
└── README.md
```

## Tech Stack

| Layer | Technology | Port |
|-------|------------|------|
| Frontend | Laravel 11 + Blade + Tailwind CSS | 8000 |
| Backend | Python FastAPI | 8080 |
| Database | PostgreSQL 16 | 5432 |
| Container | Podman Compose | - |

---

## Git Branching Workflow

### Branch Structure

```
main ← Production (stable, tested)
└── develop ← Development (integration branch)
    └── feature/nama-fitur ← Individual features
```

### Branch Rules

| Branch | Tujuan | Siapa yang Push |
|--------|--------|-----------------|
| `main` | Production code | Merge dari develop saja |
| `develop` | Testing & integration | Merge dari feature branch |
| `feature/*` | Pengembangan fitur | Developer |

### Commands

```bash
# Mulai fitur baru
git checkout develop
git pull origin develop
git checkout -b feature/nama-fitur

# Commit perubahan
git add .
git commit -m "feat: deskripsi perubahan"

# Push feature branch
git push origin feature/nama-fitur

# Merge ke develop
git checkout develop
git merge feature/nama-fitur --no-ff -m "Merge feature/nama-fitur into develop"
git push origin develop

# Merge ke main (production)
git checkout main
git merge develop --no-ff -m "Release v1.x.0"
git tag -a v1.x.0 -m "Release v1.x.0"
git push origin main --tags
```

### Commit Message Convention

```
feat:      Penambahan fitur baru
fix:       Perbaikan bug
docs:      Perubahan dokumentasi
style:     Formatting, tidak mempengaruhi kode
refactor:  Refactoring kode
test:      Penambahan/perubahan test
chore:     Maintenance
```

Contoh:
```
feat: tambah fitur laporan mingguan
fix: kalkulasi durasi error untuk overnight task
docs: update README dengan cara install
```

---

## Environment Management

### 3 Environment

| Environment | Branch | Port | Tujuan |
|-------------|--------|------|--------|
| Development | feature/* | 8000 | Aktivitas coding harian |
| Staging | develop | 8001 | Testing sebelum production |
| Production | main | 8000 | Yang live dipakai |

### Jalankan Environment

```bash
# Development (di feature branch)
git checkout feature/nama-fitur
APP_PORT=8000 podman-compose up -d --build

# Staging (di develop branch)
git checkout develop
APP_PORT=8001 podman-compose up -d --build

# Production (di main branch)
git checkout main
APP_PORT=8000 podman-compose up -d --build
```

### Akses URL

| Environment | URL |
|-------------|-----|
| Frontend | http://localhost:{PORT} |
| Backend API | http://localhost:8080/docs |
| Database | localhost:5432 |

---

## Development Workflow

### Step-by-Step Development Baru

#### 1. Setup awal (sekali saja)
```bash
git clone https://github.com/razika/daily-task-tracker.git
cd daily-task-tracker
podman-compose up -d --build
```

#### 2. Mulai fitur baru
```bash
# Pull latest
git checkout develop
git pull origin develop

# Buat feature branch
git checkout -b feature/nama-fitur

# Jalankan untuk development
APP_PORT=8000 podman-compose up -d --build
```

#### 3. Development loop
```bash
# Coding changes...

# Test di local
# Buka http://localhost:8000

# Commit
git add .
git commit -m "feat: deskripsi"

# Push
git push origin feature/nama-fitur
```

#### 4. Selesai fitur
```bash
# Merge ke develop
git checkout develop
git merge feature/nama-fitur --no-ff
git push origin develop

# Test di staging
APP_PORT=8001 podman-compose up -d --build
# Buka http://localhost:8001

# Kalau sudah oke, merge ke main
git checkout main
git merge develop --no-ff -m "Release v1.x.0"
git push origin main --tags

# Deploy production
APP_PORT=8000 podman-compose up -d --build
```

### Daily Ritual

| Kapan | Command |
|-------|---------|
| Mulai kerja | `git pull origin develop` |
| Selesai fitur | `git push origin feature/nama-fitur` |
| Test staging | `APP_PORT=8001 podman-compose up -d` |
| Deploy production | `git pull origin main && podman-compose up -d` |

---

## Deployment Guide

### Local Development

```bash
# Start semua service
podman-compose up -d --build

# Lihat logs
podman-compose logs -f

# Stop semua
podman-compose down

# Restart
podman-compose restart
```

### Production Deployment

```bash
# 1. Pull latest code
git checkout main
git pull origin main

# 2. Build & deploy
podman-compose down
podman-compose up -d --build

# 3. Verify
podman-compose ps
curl http://localhost:8000
```

### Rollback (jika ada masalah)

```bash
# Kembali ke versi sebelumnya
git checkout v1.0.0  # atau versi yang stable

# Deploy versi lama
podman-compose down
podman-compose up -d --build

# Fix masalah, lalu push fix ke main
git checkout main
# ... fix code ...
git commit -m "fix: deskripsi"
git push origin main
```

---

## Database Migration

### Schema Locations

- `database/init.sql` - Schema awal & seed data
- Dijalankan otomatis saat pertama kali `podman-compose up`

### Tambah Kolom Baru (Aman)

```sql
-- 1. Tambah kolom baru (jangan hapus yang lama dulu)
ALTER TABLE tasks ADD COLUMN priority INT DEFAULT 0;

-- 2. Deploy & update kode

-- 3. Setelah semua stable, baru hapus kolom lama (opsional)
-- ALTER TABLE tasks DROP COLUMN old_column;
```

### Best Practices

1. **Jangan hapus kolom langsung** - Tambah baru dulu
2. **Backup sebelum migration** - `pg_dump`
3. **Test di staging dulu** - Sebelum apply ke production
4. **Gunakan nullable** - Untuk kolom baru

---

## Troubleshooting

### Container tidak bisa start

```bash
# Cek logs
podman-compose logs frontend
podman-compose logs backend
podman-compose logs database

# Restart
podman-compose down
podman-compose up -d --build
```

### Port sudah terpakai

```bash
# Cek port yang terpakai
lsof -i :8000
lsof -i :8080
lsof -i :5432

# Ganti port
APP_PORT=8001 podman-compose up -d
```

### Database connection error

```bash
# Cek apakah database sudah ready
podman-compose logs database | grep "ready"

# Restart database
podman-compose restart database
```

### Git conflict

```bash
# Saat merge conflict
git merge feature/xxx
# Edit file yang conflict
git add .
git commit -m "merge: resolve conflict"
```

---

## Useful Commands

### Git

```bash
git status                    # Cek status
git log --oneline --graph    # Lihat history
git branch -a                # Lihat semua branch
git stash                    # Sementara simpan perubahan
git stash pop                # Ambil perubahan
```

### Podman

```bash
podman-compose up -d         # Start containers
podman-compose down          # Stop containers
podman-compose ps            # Lihat status
podman-compose logs -f       # Lihat logs
podman-compose exec frontend bash  # Masuk ke container
```

### Database

```bash
# Masuk ke psql (via container)
podman-compose exec database psql -U admin -d dailytask

# Lihat tables
\dt

# Lihat data
SELECT * FROM tasks;
SELECT * FROM categories;
```

---

## API Reference

### Tasks

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/tasks?date=YYYY-MM-DD | Ambil tugas per tanggal |
| GET | /api/tasks/{id} | Ambil satu tugas |
| POST | /api/tasks | Tambah tugas |
| PUT | /api/tasks/{id} | Update tugas |
| DELETE | /api/tasks/{id} | Hapus tugas |
| PATCH | /api/tasks/{id}/status | Toggle status |

### Categories

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/categories | Ambil semua kategori |
| GET | /api/categories/{id} | Ambil satu kategori |

### Reports

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/reports/summary?date=YYYY-MM-DD | Ringkasan harian |
| GET | /api/reports/weekly?start_date=&end_date= | Laporan mingguan |

---

## Changelog

### v1.0.0 (2026-09-22)
- Initial release
- CRUD Tugas
- Kategori tugas
- Laporan harian & mingguan
- Auto-calculate duration

---

*Last updated: 2026-09-22*
