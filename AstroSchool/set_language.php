<?php
session_start();

$allowed = ['en', 'lv', 'ru'];
$lang = $_GET['lang'] ?? 'en';

if (in_array($lang, $allowed)) {
    $_SESSION['lang'] = $lang;
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
