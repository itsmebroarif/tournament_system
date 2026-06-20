# 🏆 Kafeinarts Management Tools — 17an Competition Management System

A web application for managing Indonesian Independence Day ("17an") competitions. Built with Laravel 13, Livewire 4, Tailwind CSS, and Alpine.js.

---

## 📋 Daftar Isi

- [Tentang Project](#tentang-project)
- [Tech Stack](#tech-stack)
- [Fitur Utama](#fitur-utama)
- [Arsitektur Aplikasi](#arsitektur-aplikasi)
- [Database Schema](#database-schema)
- [Struktur Direktori](#struktur-direktori)
- [Alur Data & Cara Kerja](#alur-data--cara-kerja)
- [Instalasi & Setup](#instalasi--setup)
- [Pengembangan & Maintenance](#pengembangan--maintenance)
- [FAQ](#faq)

---

## Tentang Project

Aplikasi ini dibuat untuk mempermudah pengelolaan lomba 17-an di tingkat RT/RW/kompleks. Mulai dari pendaftaran peserta, antrian live, bracket turnamen, hingga generate sertifikat otomatis.

**Brand:** Kafeinarts Management Tools
**Tujuan:** Digitalisasi lomba 17 Agustusan agar lebih tertib, transparan, dan modern.

---

## Tech Stack

| Teknologi | Versi | Fungsi |
|---|---|---|
| **Laravel** | 13.8 | Backend framework (PHP 8.3) |
| **Livewire** | 4.3 | Frontend interaktif tanpa JavaScript framework berat |
| **Tailwind CSS** | 3.1 | Styling utility-first + Dark Mode |
| **Alpine.js** | 3.4 | Interaktivitas ringan (tabs, dark mode toggle) |
| **MySQL** | 8.0 | Database utama |
| **Redis** | — | Caching (session, query cache) |
| **Laravel Queues** | Database driver | Proses berat (generate PDF) dijalankan async |
| **barryvdh/laravel-dompdf** | 3.1 | Generate sertifikat PDF |
| **Laravel Breeze** | 2.4 | Scaffolding auth (Blade + Tailwind) |
| **Vite** | 8.0 | Build tool frontend |

### Kenapa Livewire?

Daripakai Vue/React + REST API, Livewire memungkinkan:
- **State management** langsung di PHP (tanpa API endpoint)
- **Real-time polling** dengan `wire:poll`
- **Multi-step wizard** dengan validasi per-step tanpa reload
- **Zero JavaScript** untuk form kompleks

---

## Fitur Utama

### 1. Halaman Publik (Tidak Perlu Login)

**Lounge Peserta** (`Livewire\Lounge`)
- Refresh otomatis tiap 5 detik (`wire:poll.5s`)
- Menampilkan nama peserta yang sudah mendaftar secara real-time
- Counter jumlah pendaftar dengan animasi pulse

**Kartu Kompetisi** (`Livewire\CompetitionCards`)
- Menampilkan semua lomba yang tersedia dalam bentuk kartu
- Setiap kartu punya **SVG ilustrasi** unik (balap karung, tarik tambang, panjat pinang, makan kerupuk, lomba kelereng, balap bakiak)
- Badge kategori usia (Anak-anak 🟢, Remaja 🟡, Dewasa 🔴)
- Badge tipe (Individu 🔵 / Tim 🟣)

### 2. Wizard Pendaftaran 5 Langkah (`Livewire\RegistrationWizard`)

Data **hanya disimpan ke database** saat langkah terakhir (Review & Submit).

| Langkah | Form | Validasi |
|---|---|---|
| **1** Biodata | Nama, Jenis Kelamin, Tanggal Lahir | Semua required. Usia otomatis kalkulasi kategori |
| **2** Kontak | No HP, Email, Social Media | Minimal HP atau Email harus diisi |
| **3** Pilih Lomba | Radio list kompetisi | Hanya menampilkan lomba sesuai kategori usia |
| **4** Tim (jika "Tim") | Nama Tim + Anggota | Skipped untuk Individu. Tim minimal 1 anggota |
| **5** Review & Submit | Ringkasan semua data | Simpan participant + registration + team_members |

### 3. Admin Dashboard (Login Required — Max 2 Admin)

**Queue / Antrian** (`Livewire\Admin\QueueList`)
- Polling tiap 10 detik
- Filter by kompetisi
- Grup per kompetisi dengan jumlah peserta
- Lihat info tim/individu + waktu daftar

**Tournament Bracket** (`Livewire\Admin\TournamentBracket`)
- Single elimination system
- Generate bracket otomatis (random pairing)
- Input skor -> otomatis tentukan pemenang
- Pemenang otomatis lanjut ke round berikutnya
- Bracket visual dengan kolom per round

**Sertifikat** (`Livewire\Admin\CertificateManager`)
- Generate sertifikat untuk semua peserta (sekali klik)
- 3 template distinct: **Juara 1 🥇** (emas), **Juara 2 🥈** (perak), **Juara 3 🥉** (perunggu)
- Template tambahan untuk peserta biasa
- Generate via **Queue Job** agar tidak blocking
- Download langsung dari dashboard

### 4. Dark Mode

- Toggle di navbar publik maupun admin
- Tersimpan di `localStorage`
- Fallback ke `prefers-color-scheme`
- Zero FOUC (inline script di head)
- Semua komponen punya variant `dark:`

### 5. Keamanan & Batasan

- **Maksimal 2 admin** — registrasi admin diblokir jika sudah ada 2 user
- Admin accounts sudah di-seed: `admin1@kafeinarts.com` dan `admin2@kafeinarts.com` (password: `password`)
- High-traffic optimization: Redis cache + queue untuk PDF

---

## Arsitektur Aplikasi

```
Browser                    Server                    Database
   │                         │                         │
   ├─ Alpine.js (tabs, DM)───┤                         │
   ├─ Livewire ──────────────┤                         │
   │   ├─ Lounge ────────────┤─── Participant::all() ──┤
   │   ├─ CompCards ─────────┤─── Competition::all() ──┤
   │   ├─ RegWizard ─────────┤─── create participant ──┤
   │   │                     │─── create registration ─┤
   │   │                     │─── create team_members ─┤
   │   ├─ Admin/Queue ───────┤─── Registration::with()─┤
   │   ├─ Admin/Bracket ─────┤─── Match CRUD ──────────┤
   │   └─ Admin/Certificates─┤─── dispatch Job ────────┤
   │                         │                         │
   │                         ├─ Queue Worker ──────────┤
   │                         │   GenerateCertificate   │
   │                         │   └─ Dompdf ────────────┤
   │                         │      └─ storage/app/    │
```

### Alur Pendaftaran (Wizard)

```
User                              Livewire                         Database
  │                                  │                                │
  ├─ Isi Step 1 (nama, gender, ttl)──┤                                │
  │                                  │ (state di property, blm saved) │
  ├─ Isi Step 2 (kontak) ───────────┤                                │
  ├─ Pilih Kompetisi (Step 3) ──────┤                                │
  ├─ Isi Tim (Step 4, jika perlu) ──┤                                │
  ├─ Review & Konfirmasi (Step 5) ──┤                                │
  │                                  ├── create participant ─────────┤
  │                                  ├── create registration ────────┤
  │                                  ├── create team_members ────────┤
  │◄── Sukses ──────────────────────┤                                │
```

### Alur Bracket Turnamen

```
Admin                          Livewire                     Database
  │                               │                           │
  ├─ Generate Bracket ────────────┤                           │
  │                               ├── shuffle registration ───┤
  │                               ├── create Round 1 matches ─┤
  │                               │                           │
  ├─ Input Skor Match A ──────────┤                           │
  │                               ├── set winner_registration─┤
  │                               ├── create/match next round─┤
  │                               │                           │
  ├─ Input Skor Match B ──────────┤                           │
  │                               ├── set winner ─────────────┤
  │                               ├── assign to next match ───┤
  │                               │                           │
  ├─ (Repeat hingga Final) ───────┤                           │
  │                               │                           │
  │◄── Juara 1 teridentifikasi ───┤                           │
```

### Alur Generate Sertifikat

```
Admin                          Livewire                    Queue                Storage
  │                               │                          │                    │
  ├─ Klik "Generate All" ─────────┤                          │                    │
  │                               ├── dispatch Job x N ──────┤                    │
  │                               │                          ├─ loadView blade ───┤
  │                               │                          ├─ Dompdf render ────┤
  │                               │                          ├─ save to disk ─────┤
  │◄── Selesai ───────────────────┤                          │                    │
  ├─ Klik "Download" ─────────────┤                          │                    │
  │                               ├── Storage::url() ────────────────────────────┤
  │◄── PDF Siap ──────────────────┤                          │                    │
```

---

## Database Schema

### ERD (Simplified)

```
users
├── id (PK)
├── name
├── email (unique)
├── password
├── role (default: 'admin')
└── timestamps

participants
├── id (PK)
├── name
├── gender (enum: Laki-laki, Perempuan)
├── birth_date (date)
├── phone (nullable)
├── email (nullable)
├── social_media (json, nullable)
└── timestamps

competitions
├── id (PK)
├── name
├── slug (unique)
├── type (enum: individu, tim)
├── age_category (enum: anak-anak, remaja, dewasa)
├── svg_illustration_key (string)
└── timestamps

registrations
├── id (PK)
├── participant_id (FK → participants)
├── competition_id (FK → competitions)
├── team_name (nullable)
├── rank (tinyInteger, nullable: 1/2/3)
├── certificate_path (nullable)
└── timestamps

team_members
├── id (PK)
├── registration_id (FK → registrations, cascade)
├── name
└── timestamps

matches (model: TournamentMatch)
├── id (PK)
├── competition_id (FK → competitions)
├── round (unsignedInteger)
├── team_a_registration_id (FK → registrations, nullable)
├── team_b_registration_id (FK → registrations, nullable)
├── winner_registration_id (FK → registrations, nullable)
└── timestamps
```

### Relasi

```
Participant
  └── hasMany → Registration

Competition
  ├── hasMany → Registration
  └── hasMany → TournamentMatch (matches)

Registration
  ├── belongsTo → Participant
  ├── belongsTo → Competition
  └── hasMany → TeamMember

TeamMember
  └── belongsTo → Registration

TournamentMatch (table: matches)
  ├── belongsTo → Competition
  ├── belongsTo → Registration (as teamA)
  ├── belongsTo → Registration (as teamB)
  └── belongsTo → Registration (as winner)
```

---

## Struktur Direktori

```
C:\laragon\www\ticketing-system\
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Auth/                    ← Breeze auth controllers
│   │           ├── RegisteredUserController.php  ← [2-admin limit di sini]
│   │           └── ...
│   ├── Livewire/                        ← Komponen interaktif
│   │   ├── Lounge.php                   ← Polling daftar peserta
│   │   ├── CompetitionCards.php         ← Kartu kompetisi
│   │   ├── RegistrationWizard.php       ← Wizard 5 langkah
│   │   └── Admin/
│   │       ├── QueueList.php            ← Antrian admin
│   │       ├── TournamentBracket.php    ← Bracket gugur
│   │       └── CertificateManager.php   ← Manajemen sertifikat
│   ├── Jobs/
│   │   └── GenerateCertificate.php      ← Queue job PDF
│   ├── Models/
│   │   ├── User.php                     ← +isAdmin()
│   │   ├── Participant.php
│   │   ├── Competition.php
│   │   ├── Registration.php
│   │   ├── TeamMember.php
│   │   └── TournamentMatch.php          ← table: matches
│   └── Services/
│       └── SvgIllustrations.php         ← 6 SVG inline
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_06_20_000001_create_participants_table.php
│   │   ├── 2026_06_20_000002_create_competitions_table.php
│   │   ├── 2026_06_20_000003_create_registrations_table.php
│   │   ├── 2026_06_20_000004_create_team_members_table.php
│   │   ├── 2026_06_20_000005_create_matches_table.php
│   │   ├── 2026_06_20_000006_add_role_to_users_table.php
│   │   └── 2026_06_20_000007_add_certificate_path_to_registrations_table.php
│   └── seeders/
│       └── DatabaseSeeder.php           ← 2 admin + 6 kompetisi
├── resources/
│   ├── css/app.css
│   ├── js/app.js                        ← Alpine init + dark mode
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php            ← Admin layout (navbar admin)
│       │   ├── guest.blade.php          ← Public layout (navbar publik)
│       │   └── navigation.blade.php     ← Admin navbar
│       ├── livewire/
│       │   ├── lounge.blade.php
│       │   ├── competition-cards.blade.php
│       │   ├── registration-wizard.blade.php
│       │   └── admin/
│       │       ├── queue-list.blade.php
│       │       ├── tournament-bracket.blade.php
│       │       └── certificate-manager.blade.php
│       ├── pdf/
│       │   └── certificate.blade.php    ← Template PDF
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php       ← Register admin
│       │   └── participant-register.blade.php
│       ├── components/                  ← Breeze components
│       ├── dashboard.blade.php          ← Admin dashboard + tabs
│       └── welcome.blade.php            ← Halaman utama
├── routes/
│   ├── web.php                          ← Route utama + /daftar
│   └── auth.php                         ← Breeze routes (login, register admin)
├── tailwind.config.js                   ← +darkMode: 'class'
├── vite.config.js
├── composer.json
└── package.json
```

---

## Instalasi & Setup

### Prasyarat

- PHP 8.3+
- Composer
- Node.js & npm
- MySQL 8.0
- Redis (opsional, untuk production)

### Langkah-langkah

```bash
# 1. Clone project
git clone <repo-url> ticketing-system
cd ticketing-system

# 2. Install dependencies PHP
composer install

# 3. Install dependencies Node
npm install

# 4. Copy environment
cp .env.example .env
# Edit .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD, CACHE_STORE=redis (jika ada Redis)

# 5. Generate app key
php artisan key:generate

# 6. Buat database MySQL
mysql -u root -p -e "CREATE DATABASE ticketing_system"

# 7. Jalankan migrasi
php artisan migrate

# 8. Seed data (2 admin + 6 kompetisi)
php artisan db:seed

# 9. Storage link untuk sertifikat
php artisan storage:link

# 10. Build frontend
npm run build

# 11. Jalankan dev server (3 in 1: app + queue + vite)
npm run dev
```

Atau gunakan script `setup`:

```bash
composer run setup
```

### Login Admin

| Email | Password |
|---|---|
| `admin1@kafeinarts.com` | `password` |
| `admin2@kafeinarts.com` | `password` |

> **Hanya 2 admin yang bisa register.** Setelah 2 akun dibuat, halaman register admin akan menampilkan error.

### Queue Worker

Untuk generate sertifikat, jalankan queue worker:

```bash
php artisan queue:work
```

Atau sudah termasuk dalam `npm run dev`:

```bash
npm run dev          # menjalankan: php artisan serve + queue:listen + vite
```

---

## Pengembangan & Maintenance

### Menambah Kompetisi Baru

1. Tambahkan SVG ilustrasi di `app/Services/SvgIllustrations.php`
2. Jalankan seeder atau insert manual ke tabel `competitions`:
   ```php
   Competition::create([
       'name' => 'Lomba Baru',
       'slug' => 'lomba-baru',
       'type' => 'individu', // atau 'tim'
       'age_category' => 'dewasa', // 'anak-anak', 'remaja', 'dewasa'
       'svg_illustration_key' => 'lomba-baru',
   ]);
   ```
3. Tambahkan key SVG di method `all()` pada `SvgIllustrations`

### Menambah Livewire Component Baru

```bash
# Buat component (manual)
# 1. Buat file di app/Livewire/NamaComponent.php
# 2. Buat view di resources/views/livewire/nama-component.blade.php

# Contoh component sederhana:
```
```php
<?php
namespace App\Livewire;

use Livewire\Component;

class NamaComponent extends Component
{
    public function render()
    {
        return view('livewire.nama-component');
    }
}
```

### Dark Mode

- Tersimpan di `localStorage` key `darkMode`
- Toggle di navbar publik (`guest.blade.php`) & admin (`navigation.blade.php`)
- Semua komponen pakai kelas `dark:` untuk styling
- Inline script di `<head>` mencegah FOUC

### Queue & Performa

| Task | Queue | Keterangan |
|---|---|---|
| Generate PDF sertifikat | `default` | Dijalankan async, tidak blocking |
| Polling lounge | — | Livewire `wire:poll.5s` — ringan |
| Polling queue admin | — | Livewire `wire:poll.10s` — ringan |

### Sesuaikan untuk Production

File `.env`:
```env
APP_ENV=production
APP_DEBUG=false
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis     # Ganti dari database ke redis
```

### Perintah Berguna

```bash
php artisan migrate                          # Jalankan migrasi
php artisan migrate:fresh                    # Reset + migrasi ulang
php artisan migrate:fresh --seed             # Reset + migrasi + seed
php artisan db:seed                          # Seed data
php artisan queue:work                       # Jalankan queue worker
php artisan storage:link                     # Symlink storage
npm run build                                # Build frontend
npm run dev                                  # Dev server (app + queue + vite)
php artisan view:clear                       # Clear cache view
php artisan livewire:publish --config        # Publish Livewire config
```

---

## FAQ

**Q: Kenapa cuma bisa 2 admin?**
A: Sesuai requirement — 2 laptop panitia. Logic di `RegisteredUserController@store` cek `User::count() >= 2`.

**Q: Gimana cara set Juara 1/2/3?**
A: Bisa lewat database langsung (`registrations.rank` = 1/2/3) atau via Tinker. Fitur ini belum ada UI-nya di dashboard — bisa ditambahkan sebagai enhancement.

**Q: Kompetisi baru muncul di publik setelah apa?**
A: Setelah di-insert ke tabel `competitions`. Tidak perlu restart server, karena Livewire membaca dari DB setiap render.

**Q: Sertifikat tidak muncul/download?**
A: Pastikan `php artisan storage:link` sudah dijalankan dan queue worker aktif (`php artisan queue:work`).

**Q: Dark mode tidak berfungsi?**
A: Cek `localStorage` di browser — key `darkMode` harus `'true'` atau `'false'`. Clear cache browser jika perlu.

**Q: Error "Class Livewire\... not found"?**
A: Jalankan `composer dump-autoload` lalu `php artisan optimize`.

---

## Credits

**Kafeinarts Management Tools** — Dibuat untuk memeriahkan HUT RI dengan teknologi modern.

---

*Dokumentasi ini diperbaharui pada: 20 Juni 2026*
