<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Proses hapus pesan
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM contacts WHERE id = $id");
    redirect('manage_messages.php');
}

// Proses tandai sebagai dibaca
if (isset($_GET['read'])) {
    $id = $_GET['read'];
    mysqli_query($conn, "UPDATE contacts SET status = 'read' WHERE id = $id");
    redirect('manage_messages.php');
}

// Ambil semua pesan
 $messages = mysqli_query($conn, "SELECT * FROM contacts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesan Masuk - Admin Panel</title>
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
        .unread {
            font-weight: 600;
        }
        .unread .badge {
            background-color: var(--primary-color);
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
                    <a href="manage_messages.php" class="nav-link active">
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
                    <h5>Kelola Pesan Masuk</h5>
                    <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i> Lihat Website
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daftar Pesan dari Pengunjung</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Subjek</th>
                                        <th>Pesan</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($messages) > 0) { ?>
                                        <?php while ($msg = mysqli_fetch_assoc($messages)) { ?>
                                            <tr class="<?php echo $msg['status'] == 'unread' ? 'unread' : ''; ?>">
                                                <td>
                                                    <?php if ($msg['status'] == 'unread') { ?>
                                                        <span class="badge">Baru</span>
                                                    <?php } else { ?>
                                                        <span class="text-muted"><i class="fas fa-envelope-open"></i></span>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $msg['name']; ?></td>
                                                <td><?php echo $msg['email']; ?></td>
                                                <td><?php echo $msg['subject']; ?></td>
                                                <td><?php echo substr($msg['message'], 50) . '...'; ?></td>
                                                <td><?php echo date('d M Y, H:i', strtotime($msg['created_at'])); ?></td>
                                                <td>
                                                    <!-- Button untuk membuka modal detail -->
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#messageModal" 
                                                            data-name="<?php echo $msg['name']; ?>" 
                                                            data-email="<?php echo $msg['email']; ?>"
                                                            data-subject="<?php echo $msg['subject']; ?>"
                                                            data-message="<?php echo nl2br($msg['message']); ?>"
                                                            data-id="<?php echo $msg['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if ($msg['status'] == 'unread') { ?>
                                                        <a href="?read=<?php echo $msg['id']; ?>" class="btn btn-sm btn-outline-success" title="Tandai sebagai dibaca">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <a href="?delete=<?php echo $msg['id']; ?>" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr><td colspan="7" class="text-center">Belum ada pesan masuk.</td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Detail Pesan -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Detail Pesan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="100"><strong>Nama:</strong></td>
                            <td id="modal-name"></td>
                        </tr>
                        <tr>
                            <td width="100"><strong>Email:</strong></td>
                            <td id="modal-email"></td>
                        </tr>
                        <tr>
                            <td width="100"><strong>Subjek:</strong></td>
                            <td id="modal-subject"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Isi Pesan:</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" id="modal-message"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="modal-id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="markAsReadBtn">Tandai Sudah Dibaca</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageModal = document.getElementById('messageModal');
        const markAsReadBtn = document.getElementById('markAsReadBtn');

        messageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            // Ambil data dari atribut data-*
            document.getElementById('modal-name').innerText = button.getAttribute('data-name');
            document.getElementById('modal-email').innerText = button.getAttribute('data-email');
            
            // --- PERBAIKAN DI SINI ---
            // Yang benar adalah 'data-subject', bukan 'data-subjek'
            document.getElementById('modal-subject').innerText = button.getAttribute('data-subject');
            
            document.getElementById('modal-message').innerHTML = button.getAttribute('data-message');
            document.getElementById('modal-id').value = button.getAttribute('data-id');

            // Sembunyikan tombol "Tandai Sudah Dibaca" jika status sudah read
            const statusBadge = button.closest('tr').querySelector('.badge');
            if (statusBadge) { // Jika ada badge 'Baru', berarti statusnya unread
                markAsReadBtn.style.display = 'inline-block';
            } else { // Jika tidak ada badge, berarti sudah read
                markAsReadBtn.style.display = 'none';
            }
        });

        // Fungsi untuk tombol "Tandai Sudah Dibaca"
        markAsReadBtn.addEventListener('click', function() {
            const messageId = document.getElementById('modal-id').value;
            window.location.href = '?read=' + messageId;
        });
    });
</script>
</body>
</html>