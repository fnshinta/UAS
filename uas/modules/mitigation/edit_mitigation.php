<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../classes/Mitigation.php';

$username = $_SESSION['username'];

// Potong username hanya sampai sebelum tanda @
if (strpos($username, '@') !== false) {
    $username = explode('@', $username)[0];
}


$mitigation = new Mitigation($conn);

// Tangkap ID dari GET atau POST
$id = $_GET['edit'] ?? $_POST['edit'] ?? null;

// Validasi ID
if (!$id || !is_numeric($id)) {
    die("ID tidak valid atau tidak ditemukan.");
}

// Ambil data mitigasi untuk diedit
$editData = $mitigation->getMitigationById($id);
if (!$editData) {
    die("Data tidak ditemukan di database untuk ID: " . htmlspecialchars($id));
}

// Update mitigasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'risk_code' => $_POST['risk_code'],
        'inherent_likehood' => $_POST['inherent_likehood'],
        'inherent_impact' => $_POST['inherent_impact'],
        'inherent_risk_level' => $_POST['inherent_likehood'] * $_POST['inherent_impact'],
        'existing_control' => $_POST['existing_control'],
        'control_quality' => $_POST['control_quality'],
        'execution_status' => $_POST['execution_status'],
        'residual_likehood' => $_POST['residual_likehood'],
        'residual_impact' => $_POST['residual_impact'],
        'residual_risk_level' => $_POST['residual_likehood'] * $_POST['residual_impact'],
        'risk_treatment' => $_POST['risk_treatment'],
        'mitigation_plan' => $_POST['mitigation_plan'],
        'after_mitigation_likehood' => $_POST['after_mitigation_likehood'],
        'after_mitigation_impact' => $_POST['after_mitigation_impact'],
        'after_mitigation_risk_level' => $_POST['after_mitigation_likehood'] * $_POST['after_mitigation_impact']
    ];

    // Eksekusi Update
    if ($mitigation->updateMitigation($id, $data)) {
        header("Location: ../../pages/mitigation.php");
        exit;
    } else {
        $error = "Gagal memperbarui data!";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Risiko</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.4/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Definisi warna sesuai dengan identification.php */
        .bg-purple-900 { background-color: #553c9a; } /* Warna ungu gelap */
        .bg-purple-800 { background-color: #6b46c1; } /* Warna ungu sedang */
        .bg-purple-700 { background-color: #805ad5; } /* Warna ungu terang */
        .bg-purple-300 { background-color: #d6bcfa; } /* Warna ungu sangat terang */
        .bg-purple-200 { background-color: #f3e8ff; } /* Warna ungu paling muda */
        .text-purple-900 { color: #553c9a; }
        .text-purple-800 { color: #6b46c1; }
        .text-purple-700 { color: #805ad5; }
        .border-purple-300 { border-color: #d6bcfa; }
        .border-purple-400 { border-color: #b794f4; } /* Tambahan, jika diperlukan */

        /* Warna untuk elemen header/navigasi agar serasi dengan skema ungu */
        .bg-custom-header {
            background-color: #6B46C1; /* Menggunakan warna ungu utama dari identification.php */
        }
        .text-custom-header {
            color: white; /* Teks putih untuk kontras dengan header ungu */
        }
        .hover\:bg-custom-nav-hover:hover {
            background-color: #805ad5; /* Ungu sedikit lebih terang saat hover */
        }
        .text-custom-nav {
            color: #d6bcfa; /* Ungu muda untuk navigasi */
        }
        .text-custom-profile {
            color: #d6bcfa; /* Ungu muda untuk teks profile */
        }
        .bg-custom-profile-circle {
            background-color: #553C9A; /* Ungu gelap untuk lingkaran profil */
        }

    </style>
</head>

    <main class="container mx-auto px-4 py-6 flex-grow">
        <section class="bg-white rounded shadow p-6 mb-8">
            <form action="edit_mitigation.php" method="POST" target="_self" class="space-y-4">
                <input type="hidden" name="edit" value="<?php echo htmlspecialchars($id); ?>">

                <!-- Kode Risiko -->
              <!-- Input Hidden untuk Risk Code -->
                <input type="hidden" name="risk_code" value="<?php echo htmlspecialchars($editData['risk_code'] ?? ''); ?>">


                <!-- Inherent Risk -->
                <h3 class="text-lg font-bold mt-6 mb-2">Inherent Risk</h3>
                <div class="space-y-4">
                    <label for="inherent_likehood" class="block text-gray-700 font-medium">Likelihood</label>
                    <select id="inherent_likehood" name="inherent_likehood" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($editData['inherent_likehood']) && $editData['inherent_likehood'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>

                    <label for="inherent_impact" class="block text-gray-700 font-medium">Impact</label>
                    <select id="inherent_impact" name="inherent_impact" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($editData['inherent_impact']) && $editData['inherent_impact'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Existing Control -->
                <h3 class="text-lg font-bold mt-6 mb-2">Existing Control</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="existing_control" class="block text-gray-700 font-medium"v>Yes/No</label>
                            <select id="existing_control" name="existing_control" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                                <option value="Yes" <?php echo (isset($editData['existing_control']) && $editData['existing_control'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                <option value="No" <?php echo (isset($editData['existing_control']) && $editData['existing_control'] == 'No') ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div>
                            <label for="control_quality" class="block text-gray-700 font-medium">Control Quality</label>
                            <select id="control_quality" name="control_quality" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                                <option value="Sufficient" <?php echo (isset($editData['control_quality']) && $editData['control_quality'] == 'Sufficient') ? 'selected' : ''; ?>>Sufficient</option>
                                <option value="Not Sufficient" <?php echo (isset($editData['control_quality']) && $editData['control_quality'] == 'Not Sufficient') ? 'selected' : ''; ?>>Not Sufficient</option>
                            </select>
                        </div>
                        <div>
                            <label for="execution_status" class="block text-gray-700 font-medium">Execution Status</label>
                            <select id="execution_status" name="execution_status" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                                <option value="On Progress" <?php echo (isset($editData['execution_status']) && $editData['execution_status'] == 'On Progress') ? 'selected' : ''; ?>>On Progress</option>
                                <option value="Pending" <?php echo (isset($editData['execution_status']) && $editData['execution_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Completed" <?php echo (isset($editData['execution_status']) && $editData['execution_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                    </div>

                <!-- Residual Risk -->
                <h3 class="text-lg font-bold mt-6 mb-2">Residual Risk</h3>
                <div class="space-y-4">
                    <div>
                        <label for="residual_likehood" class="block text-gray-700 font-medium">Likelihood</label>
                        <select id="residual_likehood" name="residual_likehood" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['residual_likehood']) && $editData['residual_likehood'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div>
                        <label for="residual_impact" class="block text-gray-700 font-medium">Impact</label>
                        <select id="residual_impact" name="residual_impact" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['residual_impact']) && $editData['residual_impact'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <!-- Risk Treatment -->
                <h3 class="text-lg font-bold mt-6 mb-2">Risk Treatment</h3>
                <div class="space-y-4">
                    <div>
                        <label for="risk_treatment" class="block text-gray-700 font-medium">Risk Treatment</label>
                        <select id="risk_treatment" name="risk_treatment" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                            <option value="Accept" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Accept') ? 'selected' : ''; ?>>Accept</option>
                            <option value="Share" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Share') ? 'selected' : ''; ?>>Share</option>
                            <option value="Reduce" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Reduce') ? 'selected' : ''; ?>>Reduce</option>
                            <option value="Avoid" <?php echo (isset($editData['risk_treatment']) && $editData['risk_treatment'] == 'Avoid') ? 'selected' : ''; ?>>Avoid</option>
                        </select>
                    </div>

                    <div>
                        <label for="mitigation_plan" class="block text-gray-700 font-medium">Mitigation Plan</label>
                        <textarea id="mitigation_plan" name="mitigation_plan" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500"><?php echo $editData['mitigation_plan'] ?? ''; ?></textarea>
                    </div>
                </div>

                <!-- After Mitigation Risk -->
                <h3 class="text-lg font-bold mt-6 mb-2">After Mitigation Risk</h3>
                <div class="space-y-4">
                    <div>
                        <label for="after_mitigation_likehood" class="block text-gray-700 font-medium">Likelihood</label>
                        <select id="after_mitigation_likehood" name="after_mitigation_likehood" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['after_mitigation_likehood']) && $editData['after_mitigation_likehood'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                <div>
                    <div class="space-y-4">
                    <label for="after_mitigation_impact" class="block text-gray-700 font-medium">Impact</label>
                        <select id="after_mitigation_impact" name="after_mitigation_impact" required class="w-full mt-1 px-4 py-2 border rounded focus:ring focus:ring-purple-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($editData['after_mitigation_impact']) && $editData['after_mitigation_impact'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                </div>
                <!-- Tombol Simpan -->
                <button type="submit"  class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800 transition">Update</button>
            </form>
        </section>
    </main>
    
    <?php
    require_once '../../includes/footer.php';
    ?> 

</body>
</html>
