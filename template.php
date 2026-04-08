<?php
function getSidebar($role, $activePage) {
    $menuItems = [];
    if($role == 'mahasiswa') {
        $menuItems = [
            ['url' => 'dashboard.php', 'icon' => '🏠', 'label' => 'Dashboard'],
            ['url' => 'khs.php', 'icon' => '📄', 'label' => 'Cetak KHS'],
            ['url' => 'ipk.php', 'icon' => '📊', 'label' => 'Hitung IPK']
        ];
    } else {
        $menuItems = [
            ['url' => 'dashboard.php', 'icon' => '🏠', 'label' => 'Dashboard'],
            ['url' => 'nilai.php', 'icon' => '✏️', 'label' => 'Input Nilai'],
            ['url' => 'daftar_mahasiswa.php', 'icon' => '👨‍🎓', 'label' => 'Manajemen Mahasiswa'],
            ['url' => 'matakuliah.php', 'icon' => '📚', 'label' => 'Manajemen MK']
        ];
    }
    
    $html = '<div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">📚</div>
            <h3>Sistem Akademik</h3>
            <p>Manajemen Nilai & KHS</p>
        </div>
        <ul class="sidebar-menu">';
    
    foreach($menuItems as $item) {
        $active = ($activePage == basename($item['url'], '.php')) ? 'active' : '';
        $html .= '<li><a href="' . $item['url'] . '" class="' . $active . '">
            <span class="menu-icon">' . $item['icon'] . '</span>
            <span>' . $item['label'] . '</span>
        </a></li>';
    }
    
    $html .= '</ul>
        <div class="sidebar-footer">
            <p>© 2026 Sistem Akademik</p>
        </div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>';
    
    return $html;
}

function getNavbar($nama) {
    return '<div class="navbar">
        <div class="hamburger" id="hamburger">
            <span></span><span></span><span></span>
        </div>
        <h2><span class="book-icon">📚</span> SISTEM AKADEMIK</h2>
        <div class="user-info">
            <div class="user-icon">👤</div>
            <span class="user-name">' . htmlspecialchars($nama) . '</span>
            <a href="logout.php" class="logout-btn" onclick="return confirmLogout(event)">Logout</a>
        </div>
    </div>';
}

function getSidebarScript() {
    return '<script>
        function confirmLogout(event) {
            event.preventDefault();
            if(confirm("Apakah Anda yakin ingin logout?")) {
                window.location.href = "logout.php?confirm=yes";
            }
            return false;
        }
        document.addEventListener("DOMContentLoaded", function() {
            var hamburger = document.getElementById("hamburger");
            var sidebar = document.getElementById("sidebar");
            var overlay = document.getElementById("sidebarOverlay");
            if(hamburger) {
                hamburger.addEventListener("click", function() {
                    sidebar.classList.toggle("active");
                    overlay.classList.toggle("active");
                    document.body.style.overflow = sidebar.classList.contains("active") ? "hidden" : "";
                });
            }
            if(overlay) {
                overlay.addEventListener("click", function() {
                    sidebar.classList.remove("active");
                    overlay.classList.remove("active");
                    document.body.style.overflow = "";
                });
            }
        });
    </script>';
}
?>