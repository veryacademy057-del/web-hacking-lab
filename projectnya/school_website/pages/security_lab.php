<?php
require_once '../includes/config.php';
require_once '../includes/header.php';
?>

<main>
    <section class="lab-hero">
        <div class="container">
            <div class="lab-kicker mb-3">RedTeam Forge / Training Environment</div>
            <div class="lab-status mb-4">LOCAL TARGET ONLINE</div>
            <h1 class="display-4 mb-3">OWASP Security Lab</h1>
            <p class="lead mb-0">A structured workspace for mapping attack surface, validating web vulnerabilities, collecting evidence, and planning remediation.</p>
        </div>
    </section>

    <section class="py-5 bg-light">
    <div class="container">
        <div class="mb-5">
            <div class="eyebrow mb-2">Scenario catalog</div>
            <h2 class="section-title mb-3">Choose a test surface</h2>
            <p class="text-muted" style="max-width: 760px;">Setiap kartu mengarah ke area aplikasi yang relevan. Gunakan target lokal, data sintetis, dan dokumentasikan hasilnya.</p>
            <div class="alert alert-warning text-start mx-auto" style="max-width: 900px;">
                Gunakan hanya di localhost atau lingkungan yang Anda miliki. Jangan memasukkan data pribadi, kredensial nyata, malware, atau target eksternal.
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6" id="sql-injection">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">01 / A03</div><h2 class="h4 mb-0">SQL Injection</h2></div>
                        <span class="badge text-bg-danger">Ada di lab</span>
                    </div>
                    <p>Input request digunakan dalam query SQL pada beberapa alur aplikasi. Amati validasi tipe, pesan error, dan perbedaan antara query mentah dengan prepared statement.</p>
                    <p class="small text-muted mb-3">Area terkait: login admin, detail berita, kontak, dan admission.</p>
                    <a class="btn btn-outline-danger btn-sm" href="<?php echo BASE_URL; ?>admin/login.php?lab=sql-injection">Buka Login untuk Uji Lokal</a>
                </article>
            </div>

            <div class="col-lg-6" id="xss">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">02 / A03</div><h2 class="h4 mb-0">Cross-Site Scripting</h2></div>
                        <span class="badge text-bg-danger">Ada di lab</span>
                    </div>
                    <p>Konten yang berasal dari request atau database dapat dirender sebagai HTML. Demo defacement tersimpan di homepage dapat dibersihkan melalui dashboard admin.</p>
                    <p class="small text-muted mb-3">Area terkait: <a href="<?php echo BASE_URL; ?>index.php">homepage</a> dan konten database.</p>
                    <a class="btn btn-outline-danger btn-sm" href="<?php echo BASE_URL; ?>pages/contact.php?lab=xss">Buka Form Kontak</a>
                </article>
            </div>

            <div class="col-lg-6" id="access-control">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">03 / A01</div><h2 class="h4 mb-0">Broken Access Control</h2></div>
                        <span class="badge text-bg-danger">Ada di lab</span>
                    </div>
                    <p>Bandingkan akses ke halaman admin tanpa sesi valid dan tinjau aksi perubahan data yang masih menggunakan request GET tanpa CSRF token.</p>
                    <p class="small text-muted mb-3">Area terkait: <a href="<?php echo BASE_URL; ?>admin/login.php">login admin</a> dan menu admin.</p>
                    <a class="btn btn-outline-danger btn-sm" href="<?php echo BASE_URL; ?>admin/login.php?lab=access-control">Buka Login Admin</a>
                </article>
            </div>

            <div class="col-lg-6" id="authentication">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">04 / A07</div><h2 class="h4 mb-0">Authentication Failures</h2></div>
                        <span class="badge text-bg-danger">Ada di lab</span>
                    </div>
                    <p>Evaluasi kredensial demo, pesan kegagalan login, session handling, dan ketiadaan rate limiting. Pengujian harus dilakukan manual dan terbatas.</p>
                    <p class="small text-muted mb-3">Jangan melakukan brute force atau menguji akun di luar lab lokal.</p>
                    <a class="btn btn-outline-danger btn-sm" href="<?php echo BASE_URL; ?>admin/login.php?lab=authentication">Buka Login Admin</a>
                </article>
            </div>

            <div class="col-lg-6" id="upload">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">05 / A08</div><h2 class="h4 mb-0">File Upload Integrity</h2></div>
                        <span class="badge text-bg-danger">Ada di lab</span>
                    </div>
                    <p>Helper upload mempercayai nama file dan belum melakukan allowlist MIME, ekstensi, ukuran, serta penyimpanan di luar web root secara memadai.</p>
                    <p class="small text-muted mb-3">Gunakan hanya file TXT, JPG, atau PNG yang tidak berbahaya.</p>
                    <a class="btn btn-outline-danger btn-sm" href="<?php echo BASE_URL; ?>pages/admission.php?lab=file-upload">Buka Form Upload</a>
                </article>
            </div>

            <div class="col-lg-6" id="misconfiguration">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">06 / A05</div><h2 class="h4 mb-0">Security Misconfiguration</h2></div>
                        <span class="badge text-bg-danger">Ada di lab</span>
                    </div>
                    <p>Konfigurasi lokal menampilkan error PHP, menggunakan kredensial database sederhana, dan membuat direktori upload dengan permission longgar.</p>
                    <p class="small text-muted mb-3">Konfigurasi ini tidak boleh digunakan pada server publik.</p>
                    <a class="btn btn-outline-warning btn-sm" href="<?php echo BASE_URL; ?>pages/security_misconfiguration.php">Open Track</a>
                </article>
            </div>

            <div class="col-lg-6" id="cryptography">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">07 / A02</div><h2 class="h4 mb-0">Cryptographic Failures</h2></div>
                        <span class="badge text-bg-warning">Review</span>
                    </div>
                    <p>Tinjau penyimpanan dokumen upload yang dapat diakses melalui web dan pastikan data sensitif tidak memakai kredensial atau file nyata.</p>
                    <p class="small text-muted mb-3">Gunakan data sintetis untuk seluruh latihan.</p>
                    <a class="btn btn-outline-warning btn-sm" href="<?php echo BASE_URL; ?>pages/cryptographic_failures.php">Open Track</a>
                </article>
            </div>

            <div class="col-lg-6" id="components">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">08 / A06</div><h2 class="h4 mb-0">Vulnerable Components</h2></div>
                        <span class="badge text-bg-warning">Inventory</span>
                    </div>
                    <p>Buat inventaris versi PHP, MySQL, Bootstrap, Font Awesome, dan komponen CDN lalu cocokkan dengan advisori resmi.</p>
                    <p class="small text-muted mb-3">Jangan memperbarui dependency produksi tanpa pengujian.</p>
                    <a class="btn btn-outline-warning btn-sm" href="<?php echo BASE_URL; ?>pages/vulnerable_components.php">Open Track</a>
                </article>
            </div>

            <div class="col-lg-6" id="logging">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">09 / A09</div><h2 class="h4 mb-0">Logging & Monitoring</h2></div>
                        <span class="badge text-bg-danger">Ada di lab</span>
                    </div>
                    <p>Bandingkan event yang tercatat di <code>debug_log.txt</code> dengan event keamanan yang belum tercatat, seperti login gagal dan perubahan admin.</p>
                    <p class="small text-muted mb-3">Log seharusnya tidak dapat diakses langsung dari web.</p>
                    <a class="btn btn-outline-danger btn-sm" href="<?php echo BASE_URL; ?>admin/login.php?lab=logging">Buka Area Admin</a>
                </article>
            </div>

            <div class="col-lg-6" id="design">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">10 / A04</div><h2 class="h4 mb-0">Insecure Design</h2></div>
                        <span class="badge text-bg-danger">Ada di lab</span>
                    </div>
                    <p>Alur payload persisten menunjukkan kegagalan desain trust boundary, output context, threat modeling, dan recovery control.</p>
                    <p class="small text-muted mb-3">Reset tersedia melalui fitur pembersihan payload admin.</p>
                    <a class="btn btn-outline-danger btn-sm" href="<?php echo BASE_URL; ?>index.php?deface=LAB-MARKER">Buka Demo Homepage</a>
                </article>
            </div>

            <div class="col-lg-6" id="ssrf">
                <article class="card lab-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div><div class="lab-index mb-2">11 / A10</div><h2 class="h4 mb-0">SSRF Review</h2></div>
                        <span class="badge text-bg-secondary">Tidak ada sink terkonfirmasi</span>
                    </div>
                    <p>Project belum memiliki fitur server-side URL fetching yang terkonfirmasi sebagai SSRF. Bedakan request browser dengan request yang dilakukan server.</p>
                    <p class="small text-muted mb-3">Jangan menguji metadata cloud, localhost service, atau jaringan internal.</p>
                    <a class="btn btn-outline-secondary btn-sm" href="<?php echo BASE_URL; ?>pages/ssrf_review.php">Open Review</a>
                </article>
            </div>
        </div>

        <section class="card mt-5 p-4">
            <h2 class="h4">Reset Lab</h2>
            <ol class="mb-0">
                <li>Gunakan tombol pembersihan payload di dashboard admin.</li>
                <li>Refresh homepage dan pastikan tampilan normal kembali.</li>
                <li>Gunakan data sintetis dan restore database bila diperlukan.</li>
            </ol>
        </section>
    </div>
</section>
</main>

<?php require_once '../includes/footer.php'; ?>
