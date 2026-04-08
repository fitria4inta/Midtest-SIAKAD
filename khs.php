<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: index.php");
    exit();
}

require_once 'koneksi.php';
require_once 'Mahasiswa.php';
require_once 'template.php';

$db = new Database();
$conn = $db->getConnection();

$mahasiswa = new Mahasiswa($conn);
$mahasiswa->setId($_SESSION['user_id']);
$mahasiswa->setNamaLengkap($_SESSION['nama']);
$mahasiswa->setNim($_SESSION['nim']);

$laporan = $mahasiswa->cetakLaporan();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak KHS | Sistem Akademik</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?= getSidebar($_SESSION['role'], 'khs') ?>
    <?= getNavbar($_SESSION['nama']) ?>
    
    <div class="container">
        <div class="khs-card">
            <div class="header-khs">
                <h2>📋 KARTU HASIL STUDI (KHS)</h2>
            </div>
            <div class="info-mahasiswa">
                <strong>👨‍🎓 Nama:</strong> <?= $_SESSION['nama'] ?> &nbsp;|&nbsp;
                <strong>🆔 NIM:</strong> <?= $_SESSION['nim'] ?>
            </div>
            <?= $laporan ?>
            <div class="btn-group">
                <a href="dashboard.php" class="btn btn-secondary">← Kembali</a>
                <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak KHS</button>
            </div>
        </div>
    </div>
    
    <?= getSidebarScript() ?>
</body>
</html>