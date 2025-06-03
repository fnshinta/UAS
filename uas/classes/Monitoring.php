<?php
require_once __DIR__ . '/../includes/db.php';

class Monitoring {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua risk_code dari tabel risks
    public function getAllRiskCodes($role, $staff_id = null) {
        if ($role === 'admin') {
            // Admin dapat mengakses semua risk_code
            $query = "SELECT risk_code FROM risks";
            $stmt = $this->conn->prepare($query);
        } elseif ($role === 'sub-admin') {
            // Sub-admin hanya dapat mengakses risk_code yang sesuai dengan staff_id mereka
            $query = "SELECT risk_code FROM risks WHERE staff_id = :staff_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
        } else {
            // Role user atau role lain tidak dapat mengakses risk_code
            return [];
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengembalikan semua risk_code yang sesuai
    }
    
    // Ambil semua data monitoring
    public function getAllMonitoring($staff_id = null, $is_admin = false) {
        if ($is_admin) {
            $query = "SELECT monitoring.*, 
                             risks.risk_event, 
                             mitigations.mitigation_plan, 
                             staffs.staff_name
                      FROM monitoring
                      LEFT JOIN risks ON monitoring.risk_code = risks.risk_code
                      LEFT JOIN mitigations ON monitoring.risk_code = mitigations.risk_code
                      LEFT JOIN staffs ON risks.staff_id = staffs.id";
            return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $query = "SELECT monitoring.*, 
                             risks.risk_event, 
                             mitigations.mitigation_plan 
                      FROM monitoring
                      LEFT JOIN risks ON monitoring.risk_code = risks.risk_code
                      LEFT JOIN mitigations ON monitoring.risk_code = mitigations.risk_code
                      WHERE risks.staff_id = :staff_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['staff_id' => $staff_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // Ambil data monitoring berdasarkan ID
    public function getMonitoringById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM monitoring WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambah monitoring baru
    public function addMonitoring($data) {
        $query = "INSERT INTO monitoring (
                    risk_code, risk_event, mitigation_plan, month_status, evidence, pic, staff_id
                  ) VALUES (
                    :risk_code, :risk_event, :mitigation_plan, :month_status, :evidence, :pic, :staff_id
                  )";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    // Update monitoring
    public function updateMonitoring($id, $data) {
        $query = "UPDATE monitoring SET 
                    risk_code = :risk_code, 
                    risk_event = :risk_event, 
                    mitigation_plan = :mitigation_plan, 
                    month_status = :month_status, 
                    evidence = :evidence, 
                    pic = :pic 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    // Hapus monitoring
    public function deleteMonitoring($id) {
        $stmt = $this->conn->prepare("DELETE FROM monitoring WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
