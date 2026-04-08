<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'koneksi.php';
require_once 'Mahasiswa.php';
require_once 'Dosen.php';
require_once 'template.php';

$db = new Database();
$conn = $db->getConnection();

if($_SESSION['role'] == 'mahasiswa') {
    $user = new Mahasiswa($conn);
    $user->setId($_SESSION['user_id']);
    $user->setNamaLengkap($_SESSION['nama']);
    $user->setNim($_SESSION['nim']);
    $info = $user->getDashboardInfo();
} else {
    $user = new Dosen($conn);
    $user->setId($_SESSION['user_id']);
    $user->setNamaLengkap($_SESSION['nama']);
    $user->setNip($_SESSION['nip']);
    $info = $user->getDashboardInfo();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sistem Akademik</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?= getSidebar($_SESSION['role'], 'dashboard') ?>
    <?= getNavbar($_SESSION['nama']) ?>
    
    <div class="container">
        <div class="welcome-card">
            <div class="dot-pattern"></div>
            <div class="emoji-1">🎓</div>
            <div class="emoji-2">📚</div>
            <div class="character"><?= $_SESSION['role'] == 'mahasiswa' ? '🧑‍🎓' : '👨‍🏫' ?></div>
            <h2>Selamat Datang, <?= $_SESSION['nama'] ?>! <span class="wave-icon">👋</span></h2>
            <p>Silakan pilih menu di bawah ini</p>
            <div class="role-badge"><?= $_SESSION['role'] == 'mahasiswa' ? '🎓 Mahasiswa' : '👨‍🏫 Dosen' ?></div>
        </div>
        
        <div class="menu-grid">
            <?php if($_SESSION['role'] == 'mahasiswa'): ?>
                <a href="khs.php" class="menu-card">
                    <div class="menu-icon">📄</div>
                    <h3>Cetak KHS</h3>
                    <p>Lihat dan cetak Kartu Hasil Studi</p>
                </a>
                <a href="ipk.php" class="menu-card">
                    <div class="menu-icon">📊</div>
                    <h3>Hitung IPK</h3>
                    <p>Lihat perhitungan IPK detail</p>
                </a>
            <?php else: ?>
                <a href="nilai.php" class="menu-card">
                    <div class="menu-icon">✏️</div>
                    <h3>Input Nilai</h3>
                    <p>Input nilai mahasiswa</p>
                </a>
                <a href="daftar_mahasiswa.php" class="menu-card">
                    <div class="menu-icon">👨‍🎓</div>
                    <h3>Manajemen Mahasiswa</h3>
                    <p>Lihat data mahasiswa</p>
                </a>
                <a href="matakuliah.php" class="menu-card">
                    <div class="menu-icon">📚</div>
                    <h3>Manajemen MK</h3>
                    <p>Lihat daftar mata kuliah</p>
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <?= getSidebarScript() ?>
</body>
</html>