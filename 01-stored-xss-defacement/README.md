# 💉 Stored XSS & Web Defacement — Demo Kerentanan Web

> **Seri Lab:** Web Hacking Lab  
> **YouTube:** [▶️ Tonton Tutorial di YouTube](https://youtu.be/XSEhGtyCxfM?si=5ifVwqQjOE1adgn2)  
> **Kategori:** `Web Security, XSS, Offensive Security`

---

## ⚠️ Disclaimer

> Project ini dibuat **hanya untuk keperluan pembelajaran keamanan web**. Gunakan hanya di lingkungan lokal atau lab yang aman dan legal. Jangan pernah melakukan teknik ini terhadap sistem yang bukan milikmu.

---

## 📋 Deskripsi

Lab ini mendemonstrasikan bagaimana sebuah website dapat diretas secara visual melalui serangan **Stored XSS** yang mengakibatkan **Web Defacement**. Payload berbahaya disimpan ke database dan efeknya bertahan bahkan setelah halaman direfresh — menunjukkan dampak nyata dari kerentanan input yang tidak disanitasi.

---

## 🎬 Video Tutorial

[![Stored XSS & Web Defacement Demo](https://img.youtube.com/vi/XSEhGtyCxfM/maxresdefault.jpg)](https://youtu.be/XSEhGtyCxfM?si=5ifVwqQjOE1adgn2)

> 📺 **[Tonton di YouTube → Stored XSS & Web Defacement Demo](https://youtu.be/XSEhGtyCxfM?si=5ifVwqQjOE1adgn2)**

---

## 🧠 Konsep Dasar

### Apa itu Deface?

**Defacement** adalah tindakan mengubah tampilan halaman website secara tidak sah — biasanya untuk menampilkan pesan, gambar, logo, atau konten yang tidak semestinya.

| Motivasi Deface | Penjelasan |
|-----------------|------------|
| Provokasi | Menampilkan pesan atau logo kelompok tertentu |
| Pencurian perhatian | Membuat website terlihat diretas untuk viral |
| Demonstrasi kerentanan | Membuktikan celah keamanan ada di sistem target |

---

### Apa itu Stored XSS?

**Stored XSS** terjadi ketika input dari pengguna **disimpan di server** (database), lalu ditampilkan kembali ke halaman lain tanpa pemfilteran yang aman — sehingga browser mengeksekusinya sebagai HTML/JavaScript yang valid.

```
Perbedaan XSS:

Reflected XSS → payload tidak disimpan, hanya memantul sekali
Stored XSS    → payload disimpan di database, muncul terus
DOM XSS       → payload dieksekusi di sisi client via DOM
```

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| **Backend** | PHP |
| **Database** | MySQL |
| **Halaman Utama** | `index.php` |
| **Panel Admin** | `dashboard.php` |
| **Koneksi DB** | `config.php` |

---

## 🔍 Bagian yang Menjadi Sumber Kerentanan

Titik rawan terdapat di `index.php` pada alur berikut:

```php
// ❌ KODE RENTAN — JANGAN DITIRU DI PRODUKSI

// 1. Input diambil langsung dari URL tanpa validasi
$payload = $_GET['deface'];

// 2. Langsung disimpan ke database tanpa sanitasi
// INSERT INTO settings SET value = '$payload'

// 3. Ditampilkan langsung sebagai HTML tanpa escaping
echo $payload;  // ← INI SANGAT BERBAHAYA
```

**Tiga penyebab kerentanan:**

| Penyebab | Penjelasan |
|----------|------------|
| ❌ Tidak ada validasi input | Semua karakter diterima termasuk `<script>`, `<html>` |
| ❌ Tidak ada sanitasi | Data user disimpan mentah ke database |
| ❌ Tidak ada output escaping | Data langsung di-echo sebagai HTML |

---

## 🎯 Alur Serangan

### Diagram Attack Chain

```
[1] Attacker menyiapkan payload XSS/HTML
         │
         │  GET /index.php?deface=<payload>
         ▼
[2] index.php menerima payload dari URL
         │
         │  INSERT INTO settings (home_deface_payload)
         ▼
[3] Payload tersimpan ke database MySQL
         │
         │  SELECT home_deface_payload → echo langsung
         ▼
[4] Halaman utama menampilkan payload sebagai HTML
         │
         ▼
[5] Website ter-deface — tampilan berubah total
         │
         │  Refresh halaman
         ▼
[6] Efek TETAP ADA karena payload sudah di database
```

---

## 🧪 Langkah-Langkah Demo

### Langkah 1 — Persiapan

Pastikan semua service berjalan:

```
✅ PHP berjalan
✅ MySQL berjalan
✅ Database sudah tersedia
✅ Website dapat diakses lewat localhost
```

---

### Langkah 2 — Masukkan Payload via URL

Payload dikirim melalui parameter `deface` di URL:

```
http://localhost/index.php?deface=<PAYLOAD_DI_SINI>
```

---

### Langkah 3 — Payload Tersimpan ke Database

Nilai payload disimpan ke setting `home_deface_payload` di tabel `settings`.

---

### Langkah 4 — Halaman Utama Berubah

Saat `index.php` dibuka, nilai yang tersimpan ditampilkan langsung sebagai HTML — tampilan website berubah menjadi halaman deface.

---

### Langkah 5 — Verifikasi Persistensi

Refresh halaman berkali-kali — efek deface **tetap muncul** karena payload sudah tersimpan di database, bukan hanya di URL.

---

### Langkah 6 — Cleanup via Admin Panel

Admin dapat menghapus payload melalui `dashboard.php`:

```
Buka dashboard.php → Login admin → Klik "Cleanup Payload"
→ Payload dihapus dari database
→ Halaman utama kembali normal
```

> 💡 **Penting:** Cleanup hanya menghapus efeknya — **kerentanannya tetap ada** selama kode tidak diperbaiki. Ini menunjukkan bahwa perbaikan keamanan bukan hanya soal menghapus data yang salah, tetapi juga **memperbaiki alur input dan output**.

---

## 💥 Mengapa Ini Berbahaya?

Serangan ini memungkinkan attacker untuk:

| Dampak | Penjelasan |
|--------|------------|
| **Mengubah tampilan website** | Seluruh konten halaman bisa diganti |
| **Menampilkan konten palsu** | Phishing, propaganda, atau konten berbahaya |
| **Menjalankan JavaScript** | Steal cookies, keylogging, redirect ke malware |
| **Dampak persisten** | Efek bertahan karena tersimpan di database |
| **Reputasi rusak** | Pengunjung melihat situs "diretas" |

---

## 🛡️ Mitigasi yang Disarankan

### ❌ Kode Rentan vs ✅ Kode Aman

```php
// ❌ RENTAN
$payload = $_GET['deface'];
echo $payload;

// ✅ AMAN
$payload = htmlspecialchars($_GET['deface'], ENT_QUOTES, 'UTF-8');
echo $payload;
```

### Langkah Mitigasi Lengkap

| Langkah | Implementasi |
|---------|-------------|
| **Validasi input** | Tolak karakter berbahaya sebelum diproses |
| **Sanitasi input** | Bersihkan input sebelum disimpan ke database |
| **Output escaping** | Gunakan `htmlspecialchars()` saat menampilkan data |
| **Prepared Statement** | Gunakan PDO/MySQLi prepared statement |
| **Batasi karakter** | Whitelist karakter yang diizinkan |
| **Content Security Policy** | Tambahkan header CSP di server |
| **Prinsip dasar** | **Jangan pernah** langsung menampilkan data user sebagai HTML |

---

## 📊 Ringkasan

| Aspek | Detail |
|-------|--------|
| **Jenis serangan** | Stored XSS → Web Defacement |
| **Titik masuk** | Parameter `?deface=` di URL |
| **Penyimpanan** | Tabel `settings`, kolom `home_deface_payload` |
| **Dampak** | Tampilan website berubah secara persisten |
| **Pemulihan** | Cleanup via `dashboard.php` |
| **Akar masalah** | Tidak ada validasi, sanitasi, dan output escaping |

---

## 📚 Referensi

- [OWASP XSS Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html)
- [OWASP Top 10 — A03: Injection](https://owasp.org/Top10/A03_2021-Injection/)
- [MITRE ATT&CK — XSS (T1059.007)](https://attack.mitre.org/techniques/T1059/007/)
- [PortSwigger — Stored XSS](https://portswigger.net/web-security/cross-site-scripting/stored)

---

*📺 Ikuti tutorialnya di [YouTube](https://youtu.be/XSEhGtyCxfM?si=5ifVwqQjOE1adgn2) | ⭐ Star repo ini jika membantu!*
