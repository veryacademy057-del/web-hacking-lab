# 🗄️ phpMyAdmin & MySQL Setup — Konfigurasi Database Manager via Web

> **Seri Lab:** Web Hacking Lab  
> **YouTube:** [▶️ Tonton Tutorial di YouTube](https://youtu.be/LI_qbWHc-4w?si=sxKDGNiIAEzt24Kv)  
> **Kategori:** `Web Server, MySQL, Database Management`

---

## ⚠️ Disclaimer

> Lab ini dikonfigurasi untuk keperluan **pembelajaran keamanan web**. Konfigurasi user dan password yang ditampilkan hanya untuk environment lab lokal. Jangan gunakan konfigurasi ini di production server.

---

## 📋 Deskripsi

Lab ini mengkonfigurasi **MySQL** dengan basic security hardening, lalu menginstall **phpMyAdmin** sebagai antarmuka web untuk mengelola database. Setelah selesai, database MySQL dapat dikelola langsung dari browser tanpa perlu command line.

---

## 🎬 Video Tutorial

[![phpMyAdmin & MySQL Setup](https://img.youtube.com/vi/LI_qbWHc-4w/maxresdefault.jpg)](https://youtu.be/LI_qbWHc-4w?si=sxKDGNiIAEzt24Kv)

> 📺 **[Tonton di YouTube → phpMyAdmin & MySQL Setup](https://youtu.be/LI_qbWHc-4w?si=sxKDGNiIAEzt24Kv)**

---

## 🧱 Apa itu phpMyAdmin?

**phpMyAdmin** adalah aplikasi berbasis web untuk mengelola database MySQL melalui browser — tanpa perlu mengetik perintah SQL di terminal.

```
Terminal (cara lama)          phpMyAdmin (cara visual)
──────────────────            ──────────────────────
mysql> SHOW DATABASES;   →   Klik "Databases" di browser
mysql> CREATE TABLE ...  →   Klik "Create table"
mysql> SELECT * FROM ... →   Klik nama tabel → Browse
```

---

## 🗺️ Arsitektur

```
Browser (dari VM lain)
        │
        │  http://10.200.200.101/phpmyadmin
        ▼
Apache Web Server
        │
        │  /var/www/html/phpmyadmin (symbolic link)
        ▼
/usr/share/phpmyadmin (file phpMyAdmin)
        │
        │  Koneksi database
        ▼
MySQL Server (127.0.0.1:3306)
```

---

## ⚙️ Langkah-Langkah Konfigurasi

### Langkah 1 — MySQL Secure Installation

Setelah instalasi MySQL, jalankan script hardening bawaan MySQL:

```bash
sudo mysql_secure_installation
```

Script ini melakukan **basic security hardening** dengan menanyakan beberapa konfigurasi:

| Pertanyaan | Jawaban yang Disarankan |
|------------|------------------------|
| Setup VALIDATE PASSWORD component? | `Y` |
| Password strength level (0/1/2) | `1` (Medium) |
| Remove anonymous users? | `Y` |
| Disallow root login remotely? | `Y` |
| Remove test database? | `Y` |
| Reload privilege tables? | `Y` |

> 💡 `mysql_secure_installation` menghapus konfigurasi default MySQL yang berbahaya — seperti anonymous user yang bisa login tanpa password dan database `test` yang bisa diakses siapa saja.

---

### Langkah 2 — Install phpMyAdmin

```bash
sudo apt install phpmyadmin -y
```

Selama proses instalasi akan muncul dialog konfigurasi:

```
Web server to configure automatically:
→ Pilih: apache2 (tekan SPACE untuk centang, ENTER untuk lanjut)

Configure database for phpmyadmin with dbconfig-common?
→ Pilih: Yes

MySQL application password for phpmyadmin:
→ Isi password (catat baik-baik)
```

---

### Langkah 3 — Buat Symbolic Link

phpMyAdmin terinstall di `/usr/share/phpmyadmin` — bukan di document root Apache. Buat symbolic link agar bisa diakses melalui web:

```bash
sudo ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin
```

**Penjelasan perintah:**

| Bagian | Keterangan |
|--------|------------|
| `ln -s` | Buat symbolic link (shortcut di Linux) |
| `/usr/share/phpmyadmin` | Lokasi file phpMyAdmin yang sebenarnya |
| `/var/www/html/phpmyadmin` | Lokasi link yang bisa diakses Apache |

> 💡 **Symbolic link** bekerja seperti shortcut di Windows — Apache membaca dari `/var/www/html/phpmyadmin` tapi sebenarnya mengakses file dari `/usr/share/phpmyadmin`.

---

### Langkah 4 — Restart Apache

```bash
sudo systemctl restart apache2
```

Restart Apache untuk menerapkan semua perubahan konfigurasi yang dilakukan selama instalasi phpMyAdmin.

---

### Langkah 5 — Akses phpMyAdmin

Buka browser dan akses:

```
http://10.200.200.101/phpmyadmin
```

Halaman login phpMyAdmin akan muncul.

---

## 🔐 Konfigurasi User MySQL untuk phpMyAdmin

Login default MySQL menggunakan `root` sering diblokir. Buat user khusus untuk phpMyAdmin:

### Langkah 6 — Masuk ke MySQL sebagai Root

```bash
sudo mysql -u root
```

**Penjelasan perintah:**

| Bagian | Keterangan |
|--------|------------|
| `sudo` | Jalankan dengan hak administrator Linux |
| `mysql` | Buka MySQL client |
| `-u root` | Login ke MySQL menggunakan user root |

Setelah berhasil, muncul prompt MySQL:

```
mysql>
```

---

### Langkah 7 — Buat User Admin untuk phpMyAdmin

```sql
-- Buat user baru
CREATE USER 'admin'@'localhost' IDENTIFIED BY 'Admin@123456!';

-- Berikan semua hak akses
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost' WITH GRANT OPTION;

-- Terapkan perubahan
FLUSH PRIVILEGES;

-- Keluar dari MySQL
EXIT;
```

**Detail user yang dibuat:**

| Field | Nilai |
|-------|-------|
| **Username** | `admin` |
| **Host** | `localhost` |
| **Password** | `Admin@123456!` |
| **Privileges** | ALL PRIVILEGES |

**Penjelasan setiap perintah SQL:**

| Perintah | Fungsi |
|----------|--------|
| `CREATE USER` | Membuat akun user baru di MySQL |
| `IDENTIFIED BY` | Menentukan password user |
| `GRANT ALL PRIVILEGES ON *.*` | Memberikan akses ke semua database dan tabel |
| `WITH GRANT OPTION` | User bisa memberikan privilege ke user lain |
| `FLUSH PRIVILEGES` | Memuat ulang tabel privilege agar perubahan langsung berlaku |

---

### Langkah 8 — Login ke phpMyAdmin

Buka browser, akses `http://10.200.200.101/phpmyadmin`, lalu login:

| Field | Nilai |
|-------|-------|
| **Username** | `admin` |
| **Password** | `Admin@123456!` |

> ✅ Dashboard phpMyAdmin berhasil terbuka — semua database MySQL bisa dikelola dari browser.

---

## 📋 Ringkasan Perintah

| Perintah | Fungsi |
|----------|--------|
| `mysql_secure_installation` | Basic security hardening MySQL |
| `apt install phpmyadmin` | Menginstall phpMyAdmin |
| `ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin` | Membuat symbolic link agar phpMyAdmin dapat diakses Apache |
| `systemctl restart apache2` | Restart Apache untuk menerapkan perubahan |
| `http://10.200.200.101/phpmyadmin` | Path untuk mengakses phpMyAdmin melalui browser |

---

## 🛠️ Troubleshooting

| Masalah | Penyebab | Solusi |
|---------|----------|--------|
| **phpMyAdmin tidak bisa diakses** | Symbolic link belum dibuat | Jalankan perintah `ln -s` di Langkah 3 |
| **Error 404 di /phpmyadmin** | Apache belum direstart | `sudo systemctl restart apache2` |
| **Login gagal dengan user admin** | User belum dibuat | Ulangi Langkah 6-7 |
| **Error: mysqli_connect()** | Extension php-mysql belum install | `sudo apt install php-mysql -y` |
| **phpMyAdmin tidak muncul saat install** | Dialog konfigurasi terlewat | `sudo dpkg-reconfigure phpmyadmin` |
| **Apache tidak bisa start** | Port 80 dipakai proses lain | `sudo ss -tlnp \| grep 80` untuk cek |

---

## 📌 Kesimpulan

Setelah mengikuti lab ini, kamu telah berhasil:

- ✅ Menjalankan `mysql_secure_installation` untuk hardening dasar MySQL
- ✅ Menginstall phpMyAdmin
- ✅ Membuat symbolic link agar phpMyAdmin dapat diakses Apache
- ✅ Membuat user MySQL `admin` dengan full privileges
- ✅ Mengakses phpMyAdmin melalui browser

Database MySQL sekarang bisa dikelola secara visual melalui browser — siap digunakan untuk lab web hacking berikutnya.

---

## 📚 Referensi

- [phpMyAdmin Official Documentation](https://www.phpmyadmin.net/docs/)
- [MySQL Secure Installation Guide](https://dev.mysql.com/doc/refman/8.0/en/mysql-secure-installation.html)
- [Ubuntu Server — phpMyAdmin Setup](https://ubuntu.com/server/docs/databases-mysql)

---

*📺 Ikuti tutorialnya di [YouTube](https://youtu.be/LI_qbWHc-4w?si=sxKDGNiIAEzt24Kv) | ⭐ Star repo ini jika membantu!*
