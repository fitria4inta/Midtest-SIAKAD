<?php
require_once 'User.php';

class Dosen extends User {
    private $nip;
    
    public function setNip($nip) {
        $this->nip = $nip;
    }
    
    public function getAllMahasiswa() {
        $query = "SELECT id, nama_lengkap, nim_nip FROM users WHERE role = 'mahasiswa' ORDER BY nim_nip";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllMataKuliah() {
        $query = "SELECT * FROM mata_kuliah ORDER BY semester, kode_mk";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function inputNilai($mahasiswa_id, $mk_id, $nilai_angka) {
        $nilai_huruf = $this->konversiNilaiKeHuruf($nilai_angka);
        
        $query = "INSERT INTO nilai (mahasiswa_id, mata_kuliah_id, nilai_huruf, nilai_angka) 
                  VALUES (:mhs, :mk, :huruf, :nilai)
                  ON DUPLICATE KEY UPDATE 
                  nilai_huruf = :huruf, nilai_angka = :nilai";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":mhs", $mahasiswa_id);
        $stmt->bindParam(":mk", $mk_id);
        $stmt->bindParam(":huruf", $nilai_huruf);
        $stmt->bindParam(":nilai", $nilai_angka);
        
        return $stmt->execute();
    }
    
    private function konversiNilaiKeHuruf($angka) {
        if($angka >= 85) return 'A';
        elseif($angka >= 80) return 'A-';
        elseif($angka >= 75) return 'B+';
        elseif($angka >= 70) return 'B';
        elseif($angka >= 65) return 'B-';
        elseif($angka >= 60) return 'C+';
        elseif($angka >= 55) return 'C';
        elseif($angka >= 50) return 'D';
        else return 'E';
    }
    
    public function getDashboardInfo() {
        $mhs = $this->getAllMahasiswa();
        $mk = $this->getAllMataKuliah();
        
        return [
            'nama' => $this->nama_lengkap,
            'nip' => $this->nip,
            'total_mahasiswa' => count($mhs),
            'total_mata_kuliah' => count($mk)
        ];
    }
}
?>