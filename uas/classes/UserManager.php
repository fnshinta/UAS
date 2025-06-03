<?php
require_once '../includes/db.php';

class UserManager extends Database {
    private $id;
    private $username;
    private $role;
    private $staff_id;
    private $password;

    // Pastikan koneksi dari Database diwarisi dengan memanggil parent constructor
    public function __construct() {
        parent::__construct();  // Memanggil konstruktor Database untuk koneksi
    }

    // === Getter dan Setter ===

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getStaffId() {
        return $this->staff_id;
    }

    public function setStaffId($staff_id) {
        $this->staff_id = $staff_id;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    // === METHOD UNTUK PROFIL PENGGUNA ===
    public function getUserProfile() {
        $query = $this->conn->prepare("
            SELECT users.id, users.username, users.role, users.last_login, users.total_logins, 
                   staffs.nama_lengkap 
            FROM users 
            LEFT JOIN staffs ON users.staff_id = staffs.id 
            WHERE users.id = ?
        ");
        $query->execute([$this->id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    

    // === CRUD Methods ===

    public function getAllUsers() {
        $query = $this->conn->query("
            SELECT users.*, staffs.staff_name 
            FROM users 
            LEFT JOIN staffs ON users.staff_id = staffs.id
        ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStaffs() {
        $query = $this->conn->query("SELECT id, staff_name FROM staffs");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveUser() {
        if ($this->id) {
            if (!empty($this->password)) {
                $query = $this->conn->prepare("
                    UPDATE users 
                    SET username = ?, role = ?, staff_id = ?, password = ? 
                    WHERE id = ?
                ");
                $query->execute([$this->username, $this->role, $this->staff_id, $this->password, $this->id]);
            } else {
                $query = $this->conn->prepare("
                    UPDATE users 
                    SET username = ?, role = ?, staff_id = ? 
                    WHERE id = ?
                ");
                $query->execute([$this->username, $this->role, $this->staff_id, $this->id]);
            }
        } else {
            $finalPassword = $this->password ?: password_hash('default123', PASSWORD_BCRYPT);
            $query = $this->conn->prepare("
                INSERT INTO users (username, password, role, staff_id) 
                VALUES (?, ?, ?, ?)
            ");
            $query->execute([$this->username, $finalPassword, $this->role, $this->staff_id]);
        }
    }

    public function deleteUser() {
        $query = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $query->execute([$this->id]);
    }
}
?>
