<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center">About RedTeam Forge</h2>
        <div class="row mt-5 g-4">
            <div class="col-lg-7">
                <div class="card h-100 p-4">
                    <h4 class="fw-semibold mb-3"><i class="fas fa-crosshairs text-primary me-2"></i>Platform Profile</h4>
                    <p class="text-muted">RedTeam Forge adalah penetration-testing training lab yang menggabungkan skenario OWASP, alur evidence, dan kontrol pemulihan dalam satu aplikasi lokal.</p>
                    <p class="text-muted">Platform ini membantu learner memahami cara berpikir tester: memahami scope, memvalidasi temuan, menilai dampak, dan menyusun remediation.</p>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card h-100 p-4">
                    <h4 class="fw-semibold mb-3"><i class="fas fa-eye text-primary me-2"></i>Visi</h4>
                    <p class="text-muted"><?php echo getSetting('vision'); ?></p>
                    <h4 class="fw-semibold mt-4"><i class="fas fa-bullseye text-primary me-2"></i>Misi</h4>
                    <p class="text-muted"><?php echo nl2br(getSetting('mission')); ?></p>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-lg-6">
                <div class="card h-100 p-4">
                    <h4 class="fw-semibold mb-3"><i class="fas fa-history text-primary me-2"></i>Lab Mission</h4>
                    <p class="text-muted"><?php echo nl2br(getSetting('detailed_history')); ?></p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 p-4">
                    <h4 class="fw-semibold mb-3"><i class="fas fa-cogs text-primary me-2"></i>Testing Domains</h4>
                    <ul class="mb-0 ps-3 text-muted">
                        <li>Penetration testing dan assessment aplikasi web.</li>
                        <li>Validasi access control dan authentication.</li>
                        <li>Secure design, logging, dan remediation planning.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center">Why This Lab Exists</h2>
        <div class="row g-4 mt-4">
            <div class="col-md-4"><div class="card p-4 h-100"><h5 class="fw-semibold">Authorized Scope</h5><p class="text-muted mb-0">Semua latihan berjalan pada target lokal dengan batasan yang jelas.</p></div></div>
            <div class="col-md-4"><div class="card p-4 h-100"><h5 class="fw-semibold">Evidence First</h5><p class="text-muted mb-0">Setiap temuan harus dapat direproduksi dan dijelaskan dampaknya.</p></div></div>
            <div class="col-md-4"><div class="card p-4 h-100"><h5 class="fw-semibold">Fix and Retest</h5><p class="text-muted mb-0">Pembelajaran berakhir pada remediation, verifikasi, dan dokumentasi.</p></div></div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center mb-4">Lab Roles</h2>
        <p class="text-center text-muted mb-5">Simulasi peran untuk memahami kolaborasi antara tester, developer, dan owner sistem.</p>
        <div class="row g-4">
            <div class="col-md-4"><div class="card p-4 h-100 text-center"><div class="team-avatar mb-3">RT</div><h5 class="fw-semibold">Red Team</h5><p class="text-muted mb-0">Menemukan dan memvalidasi risiko secara terotorisasi.</p></div></div>
            <div class="col-md-4"><div class="card p-4 h-100 text-center"><div class="team-avatar mb-3">BT</div><h5 class="fw-semibold">Blue Team</h5><p class="text-muted mb-0">Mendeteksi, merespons, dan memperkuat kontrol.</p></div></div>
            <div class="col-md-4"><div class="card p-4 h-100 text-center"><div class="team-avatar mb-3">PO</div><h5 class="fw-semibold">Platform Owner</h5><p class="text-muted mb-0">Menentukan scope, acceptance criteria, dan prioritas perbaikan.</p></div></div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-4">Lab Metrics</h2>
        <div class="row g-4 mt-2">
            <div class="col-md-4"><div class="stats-card h-100"><h4 class="fw-bold text-primary mb-1">10</h4><p class="mb-0 text-muted">OWASP categories mapped</p></div></div>
            <div class="col-md-4"><div class="stats-card h-100"><h4 class="fw-bold text-primary mb-1">1</h4><p class="mb-0 text-muted">Isolated local target</p></div></div>
            <div class="col-md-4"><div class="stats-card h-100"><h4 class="fw-bold text-primary mb-1">100%</h4><p class="mb-0 text-muted">Evidence-driven exercises</p></div></div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>