<?php
class MitigationManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTotalLoss($role, $staff_id = null) {
        if ($role === 'admin') {
            $query = $this->conn->query("
                SELECT SUM(r.quantitative) AS total_loss 
                FROM risks r LEFT JOIN mitigations m ON r.risk_code = m.risk_code 
                WHERE m.is_completed = 0 OR m.is_completed IS NULL
            ");
        } else {
            $query = $this->conn->prepare("
                SELECT SUM(r.quantitative) AS total_loss 
                FROM risks r 
                LEFT JOIN mitigations m ON r.risk_code = m.risk_code
                WHERE (m.is_completed = 0 OR m.is_completed IS NULL) AND r.staff_id = :staff_id
            ");
            $query->execute(['staff_id' => $staff_id]);
        }
        return $query->fetch(PDO::FETCH_ASSOC)['total_loss'] ?? 0;
    }

    public function getActiveRisks($role, $staff_id = null) {
        if ($role === 'admin') {
            $query = $this->conn->query("SELECT COUNT(*) AS active_risks FROM mitigations WHERE is_completed = 0");
        } else {
            $query = $this->conn->prepare("
                SELECT COUNT(*) AS active_risks FROM mitigations 
                WHERE staff_id = :staff_id AND is_completed = 0
            ");
            $query->execute(['staff_id' => $staff_id]);
        }
        return $query->fetch(PDO::FETCH_ASSOC)['active_risks'] ?? 0;
    }

    public function getRecentMitigations($role, $staff_id = null) {
        if ($role === 'admin') {
            // Admin: Ambil semua mitigation_plan dan staff_id
            $query = $this->conn->query("
                SELECT 
                    mitigations.mitigation_plan, 
                    mitigations.staff_id
                FROM mitigations
                ORDER BY mitigations.id DESC
                LIMIT 10
            ");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($role === 'sub-admin') {
            // Sub-admin: Ambil mitigation_plan berdasarkan staff_id
            $query = $this->conn->prepare("
                SELECT 
                    mitigations.mitigation_plan, 
                    mitigations.staff_id
                FROM mitigations
                WHERE mitigations.staff_id = :staff_id
                ORDER BY mitigations.id DESC
                LIMIT 10
            ");
            $query->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // User: Tidak memiliki akses
            return [];
        }
    }
    
}
?>
