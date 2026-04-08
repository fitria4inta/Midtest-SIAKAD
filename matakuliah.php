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

$query = "SELECT * FROM mata_kuliah ORDER BY semester, kode_mk";
$matakuliah = $conn->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mata Kuliah | Sistem Akademik</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?= getSidebar($_SESSION['role'], 'matakuliah') ?>
    <?= getNavbar($_SESSION['nama']) ?>
    
    <div class="container">
        <div class="card">
            <h2>📚 Daftar Mata Kuliah</h2>
            
            <?php if(count($matakuliah) == 0): ?>
                <div class="empty-message">📭 Belum ada data mata kuliah.</div>
            <?php else: ?>
                <table width="100%">
                    <thead><tr><th>No</th><th>Kode MK</th><th>Nama Mata Kuliah</th><th>SKS</th><th>Semester</th></tr></thead>
                    <tbody>
                        <?php $no=1; foreach($matakuliah as $mk): ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($mk['kode_mk']) ?></td>
                            <td><?= htmlspecialchars($mk['nama_mk']) ?></td>
                            <td style="text-align: center;"><span class="sks-badge"><?= $mk['sks'] ?> SKS</span></td>
                            <td style="text-align: center;">Semester <?= $mk['semester'] ?></td>
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