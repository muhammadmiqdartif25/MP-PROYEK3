<a id="readme-top"></a>

# Karhutla Care

Pelaporan dan pemantauan kebakaran hutan dan lahan untuk Mini Project Proyek 3, Kasus B.

[![PHP](https://img.shields.io/badge/PHP-8.5.10-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/) [![Laravel](https://img.shields.io/badge/Laravel-13.38.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/) [![MySQL](https://img.shields.io/badge/MySQL-pelaporan__karhutla-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/) [![Blade](https://img.shields.io/badge/Blade-template-F55247?style=for-the-badge)](https://laravel.com/docs/blade)

[Tentang proyek](#tentang-proyek) ·
[Cara pakai](#cara-pakai) ·
[Instalasi](#instalasi) ·
[Kontak](#kontak)

## Daftar isi

- [Tentang proyek](#tentang-proyek)
- [Kebutuhan](#kebutuhan)
- [Instalasi](#instalasi)
- [Menjalankan aplikasi](#menjalankan-aplikasi)
    - [Opsi 1: Laragon](#opsi-1-laragon)
    - [Opsi 2: server bawaan Laravel](#opsi-2-server-bawaan-laravel)
- [Akun demo](#akun-demo)
- [Cara pakai](#cara-pakai)
    - [Alur status laporan](#alur-status-laporan)
- [Business rule](#business-rule)
- [Struktur data](#struktur-data)
- [Bukti unggahan](#bukti-unggahan)
- [Interaksi asynchronous](#interaksi-asynchronous)
- [Observer](#observer)
- [Kontak](#kontak)

## Tentang proyek

Laporan kebakaran hutan dan lahan umumnya masuk melalui pesan singkat, telepon, atau grup percakapan dengan format berbeda-beda. Akibatnya, informasi lokasi, bukti kejadian, dan riwayat penanganan sering hilang. Petugas juga kesulitan mengetahui status laporan tanpa menelusuri percakapan satu per satu.

Website ini menyediakan pencatatan laporan terpusat dengan tiga pengguna: pelapor yang mengirim laporan beserta bukti dan lokasi, relawan yang memeriksa laporan, serta koordinator yang meneruskan laporan ke instansi terkait dan menutup laporan setelah selesai ditangani.

Fungsi utama aplikasi mencakup pelaporan, peninjauan, penerusan, dan pemantauan. Setiap perubahan status dilengkapi catatan dan riwayat yang dapat ditelusuri. Halaman beranda menampilkan rekap laporan berdasarkan wilayah dan status.

## Kebutuhan

| Komponen          | Versi atau keterangan                                                 |
| ----------------- | --------------------------------------------------------------------- |
| PHP               | 8.5.10                                                                |
| Laravel Framework | 13.38.0                                                               |
| Database          | MySQL atau MariaDB, nama database `pelaporan_karhutla`                |
| Web server        | Laragon (Apache atau Nginx) atau web server lain yang menjalankan PHP |

Versi PHP dan Laravel di atas adalah versi yang terpasang saat proyek ini dijalankan.

## Instalasi

1. Clone repository lalu masuk ke foldernya.

    ```bash
    git clone https://github.com/muhammadmiqdartif25/MP-PROYEK3.git
    cd MP-PROYEK3
    ```

2. Pasang dependensi PHP.

    ```bash
    composer install
    ```

3. Salin berkas environment. Di Windows, `copy` dipakai; di shell POSIX gunakan `cp`.

    ```bash
    copy .env.example .env
    ```

4. Buat application key.

    ```bash
    php artisan key:generate
    ```

5. Buat database `pelaporan_karhutla` di MySQL atau MariaDB, lalu sesuaikan nilai `DB_*` di `.env`. Berkas `.env.example` masih memakai `DB_CONNECTION=sqlite`, jadi baris itu harus diubah ke `mysql` agar aplikasi tersambung ke database yang benar.

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pelaporan_karhutla
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    Isi `DB_USERNAME` dan `DB_PASSWORD` sesuai akun MySQL di komputer Anda.

6. Jalankan migrasi beserta data demo.

    ```bash
    php artisan migrate:fresh --seed
    ```

<p align="right">(<a href="#readme-top">kembali ke atas</a>)</p>

## Menjalankan aplikasi

Database harus sudah dibuat dan dimigrasi lebih dulu (lihat bagian [Instalasi](#instalasi)), sebelum salah satu cara di bawah dipakai. Ada dua cara menjalankan aplikasi.

### Opsi 1: Laragon

Daftarkan folder proyek sebagai virtual host di Laragon, lalu buka:

```
http://mp-uts-proyek3.test
```

### Opsi 2: server bawaan Laravel

Kalau tidak memakai Laragon, jalankan server bawaan Laravel:

```bash
php artisan serve
```

Alamat bawaannya `http://127.0.0.1:8000`. Kalau port itu sudah terpakai, tentukan port lain dengan `--port`, misalnya `php artisan serve --port=8080`. Opsi `--host` dipakai kalau alamat bind perlu diganti.

Satu hal yang perlu disesuaikan: `APP_URL` di `.env` saat ini berisi `http://mp-uts-proyek3.test/`, dan nilai itu tidak cocok dengan `http://127.0.0.1:8000`. Ubah dulu saat memakai `php artisan serve`:

```dotenv
APP_URL=http://127.0.0.1:8000
```

Setelah mengubahnya, jalankan `php artisan config:clear` agar nilai baru terbaca.

Header `Host` hanya berpengaruh pada pembentukan URL oleh route, dan kedua cara menjalankan aplikasi yang sama. Tidak ada fitur yang bergantung pada Laragon.

Halaman depan hanya menampilkan penjelasan singkat dan tautan menuju halaman login. Halaman itu tidak mengalihkan pengunjung secara otomatis. Setelah masuk, pengguna dibawa ke `/home` yang isinya menyesuaikan peran.

## Akun demo

Akun dibuat oleh seeder. Tidak ada halaman registrasi.

| Username | Password     | Peran       | Nama                  |
| -------- | ------------ | ----------- | --------------------- |
| `miqdar` | `koor123`    | koordinator | Muhammad Miqdar Salim |
| `fauzan` | `relawan123` | relawan     | Fauzan Fadhila        |
| `rafli`  | `pelapor123` | pelapor     | Muhamad Rafli Ar Rauf |

Data contoh yang ikut ter-seed: 3 pengguna, 5 wilayah, 11 laporan, 6 bukti, 16 catatan peninjauan, dan 16 riwayat status. Keempat status muncul di data contoh. Ada tiga laporan `baru` milik `rafli` untuk menguji BR-01, dan dua laporan `diperiksa` tanpa bukti untuk menguji penolakan BR-04. Berkas bukti contoh ditulis langsung ke disk oleh seeder, jadi tautan unduhannya tetap berfungsi setelah instalasi baru.

## Cara pakai

Alur tiap peran:

| Peran         | Langkah                                                                                                                                                                                                                     |
| ------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `pelapor`     | Masuk, lihat ringkasan laporan sendiri di `/home`, buat laporan di `/laporan/buat`, pantau daftar di `/laporan`, ubah laporan selama statusnya masih `baru`, unduh bukti laporan sendiri dari halaman detail.               |
| `relawan`     | Masuk, lihat rekap laporan per wilayah di `/home`, buka seluruh laporan di `/laporan`, baca detail, tulis catatan peninjauan, pindahkan `baru` ke `diperiksa`, dan unduh bukti laporan mana pun.                            |
| `koordinator` | Semua yang bisa dilakukan relawan, ditambah memindahkan `diperiksa` ke `diteruskan` dan `diteruskan` ke `selesai`, serta membuka halaman Data Wilayah di `/area` yang menampilkan daftar wilayah beserta jumlah laporannya. |

Halaman Data Wilayah bersifat baca saja. Data wilayah diisi lewat seeder, dan belum ada form untuk menambah atau mengubahnya.

### Alur status laporan

```
baru -> diperiksa -> diteruskan -> selesai
```

| Perpindahan                 | Peran yang berwenang     |
| --------------------------- | ------------------------ |
| `baru` ke `diperiksa`       | relawan atau koordinator |
| `diperiksa` ke `diteruskan` | koordinator              |
| `diteruskan` ke `selesai`   | koordinator              |

Status hanya bergerak satu langkah ke depan, tidak boleh melompat, dan tidak boleh mundur. `selesai` adalah status akhir, tidak ada perpindahan dari sana.

Setiap perubahan status wajib menyertakan catatan minimal 10 karakter. Catatan itu disimpan sebagai baris `report_reviews` beserta identitas pengguna yang menulisnya, lalu observer menulis baris riwayat status.

Perpindahan ke `diteruskan` punya syarat tambahan: laporan harus sudah memiliki bukti, dan pengirim wajib mengisi `forwarded_to` serta `forwarding_note`.

<p align="right">(<a href="#readme-top">kembali ke atas</a>)</p>

## Business rule

| Kode  | Aturan                                                                                                                       | Alasan                                                                                   |
| ----- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- |
| BR-01 | Laporan hanya bisa diubah oleh pemiliknya dan hanya selama statusnya `baru`.                                                 | Isi laporan yang sudah masuk proses pemeriksaan tidak boleh berubah di belakang petugas. |
| BR-02 | Status hanya maju satu langkah, tidak dilompati, tidak dibalik.                                                              | Urutan penanganan harus mencerminkan tahapan yang benar-benar dilalui.                   |
| BR-03 | Hanya koordinator yang boleh memindahkan laporan ke `diteruskan` atau `selesai`; relawan hanya sampai `baru` ke `diperiksa`. | Keputusan meneruskan dan menutup laporan butuh kewenangan yang lebih tinggi.             |
| BR-04 | Laporan tanpa bukti tidak bisa dipindahkan ke `diteruskan`.                                                                  | Penerusan ke instansi lain harus didukung berkas yang bisa diperiksa.                    |

## Struktur data

| Tabel                | Isi                                                                                                            |
| -------------------- | -------------------------------------------------------------------------------------------------------------- |
| `users`              | Akun pengguna dengan `full_name`, `username`, dan `role`.                                                      |
| `areas`              | Daftar wilayah pemantauan, satu kolom `name` yang unik.                                                        |
| `reports`            | Laporan karhutla: judul, deskripsi, waktu kejadian, alamat, koordinat, status, data penerusan, dan data bukti. |
| `report_reviews`     | Catatan peninjauan beserta penulis dan waktunya.                                                               |
| `report_status_logs` | Riwayat perubahan status: status lama, status baru, pelaku, dan waktu.                                         |

Relasi utama: satu pengguna punya banyak laporan, satu wilayah punya banyak laporan, dan satu laporan punya banyak catatan peninjauan serta banyak riwayat status. Bukti disimpan sebagai satu kolom `evidence_path` opsional di tabel `reports`, sehingga satu laporan paling banyak punya satu bukti tanpa tabel terpisah.

## Bukti unggahan

| Aturan          | Nilai                                              |
| --------------- | -------------------------------------------------- |
| Jumlah berkas   | Satu per laporan                                   |
| Format          | JPG, JPEG, PNG, PDF                                |
| Ukuran maksimal | 2 MB (2048 KB)                                     |
| Lokasi simpan   | Disk `local`, yaitu `storage/app/private/evidence` |
| Akses           | Lewat route berizin `GET /laporan/{report}/bukti`  |

Kolom bukti bersifat opsional saat membuat atau mengubah laporan. Kalau pelapor mengunggah berkas baru pada laporan yang sama, berkas lama diganti, bukan disimpan sebagai versi tambahan.

Berkas tidak bisa dibuka lewat URL publik. Unduhan selalu melewati pemeriksaan izin, jadi pelapor hanya bisa mengunduh bukti laporannya sendiri, sedangkan relawan dan koordinator bisa mengunduh bukti laporan mana pun.

<p align="right">(<a href="#readme-top">kembali ke atas</a>)</p>

## Interaksi asynchronous

Memilih wilayah pada dropdown Wilayah di halaman rekap (`/home`) atau di halaman daftar laporan (`/laporan`) memicu `fetch()` ke endpoint berikut:

```
GET /area/{area}/info
```

## Observer

`App\Observers\ReportObserver` mendengarkan event `Report::updated`. Kalau kolom `status` berubah, observer menulis satu baris ke `report_status_logs` berisi `report_id`, `user_id`, `old_status`, `new_status`, dan timestamp.

Observer mencatat riwayat perpindahan status.

## Kontak

| Item       | Keterangan                                            |
| ---------- | ----------------------------------------------------- |
| Nama       | Muhammad Miqdar Salim                                 |
| Email      | muhammad.miqdar.tif25@polban.ac.id                    |
| Repository | https://github.com/muhammadmiqdartif25/MP-PROYEK3.git |
| NIM        | `251511050`                                           |
| Kelas      | `2B`                                                  |
