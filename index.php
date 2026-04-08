<?php
session_start();
require_once 'koneksi.php';
require_once 'Mahasiswa.php';
require_once 'Dosen.php';

$db = new Database();
$conn = $db->getConnection();
$error = "";

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if($role == 'mahasiswa') {
        $user = new Mahasiswa($conn);
    } else {
        $user = new Dosen($conn);
    }
    
    if($user->login($username, $password)) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['nama'] = $user->getNamaLengkap();
        $_SESSION['role'] = $user->getRole();
        $_SESSION['nim_nip'] = $user->getNimNip();
        
        if($role == 'mahasiswa') {
            $_SESSION['nim'] = $user->getNimNip();
        } else {
            $_SESSION['nip'] = $user->getNimNip();
        }
        
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Login gagal! Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Akademik</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.getElementById("eyeIcon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.innerHTML = "🙈";
            } else {
                passwordInput.type = "password";
                eyeIcon.innerHTML = "👁️";
            }
        }
    </script>
</head>
<body class="split-body">
    <div class="split-container">
        <div class="split-left">
            <div class="left-content">
                <div class="info-card">
                    <div class="info-icon">📚</div>
                    <h2>SISTEM AKADEMIK</h2>
                    <p>Manajemen Nilai dan Kartu Hasil Studi (KHS)</p>
                    <div class="info-features">
                        <div class="feature"><span>✓</span> Manajemen Mahasiswa</div>
                        <div class="feature"><span>✓</span> Manajemen Mata Kuliah</div>
                        <div class="feature"><span>✓</span> Input Nilai</div>
                        <div class="feature"><span>✓</span> Hitung IPK</div>
                        <div class="feature"><span>✓</span> Cetak KHS</div>
                    </div>
                </div>
            </div>
            <div class="left-footer">
                <p>© 2026 Sistem Akademik | All Rights Reserved</p>
            </div>
        </div>

        <div class="split-right">
            <div class="login-form-container">
                <div class="form-header">
                    <div class="form-logo">📚</div>
                    <h1>Selamat Datang</h1>
                    <p>Silakan login untuk melanjutkan</p>
                </div>

                <?php if($error): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="input-group">
                        <label>Role</label>
                        <select name="role" class="form-select" required>
                            <option value="mahasiswa">🎓 Mahasiswa</option>
                            <option value="dosen">👨‍🏫 Dosen</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-input" placeholder="mhs123 / dsn001" required>
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-input" placeholder="password" required>
                            <span class="toggle-password" onclick="togglePassword()" id="eyeIcon">👁️</span>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember">
                            <span>Ingat saya</span>
                        </label>
                        <a href="#" class="forgot-link" onclick="alert('Hubungi administrator'); return false;">Lupa password?</a>
                    </div>

                    <button type="submit" name="login" class="login-btn">LOGIN</button>
                </form>

                <div class="demo-info">
                    <p><strong>Demo Akun:</strong></p>
                    <p>Mahasiswa: mhs123 / password</p>
                    <p>Dosen: dsn001 / password</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>