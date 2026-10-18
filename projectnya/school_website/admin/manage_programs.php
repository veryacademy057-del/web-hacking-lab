<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Proses hapus
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM programs WHERE id = $id");
    redirect('manage_programs.php');
}

// Proses tambah/update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $icon = $_POST['icon'];

    if (isset($_POST['id'])) { // Update
        $id = $_POST['id'];
        mysqli_query($conn, "UPDATE programs SET name='$name', description='$description', icon='$icon' WHERE id = $id");
        $success_message = "Program berhasil diperbarui.";
    } else { // Tambah
        mysqli_query($conn, "INSERT INTO programs (name, description, icon) VALUES ('$name', '$description', '$icon')");
        $success_message = "Program berhasil ditambahkan.";
    }
    redirect('manage_programs.php');
}

// Ambil data untuk edit
 $edit_program = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM programs WHERE id = $id");
    $edit_program = mysqli_fetch_assoc($result);
}

// Ambil semua data
 $programs = mysqli_query($conn, "SELECT * FROM programs ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Program - Admin Panel</title>
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
                    <a href="manage_programs.php" class="nav-link active">
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
                    <h5>Kelola Program Pendidikan</h5>
                    <a href="../pages/program.php" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i> Lihat Halaman
                    </a>
                </div>
                
                <?php if (isset($success_message)) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo $edit_program ? 'Edit' : 'Tambah'; ?> Program</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <?php if ($edit_program) echo '<input type="hidden" name="id" value="' . $edit_program['id'] . '">'; ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Program</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $edit_program['name'] ?? ''; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="icon" class="form-label">Icon (dari Font Awesome)</label>
                                    <input type="text" class="form-control" id="icon" name="icon" value="<?php echo $edit_program['icon'] ?? 'fa-book'; ?>" placeholder="contoh: fa-flask">
                                </div>
                                <div class="col-12">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"><?php echo $edit_program['description'] ?? ''; ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary"><?php echo $edit_program ? 'Update Program' : 'Tambah Program'; ?></button>
                                    <?php if ($edit_program) echo '<a href="manage_programs.php" class="btn btn-secondary">Batal</a>'; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Daftar Program</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Icon</th>
                                        <th>Nama Program</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($p = mysqli_fetch_assoc($programs)) { ?>
                                        <tr>
                                            <td><i class="<?php echo $p['icon']; ?> fa-2x text-primary"></i></td>
                                            <td><?php echo $p['name']; ?></td>
                                            <td><?php echo substr($p['description'],0,80); ?>...</td>
                                            <td>
                                                <a href="?edit=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                <a href="?delete=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
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