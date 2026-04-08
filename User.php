<?php
abstract class User {
    protected $id;
    protected $username;
    protected $nama_lengkap;
    protected $role;
    protected $nim_nip;
    protected $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username AND password = MD5(:password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $password);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $user['id'];
            $this->username = $user['username'];
            $this->nama_lengkap = $user['nama_lengkap'];
            $this->role = $user['role'];
            $this->nim_nip = $user['nim_nip'];
            return true;
        }
        return false;
    }
    
    // GETTER
    public function getId() { return $this->id; }
    public function getNamaLengkap() { return $this->nama_lengkap; }
    public function getNimNip() { return $this->nim_nip; }
    public function getRole() { return $this->role; }
    
    // SETTER
    public function setId($id) { $this->id = $id; }
    public function setNamaLengkap($nama) { $this->nama_lengkap = $nama; }
    public function setNimNip($nim_nip) { $this->nim_nip = $nim_nip; }
    
    abstract public function getDashboardInfo();
}
?>