<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lang = $_SESSION['lang'] ?? 'en';
include "lang/$lang.php";
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>AstroSchool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-dark text-light">
<div class="bg-stars"></div>
<div class="bg-overlay"></div>

<header class="d-flex justify-content-between align-items-center px-4 py-3" style="background-color: #4a4f57;">
    <!-- Left: Logo + Navigation -->
    <div class="d-flex align-items-center gap-4">
        <div class="d-flex align-items-center">
            <img src="uploads/logo.png" alt="Logo" style="height: 56px; margin-right: 12px;">
            <span style="font-size: 2rem; font-weight: 700; color: white;">
                Astro<span style="color: #00ffff;">School</span>
            </span>
        </div>
        <nav class="d-flex gap-3">
            <a href="index.php"><?= $langData['home'] ?></a>
            <a href="astronomy.php"><?= $langData['astronomy'] ?></a>
            <a href="astrophotography.php"><?= $langData['astrophotography'] ?></a>
            <a href="post.php"><?= $langData['posts'] ?></a>
            <?php if (!empty($_SESSION['is_admin'])): ?>
                <a href="create_post.php"><?= $langData['add_post'] ?></a>
            <?php endif; ?>
        </nav>
    </div>

    <!-- Right: Language + Auth + Theme -->
    <div class="d-flex align-items-center gap-3">
        <!-- Language Dropdown -->
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-info dropdown-toggle" type="button" id="languageDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                🌐 <?= strtoupper($lang) ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                <li><a class="dropdown-item lang-option" href="#" data-lang="en">English</a></li>
                <li><a class="dropdown-item lang-option" href="#" data-lang="lv">Latviešu</a></li>
                <li><a class="dropdown-item lang-option" href="#" data-lang="ru">Русский</a></li>
            </ul>
        </div>

        <!-- Auth Buttons -->
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="logout.php" class="nav-button"><?= $langData['logout'] ?></a>
        <?php else: ?>
            <a href="login.php" class="nav-button"><?= $langData['login_register'] ?></a>
        <?php endif; ?>

        <button id="toggleModeBtn" class="btn btn-outline-info">🌓</button>
    </div>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Theme toggle
document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const toggleBtn = document.getElementById('toggleModeBtn');
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        body.classList.remove('bg-dark', 'text-light');
        body.classList.add('bg-light', 'text-dark');
    }
    toggleBtn?.addEventListener('click', () => {
        const dark = body.classList.contains('bg-dark');
        body.classList.toggle('bg-dark');
        body.classList.toggle('bg-light');
        body.classList.toggle('text-light');
        body.classList.toggle('text-dark');
        localStorage.setItem('theme', dark ? 'light' : 'dark');
    });
});

// Language dropdown
document.querySelectorAll('.lang-option').forEach(item => {
    item.addEventListener('click', e => {
        e.preventDefault();
        const selectedLang = item.dataset.lang;
        window.location.href = `set_language.php?lang=${selectedLang}`;
    });
});
</script>
