<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// --- PERBAIKAN LOGIKA & KEAMANAN ---

// Proses status update (Diterima/Ditolak)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    // Validasi aksi
    if ($action == 'accept') {
        $status = 'Diterima';
    } elseif ($action == 'reject') {
        $status = 'Ditolak';
    } else {
        redirect('manage_students.php');
    }
    
    // GUNAKAN PREPARED STATEMENTS UNTUK KEAMANAN
    $stmt = $conn->prepare("UPDATE registrations SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    
    redirect('manage_students.php');
}

// ✅ Fitur hapus telah dihapus sepenuhnya

// Ambil semua assessment brief
$registrations = mysqli_query($conn, "SELECT * FROM registrations ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Subjects - Operator Console</title>
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
        .table th {
            border-top: none;
            font-weight: 600;
        }
        .badge {
            font-size: 0.8rem;
        }
        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
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
                    <a href="manage_students.php" class="nav-link active">
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
                    <h5>Manage Test Subjects</h5>
                    <div>
                        <a href="export_students.php" class="btn btn-sm btn-success me-2">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </a>
                        <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i> Lihat Website
                        </a>
                    </div>
                </div>
                
                <!-- Filter Tabs -->
                <ul class="nav nav-tabs mb-4" id="studentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">Semua</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">Menunggu Verifikasi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted" type="button">Diterima</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button">Ditolak</button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content" id="studentTabsContent">
                    <!-- Semua -->
                    <div class="tab-pane fade show active" id="all">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NISN</th>
                                                <th>Industry</th>
                                                <th>Email</th>
                                                <th>Telepon</th>
                                                <th>Status</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (mysqli_num_rows($registrations) > 0) { ?>
                                                <?php $no = 1; while ($row = mysqli_fetch_assoc($registrations)) { ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo $row['full_name']; ?></td>
                                                        <td><?php echo $row['nisn']; ?></td>
                                                        <td><?php echo $row['previous_school']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>
                                                        <td><?php echo $row['phone']; ?></td>
                                                        <td>
                                                            <?php
                                                            if ($row['status'] == 'Menunggu Verifikasi') {
                                                                echo '<span class="badge bg-warning">Menunggu Verifikasi</span>';
                                                            } elseif ($row['status'] == 'Diterima') {
                                                                echo '<span class="badge bg-success">Diterima</span>';
                                                            } else {
                                                                echo '<span class="badge bg-danger">Ditolak</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                                        <td class="action-buttons">
                                                            <a href="view_student.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <?php if ($row['status'] == 'Menunggu Verifikasi') { ?>
                                                                <a href="?action=accept&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Accept this assessment brief?')">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                                <a href="?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Reject this assessment brief?')">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="9" class="text-center">Belum ada assessment brief</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Menunggu Verifikasi -->
                    <div class="tab-pane fade" id="pending">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NISN</th>
                                                <th>Asal Sekolah</th>
                                                <th>Email</th>
                                                <th>Telepon</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $pending = mysqli_query($conn, "SELECT * FROM registrations WHERE status = 'Menunggu Verifikasi' ORDER BY created_at DESC");
                                            if (mysqli_num_rows($pending) > 0) { 
                                                $no = 1; 
                                                while ($row = mysqli_fetch_assoc($pending)) { 
                                            ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo $row['full_name']; ?></td>
                                                        <td><?php echo $row['nisn']; ?></td>
                                                        <td><?php echo $row['previous_school']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>
                                                        <td><?php echo $row['phone']; ?></td>
                                                        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                                        <td class="action-buttons">
                                                            <a href="view_student.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="?action=accept&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Terima pendaftaran ini?')">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                            <a href="?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak pendaftaran ini?')">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">Tidak ada data yang menunggu verifikasi</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Diterima -->
                    <div class="tab-pane fade" id="accepted">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NISN</th>
                                                <th>Asal Sekolah</th>
                                                <th>Email</th>
                                                <th>Telepon</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $accepted = mysqli_query($conn, "SELECT * FROM registrations WHERE status = 'Diterima' ORDER BY created_at DESC");
                                            if (mysqli_num_rows($accepted) > 0) { 
                                                $no = 1; 
                                                while ($row = mysqli_fetch_assoc($accepted)) { 
                                            ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo $row['full_name']; ?></td>
                                                        <td><?php echo $row['nisn']; ?></td>
                                                        <td><?php echo $row['previous_school']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>
                                                        <td><?php echo $row['phone']; ?></td>
                                                        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                                        <td class="action-buttons">
                                                            <a href="view_student.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">Tidak ada data yang diterima</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ditolak -->
                    <div class="tab-pane fade" id="rejected">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NISN</th>
                                                <th>Asal Sekolah</th>
                                                <th>Email</th>
                                                <th>Telepon</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $rejected = mysqli_query($conn, "SELECT * FROM registrations WHERE status = 'Ditolak' ORDER BY created_at DESC");
                                            if (mysqli_num_rows($rejected) > 0) { 
                                                $no = 1; 
                                                while ($row = mysqli_fetch_assoc($rejected)) { 
                                            ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo $row['full_name']; ?></td>
                                                        <td><?php echo $row['nisn']; ?></td>
                                                        <td><?php echo $row['previous_school']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>
                                                        <td><?php echo $row['phone']; ?></td>
                                                        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                                        <td class="action-buttons">
                                                            <a href="view_student.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">Tidak ada data yang ditolak</td>
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
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
