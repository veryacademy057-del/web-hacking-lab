<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Set header untuk download file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="assessment_briefs_' . date('Y-m-d') . '.csv"');

// Buat file handle untuk output
 $output = fopen('php://output', 'w');

// Tambahkan BOM (Byte Order Mark) agar Excel membaca karakter UTF-8 dengan benar
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Header CSV (Nama Kolom)
fputcsv($output, [
    'Assessment ID',
    'Nama Lengkap',
    'NISN',
    'Industry',
    'Tanggal Lahir',
    'Jenis Kelamin',
    'Alamat',
    'Email',
    'No. HP',
    'Status',
    'Submitted At'
]);

// Ambil data siswa dari database
// Anda bisa menambahkan WHERE clause di sini jika ingin mengekspor data yang sudah difilter
// Contoh: WHERE status = 'Diterima'
 $sql = "SELECT * FROM registrations ORDER BY created_at DESC";
 $result = mysqli_query($conn, $sql);

// Isi data CSV
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Siapkan data untuk setiap baris
        $status_text = '';
        switch ($row['status']) {
            case 'Menunggu Verifikasi':
                $status_text = 'Menunggu Verifikasi';
                break;
            case 'Diterima':
                $status_text = 'Diterima';
                break;
            case 'Ditolak':
                $status_text = 'Ditolak';
                break;
            default:
                $status_text = $row['status'];
        }

        fputcsv($output, [
            $row['id'],
            $row['full_name'],
            $row['nisn'],
            $row['previous_school'],
            date('d/m/Y', strtotime($row['birth_date'])),
            ($row['gender'] == 'L') ? 'Laki-laki' : 'Perempuan',
            $row['address'],
            $row['email'],
            $row['phone'],
            $status_text,
            date('d/m/Y H:i', strtotime($row['created_at']))
        ]);
    }
}

// Tutup file handle
fclose($output);

exit();
?>