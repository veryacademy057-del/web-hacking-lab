<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Proses hapus data
if (isset($_GET['delete_facility'])) {
    $id = $_GET['delete_facility'];
    $result = mysqli_query($conn, "SELECT image FROM facilities WHERE id = $id");
    $facility = mysqli_fetch_assoc($result);
    if ($facility['image'] && file_exists('../' . $facility['image'])) unlink('../' . $facility['image']);
    mysqli_query($conn, "DELETE FROM facilities WHERE id = $id");
    redirect('manage_profile.php#tab-facilities');
}
if (isset($_GET['delete_achievement'])) {
    $id = $_GET['delete_achievement'];
    $result = mysqli_query($conn, "SELECT image FROM achievements WHERE id = $id");
    $achievement = mysqli_fetch_assoc($result);
    if ($achievement['image'] && file_exists('../' . $achievement['image'])) unlink('../' . $achievement['image']);
    mysqli_query($conn, "DELETE FROM achievements WHERE id = $id");
    redirect('manage_profile.php#tab-achievements');
}
if (isset($_GET['delete_extracurricular'])) {
    $id = $_GET['delete_extracurricular'];
    $result = mysqli_query($conn, "SELECT image FROM extracurriculars WHERE id = $id");
    $extracurricular = mysqli_fetch_assoc($result);
    if ($extracurricular['image'] && file_exists('../' . $extracurricular['image'])) unlink('../' . $extracurricular['image']);
    mysqli_query($conn, "DELETE FROM extracurriculars WHERE id = $id");
    redirect('manage_profile.php#tab-extracurriculars');
}

// Proses simpan/update
if (isset($_POST['save_history'])) {
    $history = $_POST['detailed_history'];
    mysqli_query($conn, "UPDATE settings SET setting_value = '$history' WHERE setting_key = 'detailed_history'");
    $success_message = "Platform history berhasil diperbarui.";
}

if (isset($_POST['save_structure'])) {
    if (isset($_FILES['structure_image']) && $_FILES['structure_image']['error'] == 0) {
        $image = uploadFile($_FILES['structure_image'], '../assets/images/');
        if ($image) {
            mysqli_query($conn, "UPDATE settings SET setting_value = '$image' WHERE setting_key = 'school_structure'");
            $success_message = "Struktur organisasi berhasil diperbarui.";
        }
    }
}

// Proses Fasilitas
if (isset($_POST['add_facility'])) {
    $name = $_POST['facility_name'];
    $description = $_POST['facility_description'];
    $image = uploadFile($_FILES['facility_image'], '../assets/images/');
    if($image) {
        mysqli_query($conn, "INSERT INTO facilities (name, description, image) VALUES ('$name', '$description', '$image')");
        $success_message = "Fasilitas berhasil ditambahkan.";
    }
    redirect('manage_profile.php#tab-facilities');
}
if (isset($_POST['update_facility'])) {
    $id = $_POST['facility_id'];
    $name = $_POST['facility_name'];
    $description = $_POST['facility_description'];
    if (isset($_FILES['facility_image']) && $_FILES['facility_image']['error'] == 0) {
        $image = uploadFile($_FILES['facility_image'], '../assets/images/');
        if($image) {
            mysqli_query($conn, "UPDATE facilities SET name='$name', description='$description', image='$image' WHERE id = $id");
        }
    } else {
        mysqli_query($conn, "UPDATE facilities SET name='$name', description='$description' WHERE id = $id");
    }
    $success_message = "Fasilitas berhasil diperbarui.";
    redirect('manage_profile.php#tab-facilities');
}

// Proses Prestasi
if (isset($_POST['add_achievement'])) {
    $title = $_POST['achievement_title'];
    $description = $_POST['achievement_description'];
    $date = $_POST['achievement_date'];
    $image = uploadFile($_FILES['achievement_image'], '../assets/images/');
    mysqli_query($conn, "INSERT INTO achievements (title, description, achievement_date, image) VALUES ('$title', '$description', '$date', '$image')");
    $success_message = "Prestasi berhasil ditambahkan.";
    redirect('manage_profile.php#tab-achievements');
}
if (isset($_POST['update_achievement'])) {
    $id = $_POST['achievement_id'];
    $title = $_POST['achievement_title'];
    $description = $_POST['achievement_description'];
    $date = $_POST['achievement_date'];
    if (isset($_FILES['achievement_image']) && $_FILES['achievement_image']['error'] == 0) {
        $image = uploadFile($_FILES['achievement_image'], '../assets/images/');
        mysqli_query($conn, "UPDATE achievements SET title='$title', description='$description', achievement_date='$date', image='$image' WHERE id = $id");
    } else {
        mysqli_query($conn, "UPDATE achievements SET title='$title', description='$description', achievement_date='$date' WHERE id = $id");
    }
    $success_message = "Prestasi berhasil diperbarui.";
    redirect('manage_profile.php#tab-achievements');
}

// Proses Ekstrakurikuler
if (isset($_POST['add_extracurricular'])) {
    $name = $_POST['extra_name'];
    $description = $_POST['extra_description'];
    $schedule = $_POST['extra_schedule'];
    $image = uploadFile($_FILES['extra_image'], '../assets/images/');
    if($image) {
        mysqli_query($conn, "INSERT INTO extracurriculars (name, description, schedule, image) VALUES ('$name', '$description', '$schedule', '$image')");
        $success_message = "Ekstrakurikuler berhasil ditambahkan.";
    }
    redirect('manage_profile.php#tab-extracurriculars');
}
if (isset($_POST['update_extracurricular'])) {
    $id = $_POST['extra_id'];
    $name = $_POST['extra_name'];
    $description = $_POST['extra_description'];
    $schedule = $_POST['extra_schedule'];
    if (isset($_FILES['extra_image']) && $_FILES['extra_image']['error'] == 0) {
        $image = uploadFile($_FILES['extra_image'], '../assets/images/');
        if($image) {
            mysqli_query($conn, "UPDATE extracurriculars SET name='$name', description='$description', schedule='$schedule', image='$image' WHERE id = $id");
        }
    } else {
        mysqli_query($conn, "UPDATE extracurriculars SET name='$name', description='$description', schedule='$schedule' WHERE id = $id");
    }
    $success_message = "Ekstrakurikuler berhasil diperbarui.";
    redirect('manage_profile.php#tab-extracurriculars');
}

// Ambil Data untuk Edit
 $edit_facility = null;
if (isset($_GET['edit_facility'])) {
    $id = $_GET['edit_facility'];
    $result = mysqli_query($conn, "SELECT * FROM facilities WHERE id = $id");
    $edit_facility = mysqli_fetch_assoc($result);
}

 $edit_achievement = null;
if (isset($_GET['edit_achievement'])) {
    $id = $_GET['edit_achievement'];
    $result = mysqli_query($conn, "SELECT * FROM achievements WHERE id = $id");
    $edit_achievement = mysqli_fetch_assoc($result);
}

 $edit_extracurricular = null;
if (isset($_GET['edit_extracurricular'])) {
    $id = $_GET['edit_extracurricular'];
    $result = mysqli_query($conn, "SELECT * FROM extracurriculars WHERE id = $id");
    $edit_extracurricular = mysqli_fetch_assoc($result);
}

// Ambil Data dari Database
 $history = getSetting('detailed_history');
 $structure_image = getSetting('school_structure');
 $facilities = mysqli_query($conn, "SELECT * FROM facilities ORDER BY name ASC");
 $achievements = mysqli_query($conn, "SELECT * FROM achievements ORDER BY achievement_date DESC");
 $extracurriculars = mysqli_query($conn, "SELECT * FROM extracurriculars ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Platform Profile - Operator Console</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: <?php echo getSetting('theme_color') ?: '#0d47a1'; ?>; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { background-color: var(--primary-color); min-height: 100vh; color: white; }
        .sidebar .nav-link { color: rgba(255, 255, 255, 0.8); padding: 12px 20px; border-radius: 5px; margin-bottom: 5px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: rgba(255, 255, 255, 0.1); color: white; }
        .sidebar .nav-link i { width: 25px; }
        .main-content { padding: 20px; }
        .top-bar { background-color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 15px 20px; margin-bottom: 20px; border-radius: 5px; }
        .nav-tabs .nav-link.active { background-color: var(--primary-color); border-color: var(--primary-color); color: white; }
        .nav-tabs .nav-link { color: var(--primary-color); }
        .preview-image { max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px; }
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
                    <a href="manage_profile.php" class="nav-link active">
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
                    <h5>Manage Platform Profile</h5>
                    <a href="../pages/profile.php" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-external-link-alt me-1"></i> Lihat Halaman Profil</a>
                </div>
                
                <?php if (isset($success_message)) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert"><?php echo $success_message; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php } ?>

                <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-history" type="button">Sejarah & Struktur</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-facilities" type="button">Fasilitas</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-achievements" type="button">Prestasi</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-extracurriculars" type="button">Ekstrakurikuler</button></li>
                </ul>

                <div class="tab-content" id="profileTabsContent">
                    <!-- Tab Sejarah & Struktur -->
                    <div class="tab-pane fade show active" id="tab-history">
                        <div class="card mb-4">
                            <div class="card-header"><h6>Platform History</h6></div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <textarea class="form-control" name="detailed_history" rows="10"><?php echo $history; ?></textarea>
                                    <button type="submit" name="save_history" class="btn btn-primary mt-3">Simpan Sejarah</button>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><h6>Struktur Organisasi (Gambar)</h6></div>
                            <div class="card-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input type="file" class="form-control" name="structure_image" accept="image/*">
                                    <?php if ($structure_image && file_exists('../' . $structure_image)) { ?>
                                        <div class="mt-3"><p class="mb-1">Gambar saat ini:</p><img src="<?php echo BASE_URL . $structure_image; ?>" class="preview-image"></div>
                                    <?php } ?>
                                    <button type="submit" name="save_structure" class="btn btn-primary mt-3">Upload Gambar Struktur</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Fasilitas -->
                    <div class="tab-pane fade" id="tab-facilities">
                        <div class="card mb-4">
                            <div class="card-header"><h6><?php echo $edit_facility ? 'Edit' : 'Tambah'; ?> Fasilitas</h6></div>
                            <div class="card-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <?php if ($edit_facility) echo '<input type="hidden" name="facility_id" value="' . $edit_facility['id'] . '">'; ?>
                                    <div class="row g-3">
                                        <div class="col-md-6"><input type="text" class="form-control" name="facility_name" placeholder="Nama Fasilitas" value="<?php echo $edit_facility['name'] ?? ''; ?>" required></div>
                                        <div class="col-md-6"><input type="file" class="form-control" name="facility_image" accept="image/*" <?php echo !$edit_facility ? 'required' : ''; ?>></div>
                                        <div class="col-12"><textarea class="form-control" name="facility_description" rows="3" placeholder="Deskripsi"><?php echo $edit_facility['description'] ?? ''; ?></textarea></div>
                                        <?php if ($edit_facility && $edit_facility['image']) { ?>
                                            <div class="col-12"><small>Gambar saat ini: <img src="<?php echo BASE_URL . $edit_facility['image']; ?>" width="100"></small></div>
                                        <?php } ?>
                                        <div class="col-12">
                                            <button type="submit" name="<?php echo $edit_facility ? 'update_facility' : 'add_facility'; ?>" class="btn btn-primary"><?php echo $edit_facility ? 'Update Fasilitas' : 'Tambah Fasilitas'; ?></button>
                                            <?php if ($edit_facility) echo '<a href="manage_profile.php#tab-facilities" class="btn btn-secondary">Batal</a>'; ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><h6>Daftar Fasilitas</h6></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead><tr><th>Gambar</th><th>Nama</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
                                        <tbody>
                                            <?php while($f = mysqli_fetch_assoc($facilities)) { ?>
                                                <tr>
                                                    <td><img src="<?php echo BASE_URL . $f['image']; ?>" width="80"></td>
                                                    <td><?php echo $f['name']; ?></td>
                                                    <td><?php echo substr($f['description'],0,50); ?>...</td>
                                                    <td>
                                                        <a href="?edit_facility=<?php echo $f['id']; ?>#tab-facilities" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                        <a href="?delete_facility=<?php echo $f['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Prestasi -->
                    <div class="tab-pane fade" id="tab-achievements">
                        <div class="card mb-4">
                            <div class="card-header"><h6><?php echo $edit_achievement ? 'Edit' : 'Tambah'; ?> Prestasi</h6></div>
                            <div class="card-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <?php if ($edit_achievement) echo '<input type="hidden" name="achievement_id" value="' . $edit_achievement['id'] . '">'; ?>
                                    <div class="row g-3">
                                        <div class="col-md-6"><input type="text" class="form-control" name="achievement_title" placeholder="Judul Prestasi" value="<?php echo $edit_achievement['title'] ?? ''; ?>" required></div>
                                        <div class="col-md-6"><input type="date" class="form-control" name="achievement_date" value="<?php echo $edit_achievement['achievement_date'] ?? ''; ?>" required></div>
                                        <div class="col-12"><textarea class="form-control" name="achievement_description" rows="3" placeholder="Deskripsi"><?php echo $edit_achievement['description'] ?? ''; ?></textarea></div>
                                        <div class="col-md-6"><input type="file" class="form-control" name="achievement_image" accept="image/*"></div>
                                        <?php if ($edit_achievement && $edit_achievement['image']) { ?>
                                            <div class="col-md-6"><small>Gambar saat ini: <img src="<?php echo BASE_URL . $edit_achievement['image']; ?>" width="100"></small></div>
                                        <?php } ?>
                                        <div class="col-12">
                                            <button type="submit" name="<?php echo $edit_achievement ? 'update_achievement' : 'add_achievement'; ?>" class="btn btn-primary"><?php echo $edit_achievement ? 'Update Prestasi' : 'Tambah Prestasi'; ?></button>
                                            <?php if ($edit_achievement) echo '<a href="manage_profile.php#tab-achievements" class="btn btn-secondary">Batal</a>'; ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><h6>Daftar Prestasi</h6></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead><tr><th>Tanggal</th><th>Judul</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
                                        <tbody>
                                            <?php while($a = mysqli_fetch_assoc($achievements)) { ?>
                                                <tr>
                                                    <td><?php echo date('d M Y', strtotime($a['achievement_date'])); ?></td>
                                                    <td><?php echo $a['title']; ?></td>
                                                    <td><?php echo substr($a['description'],0,50); ?>...</td>
                                                    <td>
                                                       <a href="?edit_achievement=<?php echo $a['id']; ?>#tab-achievements" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                        <a href="?delete_achievement=<?php echo $a['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Ekstrakurikuler -->
                    <div class="tab-pane fade" id="tab-extracurriculars">
                        <div class="card mb-4">
                            <div class="card-header"><h6><?php echo $edit_extracurricular ? 'Edit' : 'Tambah'; ?> Kegiatan Ekstrakurikuler</h6></div>
                            <div class="card-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <?php if ($edit_extracurricular) echo '<input type="hidden" name="extra_id" value="' . $edit_extracurricular['id'] . '">'; ?>
                                    <div class="row g-3">
                                        <div class="col-md-4"><input type="text" class="form-control" name="extra_name" placeholder="Nama Kegiatan" value="<?php echo $edit_extracurricular['name'] ?? ''; ?>" required></div>
                                        <div class="col-md-4"><input type="text" class="form-control" name="extra_schedule" placeholder="Jadwal" value="<?php echo $edit_extracurricular['schedule'] ?? ''; ?>"></div>
                                        <div class="col-md-4"><input type="file" class="form-control" name="extra_image" accept="image/*" <?php echo !$edit_extracurricular ? 'required' : ''; ?>></div>
                                        <div class="col-12"><textarea class="form-control" name="extra_description" rows="3" placeholder="Deskripsi"><?php echo $edit_extracurricular['description'] ?? ''; ?></textarea></div>
                                        <?php if ($edit_extracurricular && $edit_extracurricular['image']) { ?>
                                            <div class="col-12"><small>Gambar saat ini: <img src="<?php echo BASE_URL . $edit_extracurricular['image']; ?>" width="100"></small></div>
                                        <?php } ?>
                                        <div class="col-12">
                                            <button type="submit" name="<?php echo $edit_extracurricular ? 'update_extracurricular' : 'add_extracurricular'; ?>" class="btn btn-primary"><?php echo $edit_extracurricular ? 'Update Ekstrakurikuler' : 'Tambah Ekstrakurikuler'; ?></button>
                                            <?php if ($edit_extracurricular) echo '<a href="manage_profile.php#tab-extracurriculars" class="btn btn-secondary">Batal</a>'; ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><h6>Daftar Ekstrakurikuler</h6></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead><tr><th>Gambar</th><th>Nama</th><th>Jadwal</th><th>Aksi</th></tr></thead>
                                        <tbody>
                                            <?php while($e = mysqli_fetch_assoc($extracurriculars)) { ?>
                                                <tr>
                                                    <td><img src="<?php echo BASE_URL . $e['image']; ?>" width="80"></td>
                                                    <td><?php echo $e['name']; ?></td>
                                                    <td><?php echo $e['schedule']; ?></td>
                                                    <td>
                                                        <a href="?edit_extracurricular=<?php echo $e['id']; ?>#tab-extracurriculars" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                        <a href="?delete_extracurricular=<?php echo $e['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
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
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.hash) {
                const tabTrigger = document.querySelector(`a[href="${window.location.hash}"]`);
                if (tabTrigger) {
                    const tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }
        });
    </script>
</body>
</html>