# Product Requirement Document (PRD)
## Sistem E-Voting Pemilihan Ketua Koperasi

**Versi:** 1.0  
**Tanggal:** 16 September 2026  
**Status:** Approved / Ready for Development  
**Penulis:** System Architect & Product Manager  

---

## 1. Ringkasan Eksekutif & Tujuan (Executive Summary & Goals)

Sistem E-Voting Pemilihan Ketua Koperasi adalah aplikasi berbasis web yang dirancang untuk memodernisasi dan mempermudah proses pemungutan suara pemilihan ketua koperasi secara digital, transparan, aman, dan real-time. 

### Tujuan Utama:
1. **Integritas & Keamanan Suara:** Memastikan *One Person, One Vote* (satu anggota hanya dapat memilih satu kandidat).
2. **Kemudahan Penggunaan (User Experience):** Menyediakan antarmuka yang intuitif, konfirmasi pemilihan ganda untuk mencegah salah klik, serta perayaan visual berupa animasi konfeti setelah pemungutan suara berhasil.
3. **Aksesibilitas Multi-Device:** Menjamin tampilan responsive yang optimal baik di smartphone (mobile) maupun komputer (PC/Desktop).
4. **Privasi Hasil (Admin-Only Ranking):** Menjaga netralitas selama pemungutan suara dengan membatasi akses halaman hasil/ranking hanya untuk role Admin.
5. **Kemudahan Pengelolaan:** Menyediakan panel Admin lengkap dengan fitur CRUD (Create, Read, Update, Delete) untuk Manajemen User (Anggota) dan Kandidat.

---

## 2. Target Pengguna & Peran (User Roles & Personas)

| Role | Deskripsi | Hak Akses (Permissions) |
| :--- | :--- | :--- |
| **Voter (Anggota Koperasi)** | Anggota koperasi yang memiliki hak pilih resmi. | - Login ke sistem.<br>- Melihat daftar kandidat ketua.<br>- Memilih 1 kandidat (dengan pop-up konfirmasi).<br>- Melihat status telah memilih (kandidat lain ter-disabled).<br>- Menikmati efek animasi konfeti saat sukses memilih. |
| **Admin (Pengurus/Panitia)** | Panitia pelaksana pemilihan ketua koperasi. | - Login sebagai administrator.<br>- Mengakses Dashboard Admin.<br>- Kelola data Anggota/User (CRUD + Reset Vote Status).<br>- Kelola data Kandidat (CRUD: Nama, Visi, Misi, Foto).<br>- Melihat Halaman Ranking / Real-time Vote Count & Percentage. |

---

## 3. Alur Pengguna (User Journey / Workflows)

### 3.1. Alur Pemilihan (Voter Flow)
1. **Halaman Login:** User memasukkan Nomor Anggota / Username dan Password.
2. **Dashboard Voting:**
   - Sistem memeriksa status `has_voted`.
   - **Jika `has_voted == false`:** Seluruh kartu kandidat aktif (tombol "Pilih" enabled).
   - **Jika `has_voted == true`:** Sistem menampilkan *badge* "Anda Sudah Menggunakan Hak Pilih" dan seluruh tombol kandidat diubah menjadi `disabled`.
3. **Proses Memilih Kandidat:**
   - User mengklik tombol **"Pilih"** pada salah satu kandidat.
   - **Pop-up Modal Konfirmasi** muncul: *"Apakah Anda yakin ingin memilih [Nama Kandidat] sebagai Ketua Koperasi?"* dengan pilihan [Batal] dan [Ya, Yakin].
4. **Eksekusi & Animasi Konfeti:**
   - Jika diklik **"Ya, Yakin"**, request dikirim ke backend.
   - Database mengupdate status `has_voted = true` dan increment `vote_count` kandidat.
   - Sistem menampilkan notifikasi sukses dan memicu **Animasi Konfeti (Canvas Confetti)** di layar.
   - Tampilan otomatis ter-lock (tombol kandidat ter-disabled).

### 3.2. Alur Administrator (Admin Flow)
1. **Login Admin:** Admin masuk melalui portal login khusus atau akun ber-role Admin.
2. **Navigation Bar Admin:**
   - Menu **Manajemen User (CRUD)**
   - Menu **Manajemen Kandidat (CRUD)**
   - Menu **Hasil & Ranking (Admin Only)**
3. **Melihat Perolehan Suara:**
   - Admin membuka menu "Hasil & Ranking".
   - Menampilkan total pemilih, persentase partisipasi, serta grafik/tabel ranking kandidat secara real-time.

---

## 4. Persyaratan Fungsional (Functional Requirements)

### 4.1. Modul Autentikasi & Otorisasi
- **FR-AUTH-01:** Sistem dapat membedakan hak akses berdasarkan role (`VOTER` vs `ADMIN`).
- **FR-AUTH-02:** Sistem menggunakan JWT (JSON Web Token) atau Session-based Auth yang aman.
- **FR-AUTH-03:** Mencegah user tak terautentikasi mengakses halaman internal (Protected Routes).

### 4.2. Modul Voting (Anggota)
- **FR-VOTE-01:** Tampilan daftar kandidat menyajikan foto, nomor urut, nama, serta visi & misi ringkas.
- **FR-VOTE-02:** Pop-up konfirmasi (Modal Dialog) wajib muncul sebelum voting diproses.
- **FR-VOTE-03:** Setelah vote tersimpan, sistem langsung menonaktifkan (*disabled*) semua opsi kandidat tanpa perlu refresh manual.
- **FR-VOTE-04:** Pemicu efek visual animasi konfeti (*canvas-confetti*) saat perolehan suara berhasil dicatat.
- **FR-VOTE-05:** Validasi ketat di sisi server (backend) untuk memastikan 1 akun hanya bisa memilih 1 kali (mencegah *double voting* via API).

### 4.3. Modul Admin - CRUD User (Anggota)
- **FR-USER-01:** **Create:** Admin dapat menambahkan anggota baru (NIP/ID, Nama, Username, Password, Role).
- **FR-USER-02:** **Read:** Admin dapat melihat daftar seluruh user beserta status votingnya (`Sudah Memilih` / `Belum Memilih`).
- **FR-USER-03:** **Update:** Admin dapat memperbarui data anggota atau melakukan reset password.
- **FR-USER-04:** **Delete:** Admin dapat menghapus data anggota.
- **FR-USER-05:** **Reset Vote (Opsional/Darurat):** Admin dapat mereset status `has_voted` user jika terjadi kesalahan teknis yang sah.

### 4.4. Modul Admin - CRUD Kandidat
- **FR-CAND-01:** **Create:** Admin dapat menambahkan kandidat baru (Nomor Urut, Nama Lengkap, Visi, Misi, Foto/Avatar).
- **FR-CAND-02:** **Read:** Admin dapat melihat list seluruh kandidat.
- **FR-CAND-03:** **Update:** Admin dapat mengubah profil, visi-misi, atau foto kandidat.
- **FR-CAND-04:** **Delete:** Admin dapat menghapus kandidat dari sistem.

### 4.5. Modul Admin - Ranking & Statistics (Admin Only)
- **FR-STAT-01:** Halaman ini **hanya dapat diakses** oleh user dengan role `ADMIN`. Voter yang mencoba mengakses endpoint/halaman ini akan di-redirect ke Dashboard Voting.
- **FR-STAT-02:** Menampilkan grafik statistik (Bar Chart / Pie Chart) perolehan suara kandidat.
- **FR-STAT-03:** Menampilkan ringkasan metrik: Total Anggota, Jumlah Sudah Memilih, Jumlah Belum Memilih, Persentase Golput/Partisipasi.
- **FR-STAT-04:** Urutan kandidat otomatis diurutkan (*ranking*) berdasarkan suara terbanyak.

---

## 5. Persyaratan Non-Fungsional (Non-Functional Requirements)

### 5.1. Desain UI/UX & Responsivitas
- **NFR-UI-01 (Responsive Design):** Menggunakan prinsip *Mobile-First*. Tampilan disesuaikan secara sempurna untuk layar Smartphone (320px - 480px), Tablet (768px), dan Desktop/PC (1024px+).
- **NFR-UI-02 (Aksesibilitas & Feedback):** Memberikan visual state yang jelas (Loading indicator saat submit vote, Toast Notification sukses/gagal, State disabled yang kontras).

### 5.2. Keamanan (Security)
- **NFR-SEC-01 (Mencegah Fraud):** Validasi `has_voted` dilakukan ganda (Frontend UI disabled state + Backend Database Constraint/Check).
- **NFR-SEC-02 (Password Hashing):** Semua password user di-hash menggunakan `bcrypt` atau Argon2.
- **NFR-SEC-03 (Akses Terbatas):** Route API `/api/admin/*` dan `/api/reports/ranking` dilindungi oleh *middleware authorization* role `ADMIN`.

### 5.3. Performa & Reliabilitas
- **NFR-PERF-01:** Waktu respon sistem saat mengeksekusi voting < 1 detik.
- **NFR-PERF-02:** Animasi konfeti berjalan lancar pada 60 FPS di perangkat mobile tanpa menyebabkan *lag*.

---

## 6. Arsitektur Teknis & Teknologi (Technical Stack Recommendation)

### 6.1. Frontend Options
- **Framework:** React.js / Next.js / Vue.js / HTML5 + Tailwind CSS + Vanilla JS (pilihan sesuai kompleksitas tim).
- **Styling:** Tailwind CSS (dikarenakan fleksibilitas responsif breakpoint `sm:`, `md:`, `lg:`).
- **Animation:** `canvas-confetti` library.
- **Icons:** Lucide React / FontAwesome.

### 6.2. Backend Options
- **Runtime/Framework:** Node.js (Express.js / NestJS) ATAU Python (FastAPI / Laravel PHP).
- **Database:** PostgreSQL / MySQL (Menggunakan transaksi ACID untuk konsistensi data perolehan suara).
- **ORM:** Prisma / Sequelize / Eloquent.

---

## 7. Skema Basis Data (Database Schema Blueprint)

```sql
-- Tabel Users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('VOTER', 'ADMIN') DEFAULT 'VOTER',
    has_voted BOOLEAN DEFAULT FALSE,
    voted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Candidates
CREATE TABLE candidates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    candidate_number INT UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    vision TEXT NOT NULL,
    mission TEXT NOT NULL,
    photo_url VARCHAR(255) NULL,
    vote_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Votes (Audit Trail / Log Pemilihan)
CREATE TABLE votes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL, -- Ensure 1 user = 1 vote constraint
    candidate_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id)
);
```

---

## 8. Spesifikasi Antarmuka Layar (Wireframe & Page Specifications)

### 8.1. Layout Mobile & Desktop
- **Navigation Bar:**
  - Brand Logo Koperasi & Nama Sistem.
  - User Info (Nama & Status Hak Pilih).
  - Tombol Logout.
  - (Untuk Admin) Menu tambahan: *Users, Kandidat, Ranking*.
- **Grid Kandidat:**
  - **Mobile:** 1 Kolom (Stacked vertical).
  - **Tablet:** 2 Kolom.
  - **Desktop:** 3 - 4 Kolom.

### 8.2. Komponen Modal Konfirmasi Vote
```text
+-------------------------------------------------------+
|                KONFIRMASI PEMILIHAN                   |
+-------------------------------------------------------+
| Apakah Anda yakin ingin memilih:                      |
|                                                       |
|   [Foto]  Kandidat #02: Budi Santoso, S.E.            |
|                                                       |
| Pilihan Anda tidak dapat diubah setelah dikonfirmasi. |
+-------------------------------------------------------+
|        [ BATAL ]           [ YA, SAYA YAKIN ]        |
+-------------------------------------------------------+
```

---

## 9. Kriteria Penerimaan (Acceptance Criteria)

1. **AC-01:** User berhasil login dan melihat daftar kandidat yang terformat rapi pada layar smartphone dan laptop.
2. **AC-02:** Saat klik "Pilih", modal konfirmasi muncul. Jika memilih "Batal", modal tertutup tanpa memproses suara.
3. **AC-03:** Jika memilih "Ya", suara tersimpan di DB, animasi konfeti meletup di layar, dan seluruh kandidat langsung ter-disabled.
4. **AC-04:** Apabila user yang sudah memilih mencoba me-refresh halaman atau relogin, status tetap "Sudah Memilih" dan opsi tetap ter-disabled.
5. **AC-05:** Anggota biasa (Role: `VOTER`) tidak memiliki akses ke URL/Menu Admin maupun Hasil Ranking.
6. **AC-06:** Admin dapat menambah, mengedit, dan menghapus data user maupun kandidat secara sukses.
7. **AC-07:** Admin dapat melihat grafik ranking perolehan suara secara real-time.

---

## 10. Rencana Pengujian (Testing Strategy)

- **Unit Testing:** Pengujian fungsi hash password dan enkripsi token auth.
- **Integration Testing:** Pengujian transaksi voting (memastikan rollback terjadi jika update `vote_count` dan `has_voted` gagal salah satu).
- **Stress & Concurrency Testing:** Memastikan sistem menangani beberapa user yang memilih secara bersamaan tanpa terjadi *race condition*.
- **Usability & Responsive Testing:** Pengujian pada browser Chrome Mobile, Safari iOS, serta Chrome Desktop.

---
*Dokumen PRD ini disusun sebagai panduan resmi pengembangan aplikasi E-Voting Koperasi.*
