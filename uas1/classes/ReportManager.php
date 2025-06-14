<?php
class ReportManager {
    private $conn; // Variabel koneksi database

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Ambil total risiko per fakultas
    public function getRisksPerFaculty() {
        $query = $this->conn->query("
            SELECT 
                COALESCE(staff.nama_lengkap, staff.staff_name) AS nama_staff,
                COUNT(risks.id) AS total_risks
            FROM staff
            LEFT JOIN risks ON staff.id = risks.staff_id
            GROUP BY nama_fakultas
        ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil detail mitigasi
    public function getMitigations() {
        $query = $this->conn->query("
            SELECT 
                risks.risk_code, risks.risk_event, 
                mitigations.mitigation_plan, mitigations.existing_control, mitigations.is_completed
            FROM mitigations
            JOIN risks ON mitigations.risk_code = risks.risk_code
        ");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update status checklist mitigasi
    public function updateMitigationStatus($risk_code, $is_completed) {
        $query = $this->conn->prepare("
            UPDATE mitigations 
            SET is_completed = :is_completed 
            WHERE risk_code = :risk_code
        ");
        $query->execute([
            'is_completed' => $is_completed,
            'risk_code' => $risk_code
        ]);
    }
}
?>
