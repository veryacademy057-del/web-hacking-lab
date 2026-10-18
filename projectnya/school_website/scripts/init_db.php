<?php
// scripts/init_db.php
// Usage: php scripts/init_db.php
// Edit the DB credentials below if needed.

$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'school_website';
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . "\n");
}
$mysqli->set_charset($charset);

echo "Connected to MySQL server.\n";

// Create database if not exists
if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET $charset COLLATE {$charset}_unicode_ci")) {
    die("Failed to create database: " . $mysqli->error . "\n");
}

echo "Database `$dbName` ensured.\n";

$mysqli->select_db($dbName);

$queries = [];

$queries[] = "CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "CREATE TABLE IF NOT EXISTS `programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text,
  `icon` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `nisn` varchar(50) DEFAULT NULL,
  `previous_school` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `address` text,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Menunggu Verifikasi',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute create table queries
foreach ($queries as $q) {
    if (!$mysqli->query($q)) {
        echo "Error creating table: " . $mysqli->error . "\n";
    } else {
        echo "Table ensured.\n";
    }
}

// Insert default settings (only if not exist)
$default_settings = [
    'school_name' => 'RedTeam Forge',
    'theme_color' => '#0d47a1',
    'school_logo' => 'assets/images/logo.png',
    'hero_banner' => 'assets/images/hero.jpg',
    'admission_page_banner' => 'assets/images/admission.jpg',
    'admission_page_title' => 'Request Pentest & Security Review',
    'admission_page_description' => 'Kirim brief target dan kebutuhan pengujian untuk menyiapkan assessment yang terukur.',
    'about_description' => 'RedTeam Forge adalah platform latihan penetration testing untuk memahami cara kerja kerentanan web, validasi temuan, dan proses remediation secara aman.',
    'vision' => 'Membuat pembelajaran offensive security yang praktis, terukur, dan bertanggung jawab.',
    'mission' => 'Menyediakan lab lokal dengan skenario rentan, bukti pengujian, dan panduan perbaikan yang dapat diulang.',
    'admission_requirements' => 'Scope pengujian, target lokal, tujuan assessment, akun uji sintetis, dan batasan pengujian.',
    'school_address' => 'Security Operations Lab, Jakarta',
    'school_phone' => '+62 21 555 0199',
    'school_email' => 'hello@redteamforge.local'
];

$stmt = $mysqli->prepare("INSERT INTO settings (setting_key, setting_value) SELECT ?, ? FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM settings WHERE setting_key = ?)");
foreach ($default_settings as $k => $v) {
    $stmt->bind_param('sss', $k, $v, $k);
    if ($stmt->execute()) {
        echo "Setting ensured: $k\n";
    } else {
        echo "Error inserting setting $k: " . $mysqli->error . "\n";
    }
}
$stmt->close();

// Insert sample content for demo purposes
$sample_programs = [
    ['name' => 'Web Application Pentest', 'description' => 'Latihan menemukan, memvalidasi, dan memperbaiki kerentanan aplikasi web.', 'icon' => 'bug'],
    ['name' => 'Threat Modeling', 'description' => 'Pemetaan attack surface, trust boundary, dan prioritas risiko.', 'icon' => 'project-diagram']
];
foreach ($sample_programs as $program) {
    $check = $mysqli->prepare("SELECT id FROM programs WHERE name = ?");
    $check->bind_param('s', $program['name']);
    $check->execute();
    $check->store_result();
    if ($check->num_rows == 0) {
        $ins = $mysqli->prepare("INSERT INTO programs (name, description, icon) VALUES (?, ?, ?)");
        $ins->bind_param('sss', $program['name'], $program['description'], $program['icon']);
        $ins->execute();
        $ins->close();
    }
    $check->close();
}

$news_check = $mysqli->prepare("SELECT id FROM news WHERE title = ?");
$news_title = 'Workflow Pentest yang Reproducible';
$news_check->bind_param('s', $news_title);
$news_check->execute();
$news_check->store_result();
if ($news_check->num_rows == 0) {
    $news_content = 'Pelajari scoping, discovery, validasi temuan, evidence, dan retest secara legal dan terstruktur.';
    $news_image = 'assets/images/news.jpg';
    $news_stmt = $mysqli->prepare("INSERT INTO news (title, content, image) VALUES (?, ?, ?)");
    $news_stmt->bind_param('sss', $news_title, $news_content, $news_image);
    $news_stmt->execute();
    $news_stmt->close();
}
$news_check->close();

$gallery_check = $mysqli->prepare("SELECT id FROM gallery WHERE title = ?");
$gallery_title = 'Kegiatan Lab Keamanan';
$gallery_check->bind_param('s', $gallery_title);
$gallery_check->execute();
$gallery_check->store_result();
if ($gallery_check->num_rows == 0) {
    $gallery_desc = 'Skenario latihan untuk memahami dampak dan mitigasi kerentanan web.';
    $gallery_image = 'assets/images/gallery.jpg';
    $gallery_stmt = $mysqli->prepare("INSERT INTO gallery (title, description, image) VALUES (?, ?, ?)");
    $gallery_stmt->bind_param('sss', $gallery_title, $gallery_desc, $gallery_image);
    $gallery_stmt->execute();
    $gallery_stmt->close();
}
$gallery_check->close();

// Create default admin if not exists
$default_username = 'admin';
$default_password = 'admin123';
$hashed = password_hash($default_password, PASSWORD_DEFAULT);

$check = $mysqli->prepare("SELECT id FROM admins WHERE username = ?");
$check->bind_param('s', $default_username);
$check->execute();
$check->store_result();
if ($check->num_rows == 0) {
    $ins = $mysqli->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $ins->bind_param('ss', $default_username, $hashed);
    if ($ins->execute()) {
        echo "Default admin created: username='admin' password='admin123'\n";
    } else {
        echo "Failed to create default admin: " . $mysqli->error . "\n";
    }
    $ins->close();
} else {
    echo "Admin 'admin' already exists.\n";
}
$check->close();

// Create uploads directories
$dirs = [__DIR__ . '/../assets/uploads', __DIR__ . '/../assets/uploads/documents', __DIR__ . '/../assets/uploads/gallery'];
foreach ($dirs as $d) {
    if (!is_dir($d)) {
        if (mkdir($d, 0777, true)) {
            echo "Created directory: $d\n";
        } else {
            echo "Failed to create directory: $d\n";
        }
    } else {
        echo "Directory exists: $d\n";
    }
}

echo "Done.\n";

$mysqli->close();

?>
