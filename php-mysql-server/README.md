# 🖥️ PHP & MySQL Server — Instalasi Web Server Stack di Ubuntu

> **Seri Lab:** Web Hacking Lab  
> **YouTube:** [▶️ Tonton Tutorial di YouTube](https://youtu.be/nktfpMGrIBc?si=p5zI2Oj4FNt8Xina)  
> **Kategori:** `Web Server, PHP, MySQL, Ubuntu`

---

## ⚠️ Disclaimer

> Lab ini digunakan sebagai environment untuk **pembelajaran keamanan web**. Server yang diinstall sengaja dikonfigurasi minimal tanpa hardening untuk keperluan demo kerentanan. Jangan gunakan konfigurasi ini di production.

---

## 📋 Deskripsi

Lab ini menginstall **LAMP Stack** (Linux + Apache + MySQL + PHP) di Ubuntu sebagai environment untuk menjalankan aplikasi web yang rentan. Stack ini menjadi fondasi dari seluruh seri Web Hacking Lab — tempat di mana demo kerentanan seperti XSS, SQL Injection, dan lainnya akan dijalankan.

---

## 🎬 Video Tutorial

[![PHP MySQL Server - Instalasi Web Server Stack](https://img.youtube.com/vi/nktfpMGrIBc/maxresdefault.jpg)](https://youtu.be/nktfpMGrIBc?si=p5zI2Oj4FNt8Xina)

> 📺 **[Tonton di YouTube → PHP & MySQL Server: Instalasi Web Server Stack di Ubuntu](https://youtu.be/nktfpMGrIBc?si=p5zI2Oj4FNt8Xina)**

---

## 🧱 Apa itu LAMP Stack?

**LAMP** adalah kombinasi empat teknologi yang bekerja bersama untuk menjalankan aplikasi web berbasis PHP:

```
┌─────────────────────────────────────────────────────┐
│                   LAMP Stack                        │
│                                                     │
│   L — Linux      → Sistem operasi (Ubuntu)          │
│   A — Apache     → Web server (serve halaman web)   │
│   M — MySQL      → Database server (simpan data)    │
│   P — PHP        → Backend language (proses logic)  │
└─────────────────────────────────────────────────────┘
```

---

## 📦 Package yang Diinstall

| Package | Fungsi |
|---------|--------|
| `apache2` | Web server untuk serve halaman web |
| `php` | Bahasa pemrograman untuk backend web |
| `php-mysql` | Extension PHP untuk koneksi ke MySQL |
| `libapache2-mod-php` | Module Apache agar bisa menjalankan PHP |
| `mysql-server` | Database server untuk menyimpan data |

---

## 🗺️ Posisi di Lab

```
Internal Network (10.200.200.0/24)
        │
        ├── Windows Server   (10.200.200.20)
        ├── Windows 10       (10.200.200.30)
        ├── Kali Linux       (10.200.200.10)  ← Attacker
        ├── Ubuntu SIEM      (10.200.200.100) ← Wazuh
        │
        └── Ubuntu Web Server (10.200.200.101) ← Kita install di sini
             ├── Apache  (port 80)
             ├── PHP
             └── MySQL   (port 3306)
```

---

## ⚙️ Langkah-Langkah Instalasi

### Cara Cepat — Install Semua Sekaligus

```bash
sudo apt install apache2 php php-mysql libapache2-mod-php mysql-server -y
```

> 💡 Flag `-y` menjawab "yes" otomatis untuk semua konfirmasi — tidak perlu tekan Enter berkali-kali.

---

### Cara Detail — Install Satu per Satu

#### Langkah 1 — Update Package List

```bash
sudo apt update
```

---

#### Langkah 2 — Install Apache

```bash
sudo apt install apache2 -y
```

Apache adalah web server yang bertugas menerima HTTP request dari browser dan mengirimkan halaman web sebagai respons.

---

#### Langkah 3 — Install PHP

```bash
sudo apt install php -y
```

PHP adalah bahasa pemrograman server-side yang memproses logic aplikasi web — mengambil data dari database, memproses input user, dan menghasilkan HTML dinamis.

---

#### Langkah 4 — Install PHP MySQL Extension

```bash
sudo apt install php-mysql -y
```

Extension ini memungkinkan kode PHP berkomunikasi dengan database MySQL menggunakan fungsi seperti `mysqli_connect()` dan `PDO`.

---

#### Langkah 5 — Install Apache PHP Module

```bash
sudo apt install libapache2-mod-php -y
```

Module ini mengintegrasikan PHP ke dalam Apache — tanpa ini, Apache hanya bisa serve file statis (HTML, CSS, gambar) dan tidak bisa mengeksekusi kode PHP.

---

#### Langkah 6 — Install MySQL Server

```bash
sudo apt install mysql-server -y
```

MySQL adalah database server yang menyimpan semua data aplikasi web — user, konten, konfigurasi, dan lainnya.

---

### Menjalankan Service

#### Langkah 7 — Start & Enable Apache

```bash
# Jalankan Apache sekarang
sudo systemctl start apache2

# Aktifkan agar otomatis start saat boot
sudo systemctl enable apache2
```

---

#### Langkah 8 — Start & Enable MySQL

```bash
# Jalankan MySQL sekarang
sudo systemctl start mysql

# Aktifkan agar otomatis start saat boot
sudo systemctl enable mysql
```

> 💡 `systemctl enable` memastikan service otomatis berjalan setiap kali server direboot — tanpa ini, harus start manual setiap kali Ubuntu menyala.

---

## ✅ Verifikasi Instalasi

### Cek Status Service

```bash
# Cek status Apache
sudo systemctl status apache2

# Cek status MySQL
sudo systemctl status mysql
```

**Output yang diharapkan:**

```
● apache2.service - The Apache HTTP Server
     Active: active (running) since ...

● mysql.service - MySQL Community Server
     Active: active (running) since ...
```

---

### Cek Port yang Listen

```bash
sudo ss -tlnp | grep -E "80|3306"
```

**Output yang diharapkan:**

```
LISTEN  0  511  0.0.0.0:80    0.0.0.0:*  users:(("apache2",...))
LISTEN  0  70   127.0.0.1:3306 0.0.0.0:* users:(("mysqld",...))
```

| Port | Service | Keterangan |
|------|---------|------------|
| `80` | Apache | Menerima HTTP request dari browser |
| `3306` | MySQL | Database connection (localhost only) |

---

### Cek di Browser

Buka browser dari VM lain di jaringan lab dan akses:

```
http://10.200.200.101
```

Jika Apache berjalan dengan benar, akan muncul halaman **Apache2 Ubuntu Default Page** — ini konfirmasi web server aktif.

---

### Cek Versi yang Terinstall

```bash
# Cek versi Apache
apache2 -v

# Cek versi PHP
php -v

# Cek versi MySQL
mysql --version
```

---

## 🔍 Cara Kerja Stack (Alur Request)

```
Browser (Kali Linux / komputer lain)
        │
        │  HTTP GET http://10.200.200.101/index.php
        ▼
Apache Web Server (port 80)
        │
        │  File .php? → kirim ke PHP interpreter
        ▼
PHP Interpreter (libapache2-mod-php)
        │
        │  Butuh data dari database?
        ▼
MySQL Server (port 3306)
        │
        │  Return data
        ▼
PHP menghasilkan HTML
        │
        │  HTTP Response (HTML)
        ▼
Browser menampilkan halaman
```

---

## 📁 Direktori Penting

| Path | Keterangan |
|------|------------|
| `/var/www/html/` | Document root Apache — taruh file web di sini |
| `/etc/apache2/` | Konfigurasi Apache |
| `/etc/php/` | Konfigurasi PHP |
| `/etc/mysql/` | Konfigurasi MySQL |
| `/var/log/apache2/` | Log access dan error Apache |
| `/var/log/mysql/` | Log MySQL |

---

## 🛠️ Troubleshooting

| Masalah | Penyebab | Solusi |
|---------|----------|--------|
| **Halaman Apache tidak muncul** | Service belum jalan | `sudo systemctl start apache2` |
| **PHP tidak dieksekusi, tampil sebagai teks** | Module libapache2-mod-php belum aktif | `sudo a2enmod php` lalu restart Apache |
| **Error koneksi MySQL dari PHP** | Extension php-mysql belum install | `sudo apt install php-mysql -y` |
| **Port 80 tidak bisa diakses dari VM lain** | Firewall Ubuntu aktif | `sudo ufw allow 80` |
| **MySQL tidak bisa login** | Password root belum diset | `sudo mysql_secure_installation` |

---

## 📌 Kesimpulan

Setelah mengikuti lab ini, kamu telah berhasil:

- ✅ Menginstall Apache sebagai web server
- ✅ Menginstall PHP dan module yang dibutuhkan
- ✅ Menginstall MySQL sebagai database server
- ✅ Memverifikasi semua service berjalan
- ✅ Mengakses halaman default Apache dari browser

Web server stack sudah siap — lanjut ke lab berikutnya untuk men-deploy aplikasi web yang rentan dan memulai demo serangan.

---

## 📚 Referensi

- [Apache HTTP Server Documentation](https://httpd.apache.org/docs/)
- [PHP Official Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Ubuntu Server Guide — LAMP](https://ubuntu.com/server/docs/lamp-applications)

---

*📺 Ikuti tutorialnya di [YouTube](https://youtu.be/nktfpMGrIBc?si=p5zI2Oj4FNt8Xina) | ⭐ Star repo ini jika membantu!*
