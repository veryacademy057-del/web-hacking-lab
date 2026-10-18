<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Proses hapus berita
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Ambil nama file gambar untuk dihapus dari folder
    $sql = "SELECT image FROM news WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $news_item = mysqli_fetch_assoc($result);
    if ($news_item['image'] && file_exists('../' . $news_item['image'])) {
        unlink('../' . $news_item['image']);
    }
    // Hapus data dari database
    mysqli_query($conn, "DELETE FROM news WHERE id = $id");
    redirect('manage_news.php');
}

// Proses tambah/edit berita
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    if (isset($_POST['id'])) { // Edit
        $id = $_POST['id'];
        // Cek apakah ada gambar baru yang diupload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../assets/images/";
            $image = uploadFile($_FILES['image'], $target_dir);
            if ($image) {
                mysqli_query($conn, "UPDATE news SET title='$title', content='$content', image='$image' WHERE id=$id");
            }
        } else {
            // Jika tidak ada gambar baru, hanya update title dan content
            mysqli_query($conn, "UPDATE news SET title='$title', content='$content' WHERE id=$id");
        }
    } else { // Tambah
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../assets/images/";
            $image = uploadFile($_FILES['image'], $target_dir);
        }
        // Pastikan gambar berhasil diupload sebelum menyimpan ke database
        if ($image) {
            mysqli_query($conn, "INSERT INTO news (title, content, image) VALUES ('$title', '$content', '$image')");
        }
    }
    redirect('manage_news.php');
}

// Ambil data berita untuk diedit
 $edit_news = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM news WHERE id = $id");
    $edit_news = mysqli_fetch_assoc($result);
}

// Ambil semua data berita
 $all_news = mysqli_query($conn, "SELECT * FROM news ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Admin Panel</title>
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
        .news-thumbnail {
            width: 100px;
            height: 75px;
            object-fit: cover;
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
                    <a href="manage_news.php" class="nav-link active">
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
                    <h5>Kelola Berita & Pengumuman</h5>
                    <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i> Lihat Website
                    </a>
                </div>
                
                <!-- Form Tambah/Edit Berita -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo $edit_news ? 'Edit Berita' : 'Tambah Berita Baru'; ?></h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <?php if ($edit_news) echo '<input type="hidden" name="id" value="' . $edit_news['id'] . '">'; ?>
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Berita</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo $edit_news['title'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Isi Berita</label>
                                <textarea class="form-control" id="content" name="content" rows="8" required><?php echo $edit_news['content'] ?? ''; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <?php if ($edit_news && $edit_news['image']) { ?>
                                    <small class="text-muted d-block mt-1">Gambar saat ini: <img src="<?php echo BASE_URL . $edit_news['image']; ?>" class="news-thumbnail" alt="Current Image"></small>
                                <?php } ?>
                            </div>
                            <button type="submit" class="btn btn-primary"><?php echo $edit_news ? 'Update Berita' : 'Simpan Berita'; ?></button>
                            <?php if ($edit_news) echo '<a href="manage_news.php" class="btn btn-secondary">Batal</a>'; ?>
                        </form>
                    </div>
                </div>
                
                <!-- Daftar Berita -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daftar Berita</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($all_news) > 0) { ?>
                                        <?php while ($news = mysqli_fetch_assoc($all_news)) { ?>
                                            <tr>
                                                <td><img src="<?php echo BASE_URL . $news['image']; ?>" class="news-thumbnail" alt="<?php echo $news['title']; ?>"></td>
                                                <td><?php echo $news['title']; ?></td>
                                                <td><?php echo date('d M Y', strtotime($news['created_at'])); ?></td>
                                                <td>
                                                    <a href="?edit=<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                                    <a href="?delete=<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus berita ini?')"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr><td colspan="4" class="text-center">Belum ada berita.</td></tr>
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