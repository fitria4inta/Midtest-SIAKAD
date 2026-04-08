<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'dosen') {
    header("Location: index.php");
    exit();
}

require_once 'koneksi.php';
require_once 'template.php';

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT id, nama_lengkap, nim_nip FROM users WHERE role = 'mahasiswa' ORDER BY nim_nip";
$stmt = $conn->prepare($query);
$stmt->execute();
$mahasiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa | Sistem Akademik</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?= getSidebar($_SESSION['role'], 'daftar_mahasiswa') ?>
    <?= getNavbar($_SESSION['nama']) ?>
    
    <div class="container">
        <div class="card">
            <h2>👨‍🎓 Daftar Mahasiswa</h2>
            
            <?php if(count($mahasiswa) == 0): ?>
                <div class="empty-message">📭 Belum ada data mahasiswa.</div>
            <?php else: ?>
                <table width="100%">
                    <thead><tr><th>No</th><th>NIM</th><th>Nama Lengkap</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php $no=1; foreach($mahasiswa as $m): ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($m['nim_nip']) ?></td>
                            <td><?= htmlspecialchars($m['nama_lengkap']) ?></td>
                            <td style="text-align: center;"><span class="sks-badge" style="background:#4CAF50;">Aktif</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="dashboard.php" class="btn btn-secondary">← Kembali</a>
            </div>
        </div>
    </div>
    
    <?= getSidebarScript() ?>
</body>
</html>