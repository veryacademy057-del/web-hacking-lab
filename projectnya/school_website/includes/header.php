<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getSetting('school_name'); ?> | Penetration Testing Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        :root {
            --primary-color: <?php echo getSetting('theme_color') ?: '#0b5fff'; ?>;
            --secondary-color: #f5a623;
        }
        body {
            font-family: 'Poppins', sans-serif;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .hero-section {
            background: linear-gradient(rgba(6, 20, 45, 0.72), rgba(6, 20, 45, 0.72));
            color: white;
            padding: clamp(90px, 7vw, 150px);
            text-align: center;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            opacity: 0.9;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        .footer {
            background-color: #071426;
            color: white;
            padding: 3rem 0 20px;
        }
        .card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .section-title {
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--primary-color);
        }
    </style>
</head>
<body class="app-body">
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #071426;">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <span class="brand-badge me-2">NS</span>
                <?php echo getSetting('school_name'); ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/profile.php">Platform</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/program.php">Methodology</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/news.php">Research Notes</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/gallery.php">Case Files</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/contact.php">Contact</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Security Lab
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/security_lab.php">Ringkasan OWASP Top 10</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/login.php?lab=sql-injection">SQL Injection - Login</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/security_lab.php#xss">Cross-Site Scripting (XSS)</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/security_lab.php#access-control">Broken Access Control</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/cryptographic_failures.php">Cryptographic Failures</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/security_lab.php#design">Insecure Design</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/security_misconfiguration.php">Security Misconfiguration</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/vulnerable_components.php">Vulnerable Components</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/rce_simulator.php">RCE Simulator</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/security_lab.php#authentication">Authentication</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/security_lab.php#upload">File Upload</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/security_lab.php#logging">Logging & Monitoring</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>pages/ssrf_review.php">SSRF Review</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link btn btn-warning text-dark px-3 ms-2" href="<?php echo BASE_URL; ?>pages/admission.php">Scope a Test</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-outline-light px-3 ms-2" href="<?php echo BASE_URL; ?>admin/login.php">Operator Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>