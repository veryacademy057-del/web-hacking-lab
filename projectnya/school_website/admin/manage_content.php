<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Proses form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_general'])) {
        // Update general settings
        $school_name = $_POST['school_name'];
        $school_address = $_POST['school_address'];
        $school_phone = $_POST['school_phone'];
        $school_email = $_POST['school_email'];
        $about_description = $_POST['about_description'];
        $vision = $_POST['vision'];
        $mission = $_POST['mission'];
        
        // Update settings
        mysqli_query($conn, "UPDATE settings SET setting_value = '$school_name' WHERE setting_key = 'school_name'");
        mysqli_query($conn, "UPDATE settings SET setting_value = '$school_address' WHERE setting_key = 'school_address'");
        mysqli_query($conn, "UPDATE settings SET setting_value = '$school_phone' WHERE setting_key = 'school_phone'");
        mysqli_query($conn, "UPDATE settings SET setting_value = '$school_email' WHERE setting_key = 'school_email'");
        mysqli_query($conn, "UPDATE settings SET setting_value = '$about_description' WHERE setting_key = 'about_description'");
        mysqli_query($conn, "UPDATE settings SET setting_value = '$vision' WHERE setting_key = 'vision'");
        mysqli_query($conn, "UPDATE settings SET setting_value = '$mission' WHERE setting_key = 'mission'");
        
        $success_message = "Pengaturan umum berhasil diperbarui.";
    } elseif (isset($_POST['update_contact'])) {
        $school_address = $_POST['school_address'];
        $school_phone = $_POST['school_phone'];
        $school_email = $_POST['school_email'];
        $map_embed = $_POST['contact_map_embed'];
        
        mysqli_query($conn, "UPDATE settings SET setting_value = '$school_address' WHERE setting_key = 'school_address'");
        mysqli_query($conn, "UPDATE settings SET setting_value = '$school_phone' WHERE setting_key = 'school_phone'");
        mysqli_query($conn, "UPDATE settings SET setting_value = '$school_email' WHERE setting_key = 'school_email'");
        mysqli_query($conn, "UPDATE settings SET setting_value = '$map_embed' WHERE setting_key = 'contact_map_embed'");
        
        $success_message = "Informasi kontak berhasil diperbarui.";
    } elseif (isset($_POST['update_appearance'])) {
        // Update appearance settings
        $theme_color = $_POST['theme_color'];
        
        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $logo = uploadFile($_FILES['logo'], '../assets/images/');
            if ($logo) {
                mysqli_query($conn, "UPDATE settings SET setting_value = '$logo' WHERE setting_key = 'school_logo'");
            }
        }
        
        // Handle hero banner upload
        if (isset($_FILES['hero_banner']) && $_FILES['hero_banner']['error'] == 0) {
            $hero_banner = uploadFile($_FILES['hero_banner'], '../assets/images/');
            if ($hero_banner) {
                mysqli_query($conn, "UPDATE settings SET setting_value = '$hero_banner' WHERE setting_key = 'hero_banner'");
            }
        }
        
        // Update theme color
        mysqli_query($conn, "UPDATE settings SET setting_value = '$theme_color' WHERE setting_key = 'theme_color'");
        
        $success_message = "Tampilan berhasil diperbarui.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Konten - Admin Panel</title>
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
        .nav-tabs .nav-link.active {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        .nav-tabs .nav-link {
            color: var(--primary-color);
        }
        .preview-image {
            max-width: 200px;
            max-height: 150px;
            border: 1px solid #ddd;
            border-radius: 5px;
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
                    <a href="manage_content.php" class="nav-link active">
                        <i class="fas fa-cog me-2"></i> Kelola Konten
                    </a>
                    <a href="manage_programs.php" class="nav-link">
                        <i class="fas fa-layer-group me-2"></i> Capabilities
                    </a>
                    <a href="manage_admission_page.php" class="nav-link">
                        <i class="fas fa-clipboard-check me-2"></i> Assessment Briefs
                    </a>
                    <a href="manage_students.php" class="nav-link">
                        <i class="fas fa-users me-2"></i> Test Subjects
                    </a>
                    <a href="manage_news.php" class="nav-link">
                        <i class="fas fa-file-signature me-2"></i> Research Notes
                    </a>
                    <a href="manage_gallery.php" class="nav-link">
                        <i class="fas fa-folder-open me-2"></i> Case Files
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
                    <h5>Manage Security Lab Content</h5>
                    <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i> Lihat Website
                    </a>
                </div>
                
                <?php if (isset($success_message)) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>
                
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="contentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">Pengaturan Umum</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">Info Kontak</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button">Tampilan</button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content" id="contentTabsContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="school_name" class="form-label">Platform Name</label>
                                            <input type="text" class="form-control" id="school_name" name="school_name" value="<?php echo getSetting('school_name'); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="school_email" class="form-label">Operations Email</label>
                                            <input type="email" class="form-control" id="school_email" name="school_email" value="<?php echo getSetting('school_email'); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="school_phone" class="form-label">Operations Phone</label>
                                            <input type="tel" class="form-control" id="school_phone" name="school_phone" value="<?php echo getSetting('school_phone'); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="school_address" class="form-label">Operations Address</label>
                                            <input type="text" class="form-control" id="school_address" name="school_address" value="<?php echo getSetting('school_address'); ?>">
                                        </div>
                                        <div class="col-12">
                                            <label for="about_description" class="form-label">Platform Description</label>
                                            <textarea class="form-control" id="about_description" name="about_description" rows="3"><?php echo getSetting('about_description'); ?></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="vision" class="form-label">Visi</label>
                                            <textarea class="form-control" id="vision" name="vision" rows="2"><?php echo getSetting('vision'); ?></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="mission" class="form-label">Misi</label>
                                            <textarea class="form-control" id="mission" name="mission" rows="4"><?php echo getSetting('mission'); ?></textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" name="update_general" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Settings -->
                    <div class="tab-pane fade" id="contact">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="school_address" class="form-label">Operations Address</label>
                                            <textarea class="form-control" id="school_address" name="school_address" rows="2"><?php echo getSetting('school_address'); ?></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="school_phone" class="form-label">Telepon</label>
                                            <input type="tel" class="form-control" id="school_phone" name="school_phone" value="<?php echo getSetting('school_phone'); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="school_email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="school_email" name="school_email" value="<?php echo getSetting('school_email'); ?>">
                                        </div>
                                        <div class="col-12">
                                            <label for="contact_map_embed" class="form-label">Embed Peta Google Maps</label>
                                            <textarea class="form-control" id="contact_map_embed" name="contact_map_embed" rows="4"><?php echo getSetting('contact_map_embed'); ?></textarea>
                                            <small>Pergi ke Google Maps, klik "Bagikan" -> "Embed peta", lalu salin kode HTML-nya di sini.</small>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" name="update_contact" class="btn btn-primary">Simpan Info Kontak</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Appearance Settings -->
                    <div class="tab-pane fade" id="appearance">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="theme_color" class="form-label">Warna Tema</label>
                                            <div class="input-group">
                                                <input type="color" class="form-control form-control-color" id="theme_color" name="theme_color" value="<?php echo getSetting('theme_color') ?: '#0d47a1'; ?>">
                                                <input type="text" class="form-control" value="<?php echo getSetting('theme_color') ?: '#0d47a1'; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="logo" class="form-label">Platform Logo</label>
                                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                            <div class="mt-2">
                                                <small>Logo saat ini:</small>
                                                <div class="mt-1">
                                                    <?php 
                                                    $logo = getSetting('school_logo');
                                                    if ($logo && file_exists('../' . $logo)) {
                                                        echo '<img src="' . BASE_URL . $logo . '" class="preview-image" alt="Logo">';
                                                    } else {
                                                        echo '<span class="text-muted">Belum ada logo</span>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="hero_banner" class="form-label">Banner Halaman Utama</label>
                                            <input type="file" class="form-control" id="hero_banner" name="hero_banner" accept="image/*">
                                            <div class="mt-2">
                                                <small>Banner saat ini:</small>
                                                <div class="mt-1">
                                                    <?php 
                                                    $hero_banner = getSetting('hero_banner');
                                                    if ($hero_banner && file_exists('../' . $hero_banner)) {
                                                        echo '<img src="' . BASE_URL . $hero_banner . '" class="preview-image" alt="Hero Banner">';
                                                    } else {
                                                        echo '<span class="text-muted">Belum ada banner</span>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" name="update_appearance" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update color input text when color picker changes
        document.getElementById('theme_color').addEventListener('input', function() {
            this.nextElementSibling.value = this.value;
        });
    </script>
</body>
</html>