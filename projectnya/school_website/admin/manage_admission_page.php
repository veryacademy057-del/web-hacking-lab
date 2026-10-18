<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = sanitize($_POST['admission_page_title']);
    $description = sanitize($_POST['admission_page_description']);
    $requirements = sanitize($_POST['admission_requirements']);

    mysqli_query($conn, "UPDATE settings SET setting_value = '$title' WHERE setting_key = 'admission_page_title'");
    mysqli_query($conn, "UPDATE settings SET setting_value = '$description' WHERE setting_key = 'admission_page_description'");
    mysqli_query($conn, "UPDATE settings SET setting_value = '$requirements' WHERE setting_key = 'admission_requirements'");

    if (isset($_FILES['admission_page_banner']) && $_FILES['admission_page_banner']['error'] == 0) {
        $image = uploadFile($_FILES['admission_page_banner'], '../assets/images/');
        if ($image) {
            mysqli_query($conn, "UPDATE settings SET setting_value = '$image' WHERE setting_key = 'admission_page_banner'");
            $success_message = "Assessment banner berhasil diperbarui.";
        } else {
            $error_message = "Gagal mengupload gambar. Periksa ukuran dan tipe file.";
        }
    } else {
        $success_message = "Assessment page content berhasil diperbarui.";
    }
    redirect('manage_admission_page.php');
}

 $title = getSetting('admission_page_title');
 $description = getSetting('admission_page_description');
 $requirements = getSetting('admission_requirements');
 $banner = getSetting('admission_page_banner');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assessment Page - Operator Console</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: <?php echo getSetting('theme_color') ?: '#0d47a1'; ?>;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: var(--primary-color);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .sidebar .nav-link i {
            width: 25px;
        }
        .main-content {
            padding: 20px;
        }
        .top-bar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .preview-image {
            max-width: 300px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h4>Admin Panel</h4>
                    <p class="small"><?php echo getSetting('school_name'); ?></p>
                </div>
                <nav class="nav flex-column p-3">
                    <a href="dashboard.php" class="nav-link">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="manage_profile.php" class="nav-link">
                        <i class="fas fa-crosshairs me-2"></i> Platform Profile
                    </a>
                    <a href="manage_content.php" class="nav-link">
                        <i class="fas fa-cog me-2"></i> Kelola Konten
                    </a>
                    <a href="manage_programs.php" class="nav-link">
                        <i class="fas fa-layer-group me-2"></i> Capabilities
                    </a>
                    <a href="manage_admission_page.php" class="nav-link active">
                        <i class="fas fa-clipboard-check me-2"></i> Assessment Briefs
                    </a>
                    <a href="manage_students.php" class="nav-link">
                        <i class="fas fa-users me-2"></i> Test Subjects
                    </a>
                    <a href="manage_news.php" class="nav-link">
                        <i class="fas fa-newspaper me-2"></i> Kelola Berita
                    </a>
                    <a href="manage_gallery.php" class="nav-link">
                        <i class="fas fa-images me-2"></i> Kelola Galeri
                    </a>
                    <a href="manage_messages.php" class="nav-link">
                        <i class="fas fa-envelope me-2"></i> Pesan Masuk
                    </a>
                    <a href="manage_admin.php" class="nav-link">
                        <i class="fas fa-user-shield me-2"></i> Kelola Admin
                    </a>
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </nav>
            </div>
            
            <div class="col-md-10 main-content">
                <div class="top-bar d-flex justify-content-between align-items-center">
                    <h5>Manage Assessment Brief Page</h5>
                    <a href="../pages/admission.php" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i> Lihat Halaman
                    </a>
                </div>
                
                <?php if (isset($success_message)) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                <?php if (isset($error_message)) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>

                <div class="card">
                    <div class="card-header">
                        <h6>Assessment Page Content</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="admission_page_title" class="form-label">Judul Halaman</label>
                                    <input type="text" class="form-control" id="admission_page_title" name="admission_page_title" value="<?php echo $title; ?>">
                                </div>
                                <div class="col-12">
                                    <label for="admission_page_description" class="form-label">Deskripsi Utama</label>
                                    <textarea class="form-control" id="admission_page_description" name="admission_page_description" rows="4"><?php echo $description; ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="admission_page_banner" class="form-label">Banner Halaman</label>
                                    <input type="file" class="form-control" id="admission_page_banner" name="admission_page_banner" accept="image/*">
                                    <?php if ($banner && file_exists('../' . $banner)) { ?>
                                        <div class="mt-2">
                                            <p class="mb-1">Banner saat ini:</p>
                                            <img src="<?php echo BASE_URL . $banner; ?>" class="preview-image">
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="admission_requirements" class="form-label">Assessment Requirements</label>
                                    <textarea class="form-control" id="admission_requirements" name="admission_requirements" rows="6"><?php echo $requirements; ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>