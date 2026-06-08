# Requirement Tabel Migration - Akuntansi Pemuda

Dokumen ini berisi spesifikasi detail untuk pembuatan migration tabel database berdasarkan ERD.

**Catatan:** Semua tabel menggunakan soft delete (`deleted_at`).

## 1. Tabel `users`
Tabel untuk menyimpan informasi pengguna sistem.

| Kolom | Tipe Data | Atribut | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | Auto Increment | Primary Key |
| `name` | String | Nullable, Unique | Nama lengkap pengguna |
| `username` | String | Nullable, Unique | Username untuk login |
| `email` | String | Unique | Alamat email |
| `role` | Enum | - | `bendahara`, `biasa` |
| `status` | Enum | - | `Aktif`, `Pending`, `Tidak Aktif` |
| `profile_image` | String | Nullable | Path/URL foto profil |
| `provider` | String | Nullable | Provider autentikasi |
| `id_provider` | String | Nullable | ID dari provider eksternal |
| `password` | String | Nullable | Hash password |
| `activated_at` | DateTime | Nullable | Waktu aktivasi akun |
| `deleted_at` | Timestamp | Nullable | Soft delete |

---

## 2. Tabel `penanggung_jawab`
Tabel untuk menyimpan data penanggung jawab transaksi.

| Kolom | Tipe Data | Atribut | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | Auto Increment | Primary Key |
| `nama` | String | Unique | Nama penanggung jawab |
| `valuasi_transaksi` | Integer | - | Nilai valuasi transaksi |
| `deleted_at` | Timestamp | Nullable | Soft delete |

---

## 3. Tabel `akun`
Tabel untuk menyimpan daftar akun akuntansi.

| Kolom | Tipe Data | Atribut | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | Auto Increment | Primary Key |
| `riil_terakhir` | BigInt (FK) | Nullable | Foreign Key ke `riil_history.id` |
| `nama_akun` | String | Index | Nama akun |
| `kas` | Enum | Index | `17 an`, `kas pemuda` |
| `deleted_at` | Timestamp | Nullable | Soft delete |

---

## 4. Tabel `riil_history`
Tabel untuk mencatat riwayat saldo riil akun.

| Kolom | Tipe Data | Atribut | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | Auto Increment | Primary Key |
| `akun_id` | BigInt (FK) | - | Foreign Key ke `akun.id` |
| `date` | Date | Index | Tanggal pencatatan |
| `verified` | Boolean | Index | Status verifikasi |
| `riil` | Decimal/BigInt | - | Jumlah saldo riil |
| **Constraint** | **Unique** | `(akun_id, date)` | Satu akun hanya boleh punya satu riil per tanggal |
| `deleted_at` | Timestamp | Nullable | Soft delete |

---

## 5. Tabel `transaksi`
Tabel untuk mencatat transaksi keuangan.

| Kolom | Tipe Data | Atribut | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | Auto Increment | Primary Key |
| `akun_id` | BigInt (FK) | - | Foreign Key ke `akun.id` |
| `penginput_id` | BigInt (FK) | - | Foreign Key ke `users.id` |
| `penanggung_jawab_id` | BigInt (FK) | - | Foreign Key ke `penanggung_jawab.id` |
| `deskripsi` | String | - | Deskripsi transaksi |
| `date` | Date | Index | Tanggal transaksi |
| `jenis_transaksi` | Enum | Index | `pemasukan`, `pengeluaran` |
| `jumlah` | Integer | - | Nominal transaksi |
| `bukti` | String | Nullable | Path/URL bukti transaksi |
| `deleted_at` | Timestamp | Nullable | Soft delete |

---

## 6. Tabel `mutasi_rekening`
Tabel untuk mencatat mutasi antar akun (debit/kredit).

| Kolom | Tipe Data | Atribut | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | Auto Increment | Primary Key |
| `akun_debit_id` | BigInt (FK) | - | Foreign Key ke `akun.id` |
| `akun_kredit_id` | BigInt (FK) | - | Foreign Key ke `akun.id` |
| `date` | Date | Index | Tanggal mutasi |
| `jumlah` | Integer | - | Nominal mutasi |
| `keterangan` | Text | - | Keterangan mutasi |
| `deleted_at` | Timestamp | Nullable | Soft delete |

## Relasi Antar Tabel (Summary)
- `riil_history` $\rightarrow$ `akun` (Many-to-One via `akun_id`)
- `akun` $\rightarrow$ `riil_history` (One-to-One/Many via `riil_terakhir`)
- `transaksi` $\rightarrow$ `akun` (Many-to-One via `akun_id`)
- `transaksi` $\rightarrow$ `users` (Many-to-One via `penginput_id`)
- `transaksi` $\rightarrow$ `penanggung_jawab` (Many-to-One via `penanggung_jawab_id`)
- `mutasi_rekening` $\rightarrow$ `akun` (Many-to-One via `akun_debit_id` dan `akun_kredit_id`)
