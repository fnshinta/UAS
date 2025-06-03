<?php
class ReportManager {
    private $conn; // Variabel koneksi database

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Ambil total risiko per staff
    public function getRisksPerStaff() {
        $query = $this->conn->query("
            SELECT 
                COALESCE(staffs.nama_lengkap, staffs.staff_name) AS nama_staff,
                COUNT(risks.id) AS total_risks
            FROM staffs
            LEFT JOIN risks ON staffs.id = risks.staff_id
            GROUP BY nama_staff
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
