<?php
require_once __DIR__ . '/../includes/config.php';

$settings = [
    'school_name' => 'RedTeam Forge',
    'about_description' => 'RedTeam Forge adalah platform latihan penetration testing untuk memahami cara kerja kerentanan web, validasi temuan, dan proses remediation secara aman.',
    'vision' => 'Membuat pembelajaran offensive security yang praktis, terukur, dan bertanggung jawab.',
    'mission' => 'Menyediakan lab lokal dengan skenario rentan, bukti pengujian, dan panduan perbaikan yang dapat diulang.',
    'admission_page_title' => 'Request Pentest & Security Review',
    'admission_page_description' => 'Kirim brief target dan kebutuhan pengujian untuk menyiapkan assessment yang terukur.',
    'admission_requirements' => 'Scope pengujian, target lokal, tujuan assessment, akun uji sintetis, dan batasan pengujian.',
    'school_address' => 'Security Operations Lab, Jakarta',
    'school_phone' => '+62 21 555 0199',
    'school_email' => 'hello@redteamforge.local'
];

$stmt = mysqli_prepare($conn, "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
foreach ($settings as $key => $value) {
    mysqli_stmt_bind_param($stmt, 'ss', $key, $value);
    mysqli_stmt_execute($stmt);
}

mysqli_stmt_close($stmt);
echo "RedTeam Forge branding settings updated.\n";
