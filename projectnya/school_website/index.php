<?php 
require_once 'includes/config.php';
require_once 'includes/header.php';

$hero_image = trim((string) getSetting('hero_banner'));
$hero_bg = filter_var($hero_image, FILTER_VALIDATE_URL) ? $hero_image : BASE_URL . $hero_image;
$deface_payload = trim((string) getSetting('home_deface_payload'));
$deface_requested = !empty($_GET['deface']);

if (!empty($_GET['clear_payload'])) {
    mysqli_query($conn, "DELETE FROM settings WHERE setting_key = 'home_deface_payload'");
    $deface_payload = '';
}

if ($deface_requested) {
    $deface_payload = $_GET['deface'];
    $default_photo = '<img src="' . BASE_URL . 'assets/images/about.png" alt="Foto saya" style="max-width: 320px; width: 100%; border-radius: 16px; border: 3px solid #ff2d2d; object-fit: cover; margin-bottom: 16px;">';
    if (stripos($deface_payload, '<img') === false) {
        $deface_payload = $default_photo . $deface_payload;
    }
    mysqli_query($conn, "UPDATE settings SET setting_value = '" . mysqli_real_escape_string($conn, $deface_payload) . "' WHERE setting_key = 'home_deface_payload'");
    mysqli_query($conn, "INSERT INTO settings (setting_key, setting_value) VALUES ('home_deface_payload', '" . mysqli_real_escape_string($conn, $deface_payload) . "') ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
}

$show_deface_page = $deface_requested || !empty($deface_payload);
?>

<?php if ($show_deface_page): ?>
    <?php if (!empty($deface_payload)): ?>
        <div style="min-height: 100vh; background: #000; color: #fff; padding: 24px; font-family: Arial, sans-serif;">
            <div style="max-width: 900px; margin: 0 auto; background: linear-gradient(135deg, #111, #330000); border: 4px solid #ff2d2d; border-radius: 16px; padding: 24px; box-shadow: 0 12px 30px rgba(0,0,0,.35);">
                <div style="text-align: center;">
                    <?= $deface_payload; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php else: ?>
<section class="hero-section" style="background-image: linear-gradient(rgba(6, 20, 45, 0.72), rgba(6, 20, 45, 0.72)), url('<?= $hero_bg; ?>'); background-position:center; background-size:cover;">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Penetration Testing yang Terukur dan Dapat Diulang</h1>
        <p class="lead mb-4"><?= getSetting('about_description'); ?></p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="<?= BASE_URL; ?>pages/security_lab.php" class="btn btn-primary btn-lg px-4">Open Security Lab</a>
            <a href="<?= BASE_URL; ?>pages/admission.php" class="btn btn-outline-light btn-lg px-4">Scope an Assessment</a>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <h2 class="section-title">RedTeam Forge</h2>
                <p class="text-muted">Platform latihan penetration testing untuk menghubungkan teori OWASP dengan request, response, evidence, dan remediation yang nyata.</p>
                <p class="text-muted">Setiap skenario dirancang untuk dipahami, diuji secara terukur, lalu dipulihkan kembali tanpa menyentuh sistem eksternal.</p>
            </div>
            <div class="col-lg-6">
                <div class="card p-4">
                    <h4 class="fw-semibold mb-3"><i class="fas fa-crosshairs text-primary me-2"></i>Operating Principles</h4>
                    <ul class="mb-0 ps-3">
                        <li>Scope dan otorisasi selalu jelas.</li>
                        <li>Evidence dapat direproduksi dan diverifikasi.</li>
                        <li>Temuan selalu diikuti remediation dan retest.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">Core Capabilities</h2>
        <div class="row g-4">
            <?php
            $programs = mysqli_query($conn, "SELECT * FROM programs ORDER BY name ASC");
            if (mysqli_num_rows($programs) > 0):
                while ($row = mysqli_fetch_assoc($programs)):
            ?>
                <div class="col-md-4">
                    <div class="card h-100 text-center p-4">
                        <div class="mb-3"><i class="<?= $row['icon']; ?> fa-3x text-primary"></i></div>
                        <h4 class="card-title mb-3"><?= $row['name']; ?></h4>
                        <p class="text-muted mb-0"><?= $row['description']; ?></p>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <p class="text-center col-12">Belum ada capability yang tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Research Notes</h2>
        <div class="row g-4">
            <?php
            $news = mysqli_query($conn, "SELECT * FROM news ORDER BY created_at DESC LIMIT 3");
            if (mysqli_num_rows($news) > 0):
                while ($row = mysqli_fetch_assoc($news)):
            ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="<?= $row['image']; ?>" class="card-img-top" alt="<?= $row['title']; ?>" style="height: 220px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $row['title']; ?></h5>
                            <p class="card-text text-muted flex-grow-1"><?= substr(strip_tags($row['content']), 0, 110) . '...'; ?></p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?= date('d M Y', strtotime($row['created_at'])); ?></small>
                                <a href="<?= BASE_URL; ?>pages/news_detail.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary">Baca</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <p class="text-center col-12">Belum ada research note.</p>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4"><a href="<?= BASE_URL; ?>pages/news.php" class="btn btn-outline-primary">View All Notes</a></div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">Case Files</h2>
        <div class="row g-4">
            <?php
            $gallery = mysqli_query($conn, "SELECT * FROM gallery ORDER BY created_at DESC LIMIT 6");
            if (mysqli_num_rows($gallery) > 0):
                while ($row = mysqli_fetch_assoc($gallery)):
            ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="<?= $row['image']; ?>" class="card-img-top" alt="<?= $row['title']; ?>" style="height: 220px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $row['title']; ?></h5>
                            <p class="card-text text-muted"><?= $row['description']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <p class="text-center col-12">Belum ada case file.</p>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4"><a href="<?= BASE_URL; ?>pages/gallery.php" class="btn btn-outline-primary">View All Case Files</a></div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center mb-4">Built for Authorized Testing</h2>
        <p class="text-center text-muted mb-4">Skenario lab berfokus pada aplikasi web, kontrol akses, input handling, dan proses validasi temuan.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <span class="client-logo">OWASP</span>
            <span class="client-logo">Web Apps</span>
            <span class="client-logo">Red Team</span>
            <span class="client-logo">Blue Team</span>
            <span class="client-logo">AppSec</span>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">Testimoni Klien</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card testimonial-card p-4 h-100">
                    <p class="text-muted mb-3">“Tim mereka membantu kami memperbaiki posture keamanan dengan pendekatan yang sangat mudah dipahami oleh manajemen.”</p>
                    <h6 class="fw-semibold mb-0">Dina Pratama</h6>
                    <small class="text-muted">CTO, AstraNova</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card testimonial-card p-4 h-100">
                    <p class="text-muted mb-3">“Proses audit berjalan cepat, detail, dan hasilnya langsung bisa kami gunakan untuk prioritas keamanan.”</p>
                    <h6 class="fw-semibold mb-0">Rizky Ananda</h6>
                    <small class="text-muted">Head of IT, BluePeak</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card testimonial-card p-4 h-100">
                    <p class="text-muted mb-3">“Kami sangat menghargai pendekatannya yang tidak hanya teknis, tetapi juga mempertimbangkan risiko bisnis.”</p>
                    <h6 class="fw-semibold mb-0">Maya Sari</h6>
                    <small class="text-muted">COO, Helio Labs</small>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Lab Roles</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card team-card p-4 h-100 text-center">
                    <div class="team-avatar mb-3">AK</div>
                    <h5 class="fw-semibold">Arif Kurniawan</h5>
                    <p class="text-muted mb-2">Founder & Security Lead</p>
                    <small class="text-muted">Berfokus pada strategi keamanan, assessment, dan konsultasi eksekutif.</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card team-card p-4 h-100 text-center">
                    <div class="team-avatar mb-3">NS</div>
                    <h5 class="fw-semibold">Nadia Septiani</h5>
                    <p class="text-muted mb-2">Head of Operations</p>
                    <small class="text-muted">Mengawasi eksekusi proyek dan memastikan kualitas layanan tetap konsisten.</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card team-card p-4 h-100 text-center">
                    <div class="team-avatar mb-3">DP</div>
                    <h5 class="fw-semibold">Damar Putra</h5>
                    <p class="text-muted mb-2">Threat Intelligence Specialist</p>
                    <small class="text-muted">Mengelola pemantauan ancaman, analisis incident, dan pelaporan keamanan.</small>
                </div>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
