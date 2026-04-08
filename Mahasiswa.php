<?php
require_once 'User.php';
require_once 'interface.php';

class Mahasiswa extends User implements LaporanInterface {
    private $nim;
    
    public function setNim($nim) {
        $this->nim = $nim;
    }
    
    public function getNilai() {
        $query = "SELECT mk.kode_mk, mk.nama_mk, mk.sks, n.nilai_huruf, n.nilai_angka 
                  FROM nilai n 
                  JOIN mata_kuliah mk ON n.mata_kuliah_id = mk.id 
                  WHERE n.mahasiswa_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function hitungIPK() {
        $nilai = $this->getNilai();
        $totalSKS = 0;
        $totalBobot = 0;
        
        foreach($nilai as $n) {
            $bobot = $this->konversiNilaiKeBobot($n['nilai_angka']);
            $totalSKS += $n['sks'];
            $totalBobot += ($bobot * $n['sks']);
        }
        
        return $totalSKS > 0 ? round($totalBobot / $totalSKS, 2) : 0;
    }
    
    private function konversiNilaiKeBobot($angka) {
        if($angka >= 85) return 4.0;
        if($angka >= 80) return 3.7;
        if($angka >= 75) return 3.3;
        if($angka >= 70) return 3.0;
        if($angka >= 65) return 2.7;
        if($angka >= 60) return 2.3;
        if($angka >= 55) return 2.0;
        if($angka >= 50) return 1.7;
        if($angka >= 40) return 1.0;
        return 0;
    }
    
    public function cetakLaporan() {
        $nilai = $this->getNilai();
        $ipk = $this->hitungIPK();
        
        $html = "<div class='khs-container'>";
        $html .= "<h3>Kartu Hasil Studi (KHS)</h3>";
        $html .= "<p><strong>Nama:</strong> {$this->nama_lengkap}</p>";
        $html .= "<p><strong>NIM:</strong> {$this->nim}</p>";
        $html .= "<table class='khs-table' border='1' cellpadding='10' cellspacing='0' width='100%'>";
        $html .= "<tr style='background: #4A90E2; color: white;'><th>Kode MK</th><th>Mata Kuliah</th><th>SKS</th><th>Nilai Huruf</th><th>Nilai Angka</th><tr>";
        
        if(count($nilai) > 0) {
            foreach($nilai as $n) {
                $html .= "<tr>";
                $html .= "<td>{$n['kode_mk']}</td>";
                $html .= "<td>{$n['nama_mk']}</td>";
                $html .= "<td>{$n['sks']}</td>";
                $html .= "<td>{$n['nilai_huruf']}</td>";
                $html .= "<td>{$n['nilai_angka']}</td>";
                $html .= "</tr>";
            }
        } else {
            $html .= "<tr><td colspan='5' style='text-align: center;'>Belum ada nilai</td></tr>";
        }
        
        $html .= "<tr style='background: #f0f0f0; font-weight: bold;'><td colspan='4'><strong>IPK</strong></td><td><strong>{$ipk}</strong></td></tr>";
        $html .= "</table></div>";
        
        return $html;
    }
    
    public function getDashboardInfo() {
        $nilai = $this->getNilai();
        return [
            'nama' => $this->nama_lengkap,
            'nim' => $this->nim,
            'jumlah_mk' => count($nilai),
            'ipk' => $this->hitungIPK()
        ];
    }
}
?>