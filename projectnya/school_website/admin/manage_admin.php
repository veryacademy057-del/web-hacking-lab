<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Proses hapus admin
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    // Cegah admin menghapus akunnya sendiri
    if ($id_to_delete != $_SESSION['admin_id']) {
        mysqli_query($conn, "DELETE FROM admins WHERE id = $id_to_delete");
        $success_message = "Admin berhasil dihapus.";
    } else {
        $error_message = "Anda tidak dapat menghapus akun Anda sendiri.";
    }
    redirect('manage_admin.php');
}

// Proses tambah admin baru
if (isset($_POST['add_admin'])) {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    // Cek apakah username sudah ada
    $check_user = mysqli_query($conn, "SELECT id FROM admins WHERE username = '$username'");
    if (mysqli_num_rows($check_user) > 0) {
        $error_message = "Username '$username' sudah digunakan. Silakan pilih username lain.";
    } else {
        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO admins (username, password) VALUES ('$username', '$hashed_password')");
        $success_message = "Admin baru berhasil ditambahkan.";
    }
    redirect('manage_admin.php');
}

// Proses ubah password sendiri
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $admin_id = $_SESSION['admin_id'];

    // Ambil password admin saat ini dari database
    $result = mysqli_query($conn, "SELECT password FROM admins WHERE id = $admin_id");
    $admin = mysqli_fetch_assoc($result);

    // Verifikasi password lama
    if (password_verify($current_password, $admin['password'])) {
        // Hash password baru
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE admins SET password = '$new_hashed_password' WHERE id = $admin_id");
        $success_message = "Password berhasil diubah.";
    } else {
        $error_message = "Password lama yang Anda masukkan salah.";
    }
    redirect('manage_admin.php');
}

// Ambil semua data admin
 $admins = mysqli_query($conn, "SELECT id, username, created_at FROM admins ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin - Admin Panel</title>
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
                    <a href="manage_admission_page.php" class="nav-link">
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
                    <a href="manage_admin.php" class="nav-link active">
                        <i class="fas fa-user-shield me-2"></i> Kelola Admin
                    </a>
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </nav>
            </div>
            
            <div class="col-md-10 main-content">
                <div class="top-bar d-flex justify-content-between align-items-center">
                    <h5>Kelola Admin</h5>
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
                
                <?php if (isset($error_message)) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>

                <div class="row">
                    <!-- Form Tambah Admin Baru -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-user-plus me-2"></i>Tambah Admin Baru</h6>
                            </div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <button type="submit" name="add_admin" class="btn btn-primary btn-sm">Tambah Admin</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Form Ubah Password Saya -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-key me-2"></i>Ubah Password Saya</h6>
                            </div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Password Lama</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    </div>
                                    <button type="submit" name="change_password" class="btn btn-warning btn-sm">Ubah Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Admin -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Admin</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($admins) > 0) { ?>
                                        <?php while ($admin_data = mysqli_fetch_assoc($admins)) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo $admin_data['username']; ?>
                                                    <?php if ($admin_data['id'] == $_SESSION['admin_id']) echo '<span class="badge bg-success ms-2">Anda</span>'; ?>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($admin_data['created_at'])); ?></td>
                                                <td>
                                                    <?php if ($admin_data['id'] != $_SESSION['admin_id']) { ?>
                                                        <a href="?delete=<?php echo $admin_data['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus admin ini?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a>
                                                    <?php } else { ?>
                                                        <span class="text-muted">Tidak ada aksi</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr><td colspan="3" class="text-center">Tidak ada data admin.</td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>