<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'dosen') {
    header("Location: index.php");
    exit();
}

require_once 'koneksi.php';
require_once 'Dosen.php';
require_once 'template.php';

$db = new Database();
$conn = $db->getConnection();
$dosen = new Dosen($conn);
$dosen->setId($_SESSION['user_id']);

$message = "";
$error = "";

$mahasiswa = $dosen->getAllMahasiswa();
$matakuliah = $dosen->getAllMataKuliah();

if(isset($_POST['submit'])) {
    $mhs_id = $_POST['mahasiswa_id'];
    $mk_id = $_POST['mk_id'];
    $nilai = $_POST['nilai_angka'];
    
    if($nilai >= 0 && $nilai <= 100) {
        if($dosen->inputNilai($mhs_id, $mk_id, $nilai)) {
            $message = "Nilai berhasil disimpan!";
        } else {
            $error = "Gagal menyimpan nilai!";
        }
    } else {
        $error = "Nilai harus antara 0-100!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai | Sistem Akademik</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?= getSidebar($_SESSION['role'], 'nilai') ?>
    <?= getNavbar($_SESSION['nama']) ?>
    
    <div class="container">
        <div class="card">
            <h2>✏️ Input Nilai Mahasiswa</h2>
            <div class="subtitle">Masukkan nilai untuk mata kuliah</div>
            
            <?php if($message): ?>
                <div class="message">✓ <?= $message ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="error-message" style="background:#fee2e2; color:#dc2626; padding:12px; border-radius:14px; margin-bottom:20px;">✗ <?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Mahasiswa</label>
                    <select name="mahasiswa_id" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        <?php foreach($mahasiswa as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= $m['nama_lengkap'] ?> (<?= $m['nim_nip'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Mata Kuliah</label>
                    <select name="mk_id" required>
                        <option value="">-- Pilih Mata Kuliah --</option>
                        <?php foreach($matakuliah as $mk): ?>
                            <option value="<?= $mk['id'] ?>"><?= $mk['kode_mk'] ?> - <?= $mk['nama_mk'] ?> (<?= $mk['sks'] ?> SKS)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Nilai (0-100)</label>
                    <input type="number" name="nilai_angka" step="0.01" min="0" max="100" required>
                </div>
                
                <button type="submit" name="submit">Simpan Nilai</button>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="dashboard.php" class="btn btn-secondary">← Kembali</a>
            </div>
        </div>
    </div>
    
    <?= getSidebarScript() ?>
</body>
</html>