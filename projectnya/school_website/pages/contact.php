<?php require_once '../includes/config.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $sql = "INSERT INTO contacts (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
    
    if (mysqli_query($conn, $sql)) {
        $success_message = "Pesan Anda telah terkirim. Tim kami akan segera menghubungi Anda.";
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
}
?>

<?php require_once '../includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center">Open a Testing Conversation</h2>
        <p class="text-center text-muted mx-auto" style="max-width: 760px;">Gunakan form ini untuk mencatat scope, tujuan pengujian, dan kebutuhan security review pada lingkungan yang terotorisasi.</p>
        <div class="row mt-5 g-4">
            <div class="col-lg-4"><div class="card h-100 p-4"><h5 class="fw-semibold"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Alamat</h5><p class="text-muted mb-0"><?php echo getSetting('school_address'); ?></p></div></div>
            <div class="col-lg-4"><div class="card h-100 p-4"><h5 class="fw-semibold"><i class="fas fa-phone me-2 text-primary"></i>Telepon</h5><p class="text-muted mb-0"><?php echo getSetting('school_phone'); ?></p></div></div>
            <div class="col-lg-4"><div class="card h-100 p-4"><h5 class="fw-semibold"><i class="fas fa-envelope me-2 text-primary"></i>Email</h5><p class="text-muted mb-0"><?php echo getSetting('school_email'); ?></p></div></div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-4">Assessment Brief</h5>
                        <?php if (isset($success_message)) { ?><div class="alert alert-success"><?php echo $success_message; ?></div><?php } ?>
                        <?php if (isset($error_message)) { ?><div class="alert alert-danger"><?php echo $error_message; ?></div><?php } ?>
                        <form action="" method="post">
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Nama Lengkap</label><input type="text" class="form-control" name="name" required></div>
                                <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
                                <div class="col-12"><label class="form-label">Subjek</label><input type="text" class="form-control" name="subject" required></div>
                                <div class="col-12"><label class="form-label">Pesan</label><textarea class="form-control" name="message" rows="5" required></textarea></div>
                                <div class="col-12 text-center"><button type="submit" class="btn btn-primary px-5">Submit Brief</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>