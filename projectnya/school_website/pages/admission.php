<?php require_once '../includes/config.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $nisn = $_POST['nisn'];
    $previous_school = $_POST['previous_school'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    $documents = '';
    if (isset($_FILES['documents']) && $_FILES['documents']['error'] == 0) {
        $target_dir = "../assets/uploads/documents/";
        $documents = uploadFile($_FILES['documents'], $target_dir);
    }
    
    $sql = "INSERT INTO registrations (full_name, nisn, previous_school, birth_date, gender, address, email, phone, documents) 
            VALUES ('$full_name', '$nisn', '$previous_school', '$birth_date', '$gender', '$address', '$email', '$phone', '$documents')";
    
    if (mysqli_query($conn, $sql)) {
        $success_message = "Permintaan konsultasi Anda telah diterima. Tim kami akan menghubungi Anda dalam 1x24 jam.";
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
}
?>

<?php require_once '../includes/header.php'; ?>

<section class="hero-section-admission" style="background: linear-gradient(rgba(7, 20, 38, 0.7), rgba(7, 20, 38, 0.7)), url('<?php echo filter_var(getSetting('admission_page_banner'), FILTER_VALIDATE_URL) ? getSetting('admission_page_banner') : BASE_URL . getSetting('admission_page_banner'); ?>') center/cover no-repeat; color: white; padding: 110px 0; text-align: center;">
    <div class="container">
        <h1 class="display-5 fw-bold mb-4"><?php echo getSetting('admission_page_title'); ?></h1>
        <p class="lead mb-0"><?php echo getSetting('admission_page_description'); ?></p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h4 class="card-title text-center mb-4">Formulir Konsultasi</h4>
                        <?php if (isset($success_message)) { ?><div class="alert alert-success"><?php echo $success_message; ?></div><?php } ?>
                        <?php if (isset($error_message)) { ?><div class="alert alert-danger"><?php echo $error_message; ?></div><?php } ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Contact Name *</label><input type="text" class="form-control" name="full_name" required></div>
                                <div class="col-md-6"><label class="form-label">Organization *</label><input type="text" class="form-control" name="nisn" required></div>
                                <div class="col-md-6"><label class="form-label">Industry *</label><input type="text" class="form-control" name="previous_school" required></div>
                                <div class="col-md-6"><label class="form-label">Target Date *</label><input type="date" class="form-control" name="birth_date" required></div>
                                <div class="col-md-6"><label class="form-label">Assessment Type *</label><select class="form-select" name="gender"><option value="Web Pentest">Web Application Pentest</option><option value="API Pentest">API Security Review</option><option value="Threat Modeling">Threat Modeling</option><option value="Retest">Remediation Retest</option></select></div>
                                <div class="col-md-6"><label class="form-label">Phone *</label><input type="tel" class="form-control" name="phone" required></div>
                                <div class="col-12"><label class="form-label">Email *</label><input type="email" class="form-control" name="email" required></div>
                                <div class="col-12"><label class="form-label">Scope & Objectives *</label><textarea class="form-control" name="address" rows="4" required placeholder="Target, in-scope endpoints, test goals, and constraints"></textarea></div>
                                <div class="col-12"><label class="form-label">Upload Scope Document</label><input type="file" class="form-control" name="documents" accept=".pdf,.jpg,.jpeg,.png"><div class="form-text">Use synthetic data only. Format: PDF, JPG, PNG.</div></div>
                                <div class="col-12 text-center"><button type="submit" class="btn btn-primary btn-lg px-5">Create Assessment Brief</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container"><div class="row justify-content-center"><div class="col-lg-8"><div class="card"><div class="card-body"><h4 class="card-title">Yang Bisa Kami Siapkan</h4><p class="text-muted mb-0"><?php echo getSetting('admission_requirements'); ?></p></div></div></div></div></div>
</section>

<?php require_once '../includes/footer.php'; ?>