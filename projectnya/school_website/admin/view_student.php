<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Get student ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('manage_students.php');
}
 $id = $_GET['id'];

// --- PROSES LOGIKA DI BAWAH INI ---

// 1. Proses Hapus Data (via GET)
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Gunakan prepared statement untuk keamanan
    $sql = "DELETE FROM registrations WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    // Set pesan sukses dalam session (jika Anda menggunakan session flash)
    // $_SESSION['success_message'] = "Data pendaftar berhasil dihapus.";
    
    redirect('manage_students.php'); // Kembali ke halaman utama setelah hapus
}

// 2. Proses Update Status (via POST)
if (isset($_POST['update_status'])) {
    $status = $_POST['status'];

    // Gunakan prepared statement untuk keamanan
    $sql = "UPDATE registrations SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    mysqli_stmt_execute($stmt);
    
    $success_message = "Status berhasil diperbarui!";
}

// 3. Ambil Data Pendaftar untuk Ditampilkan
// Gunakan prepared statement untuk keamanan
 $sql = "SELECT * FROM registrations WHERE id = ?";
 $stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
 $result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // Jika data tidak ditemukan, kembali ke halaman utama
    redirect('manage_students.php');
}

 $student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Brief Detail - Operator Console</title>
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
            transition: all 0.2s;
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
        .student-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .student-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
        }
        .document-preview {
            max-width: 100%;
            max-height: 400px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h4>Admin Panel</h4>
                    <p class="small"><?php echo htmlspecialchars(getSetting('school_name')); ?></p>
                </div>
                <nav class="nav flex-column p-3">
                    <a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                    <a href="manage_profile.php" class="nav-link"><i class="fas fa-crosshairs me-2"></i> Platform Profile</a>
                    <a href="manage_content.php" class="nav-link"><i class="fas fa-cog me-2"></i> Kelola Konten</a>
                    <a href="manage_programs.php" class="nav-link"><i class="fas fa-layer-group me-2"></i> Capabilities</a>
                    <a href="manage_admission_page.php" class="nav-link"><i class="fas fa-clipboard-check me-2"></i> Assessment Briefs</a>
                    <a href="manage_students.php" class="nav-link active"><i class="fas fa-users me-2"></i> Test Subjects</a>
                    <a href="manage_news.php" class="nav-link"><i class="fas fa-newspaper me-2"></i> Kelola Berita</a>
                    <a href="manage_gallery.php" class="nav-link"><i class="fas fa-images me-2"></i> Kelola Galeri</a>
                    <a href="manage_messages.php" class="nav-link"><i class="fas fa-envelope me-2"></i> Pesan Masuk</a>
                    <a href="manage_admin.php" class="nav-link"><i class="fas fa-user-shield me-2"></i> Kelola Admin</a>
                    <a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <!-- Top Bar -->
                <div class="top-bar d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assessment Brief Detail</h5>
                    <div>
                        <a href="manage_students.php" class="btn btn-sm btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i> Lihat Website
                        </a>
                    </div>
                </div>

                <!-- Notifikasi Sukses -->
                <?php if (isset($success_message)) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>
                
                <!-- Kartu Detail Siswa -->
                <div class="student-card">
                    <div class="student-header">
                        <h3><?php echo htmlspecialchars($student['full_name']); ?></h3>
                        <p class="mb-0">NISN: <?php echo htmlspecialchars($student['nisn']); ?></p>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Kolom Kiri: Data Pribadi -->
                            <div class="col-md-8">
                                <h5 class="mb-3">Data Pribadi</h5>
                                <table class="table table-borderless">
                                    <tr><td width="150">Nama Lengkap</td><td width="10">:</td><td><?php echo htmlspecialchars($student['full_name']); ?></td></tr>
                                    <tr><td>NISN</td><td>:</td><td><?php echo htmlspecialchars($student['nisn']); ?></td></tr>
                                    <tr><td>Industry</td><td>:</td><td><?php echo htmlspecialchars($student['previous_school']); ?></td></tr>
                                    <tr><td>Tanggal Lahir</td><td>:</td><td><?php echo date('d F Y', strtotime($student['birth_date'])); ?></td></tr>
                                    <tr><td>Jenis Kelamin</td><td>:</td><td><?php echo $student['gender'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td></tr>
                                    <tr><td>Alamat</td><td>:</td><td><?php echo htmlspecialchars($student['address']); ?></td></tr>
                                    <tr><td>Email</td><td>:</td><td><?php echo htmlspecialchars($student['email']); ?></td></tr>
                                    <tr><td>No. Telepon</td><td>:</td><td><?php echo htmlspecialchars($student['phone']); ?></td></tr>
                                    <tr><td>Submitted At</td><td>:</td><td><?php echo date('d F Y, H:i', strtotime($student['created_at'])); ?></td></tr>
                                </table>
                                
                                <!-- Dokumen Lampiran -->
                                <?php if (!empty($student['documents'])) { ?>
                                    <h5 class="mb-3 mt-4">Dokumen Lampiran</h5>
                                    <div class="document-container">
                                        <?php
                                        $document_path = '../' . $student['documents'];
                                        $document_url = BASE_URL . $student['documents'];
                                        
                                        if (file_exists($document_path)) {
                                            $file_ext = strtolower(pathinfo($document_path, PATHINFO_EXTENSION));
                                            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                echo '<img src="' . htmlspecialchars($document_url) . '" class="document-preview img-fluid" alt="Document">';
                                            } else {
                                                echo '<div class="alert alert-info d-flex align-items-center">';
                                                echo '<i class="fas fa-file-pdf me-3 fa-2x"></i>';
                                                echo '<div>';
                                                echo '<a href="' . htmlspecialchars($document_url) . '" target="_blank" class="btn btn-sm btn-primary me-2">Lihat Dokumen</a>';
                                                echo '<a href="' . htmlspecialchars($document_url) . '" download class="btn btn-sm btn-success">Unduh</a>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<div class="alert alert-warning">Dokumen tidak ditemukan di server.</div>';
                                        }
                                        ?>
                                    </div>
                                <?php } else { ?>
                                    <h5 class="mb-3 mt-4">Dokumen Lampiran</h5>
                                    <p class="text-muted">Tidak ada dokumen yang dilampirkan.</p>
                                <?php } ?>
                            </div>
                            
                            <!-- Kolom Kanan: Status & Aksi -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Assessment Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <?php
                                            $status_class = 'bg-secondary';
                                            if ($student['status'] == 'Menunggu Verifikasi') $status_class = 'bg-warning';
                                            elseif ($student['status'] == 'Diterima') $status_class = 'bg-success';
                                            elseif ($student['status'] == 'Ditolak') $status_class = 'bg-danger';
                                            
                                            echo '<span class="badge ' . $status_class . ' fs-6 p-2">' . htmlspecialchars($student['status']) . '</span>';
                                            ?>
                                        </div>
                                        
                                        <form action="" method="post">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Perbarui Status</label>
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="Menunggu Verifikasi" <?php echo $student['status'] == 'Menunggu Verifikasi' ? 'selected' : ''; ?>>Menunggu Verifikasi</option>
                                                    <option value="Diterima" <?php echo $student['status'] == 'Diterima' ? 'selected' : ''; ?>>Diterima</option>
                                                    <option value="Ditolak" <?php echo $student['status'] == 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                                                </select>
                                            </div>
                                            <button type="submit" name="update_status" class="btn btn-primary w-100">
                                                <i class="fas fa-sync-alt me-1"></i> Perbarui Status
                                            </button>
                                        </form>
                                        
                                        <hr>
                                        
                                        <div class="d-grid">
                                            <a href="?action=delete&id=<?php echo $student['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data pendaftar ini? Tindakan ini tidak dapat dibatalkan.')">
                                                <i class="fas fa-trash me-1"></i> Hapus Data
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>