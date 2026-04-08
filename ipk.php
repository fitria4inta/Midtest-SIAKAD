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

$ipk = $mahasiswa->hitungIPK();
$nilai = $mahasiswa->getNilai();

$total_sks = 0;
$total_bobot = 0;
foreach($nilai as $n) {
    $bobot = 0;
    if($n['nilai_angka'] >= 85) $bobot = 4;
    elseif($n['nilai_angka'] >= 80) $bobot = 3.7;
    elseif($n['nilai_angka'] >= 75) $bobot = 3.3;
    elseif($n['nilai_angka'] >= 70) $bobot = 3;
    elseif($n['nilai_angka'] >= 65) $bobot = 2.7;
    elseif($n['nilai_angka'] >= 60) $bobot = 2.3;
    elseif($n['nilai_angka'] >= 55) $bobot = 2;
    elseif($n['nilai_angka'] >= 50) $bobot = 1.7;
    else $bobot = 0;
    $total_sks += $n['sks'];
    $total_bobot += ($bobot * $n['sks']);
}

$predikat = '';
if($ipk >= 3.5) $predikat = 'Cum Laude (Dengan Pujian) 🏆';
elseif($ipk >= 3.0) $predikat = 'Sangat Memuaskan ⭐⭐⭐';
elseif($ipk >= 2.5) $predikat = 'Memuaskan ⭐⭐';
elseif($ipk >= 2.0) $predikat = 'Cukup ⭐';
else $predikat = 'Kurang ⚠️';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung IPK | Sistem Akademik</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?= getSidebar($_SESSION['role'], 'ipk') ?>
    <?= getNavbar($_SESSION['nama']) ?>
    
    <div class="container">
        <div class="ipk-card">
            <div>📊 INDEKS PRESTASI KUMULATIF (IPK)</div>
            <div class="ipk-value"><?= $ipk ?></div>
            <div>Skala 4.00</div>
            <div class="predikat"><?= $predikat ?></div>
        </div>
        
        <?php if(count($nilai) == 0): ?>
            <div class="empty-message">📭 Belum ada data nilai. Silakan hubungi dosen.</div>
        <?php else: ?>
            <div class="detail-table">
                <table width="100%">
                    <thead><tr><th>Mata Kuliah</th><th>SKS</th><th>Nilai</th><th>Bobot</th><th>Total Bobot</th></tr></thead>
                    <tbody>
                        <?php foreach($nilai as $n): 
                            $bobot = 0;
                            if($n['nilai_angka'] >= 85) $bobot = 4;
                            elseif($n['nilai_angka'] >= 80) $bobot = 3.7;
                            elseif($n['nilai_angka'] >= 75) $bobot = 3.3;
                            elseif($n['nilai_angka'] >= 70) $bobot = 3;
                            elseif($n['nilai_angka'] >= 65) $bobot = 2.7;
                            elseif($n['nilai_angka'] >= 60) $bobot = 2.3;
                            elseif($n['nilai_angka'] >= 55) $bobot = 2;
                            elseif($n['nilai_angka'] >= 50) $bobot = 1.7;
                            else $bobot = 0;
                        ?>
                        <tr>
                            <td><?= $n['nama_mk'] ?></td>
                            <td><?= $n['sks'] ?></td>
                            <td><?= $n['nilai_angka'] ?></td>
                            <td><?= $bobot ?></td>
                            <td><?= $bobot * $n['sks'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="summary">
                <h3>📝 Ringkasan Perhitungan IPK</h3>
                <div class="summary-item"><span>Total SKS</span><strong><?= $total_sks ?> SKS</strong></div>
                <div class="summary-item"><span>Total Bobot</span><strong><?= $total_bobot ?></strong></div>
                <div class="summary-item"><span>Rumus IPK</span><strong><?= $total_bobot ?> ÷ <?= $total_sks ?> = <?= $ipk ?></strong></div>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php" class="btn btn-primary">← Kembali ke Dashboard</a>
        </div>
    </div>
    
    <?= getSidebarScript() ?>
</body>
</html>