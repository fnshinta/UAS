<?php 
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../classes/Risk.php';

$risk = new Risk($conn);

$role = $_SESSION['role'] ?? '';
$is_admin = $role === 'admin' || $role === 'sub-admin';
$staff_id = $_SESSION['staff_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin) {
    $data = [
        'objective' => $_POST['objective'],
        'process_business' => $_POST['process_business'],
        'risk_category' => $_POST['risk_category'],
        'risk_code' => $risk->generateRiskCode(),
        'risk_event' => $_POST['risk_event'],
        'risk_cause' => $_POST['risk_cause'],
        'risk_source' => $_POST['risk_source'],
        'qualitative' => $_POST['qualitative'],
        'quantitative' => str_replace(['Rp', '.', ' '], '', $_POST['quantitative']),
        'risk_owner' => $_POST['risk_owner'],
        'department' => $_POST['department'],
        'staff_id' => $staff_id,
        'created_by' => $_SESSION['user_id']
    ];
    $risk->addRisk($data);
    header("Location: identification.php");
    exit;
}

require_once '../includes/header.php';
?>

<main class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-purple-900">Identifikasi Risiko</h1>

    <?php if ($is_admin): ?>
    <section class="bg-white shadow-md rounded p-6 mb-10 border border-purple-300">
        <h2 class="text-2xl font-semibold mb-5 text-purple-800">Tambah Risiko Baru</h2>
        <form method="POST" action="identification.php" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="text" name="objective" placeholder="Objective" required class="w-full p-3 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
            <select name="process_business" class="w-full p-3 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="Akademik">Academic</option>
                <option value="Finansial">Financial</option>
                <option value="Kepegawaian">Staff</option>
            </select>
            <select name="risk_category" class="w-full p-3 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="Strategic">Strategic</option>
                <option value="Financial">Financial</option>
                <option value="Operational">Operational</option>
            </select>
            <input type="text" name="risk_event" placeholder="Risk Event" required class="w-full p-3 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
            <input type="text" name="risk_cause" placeholder="Risk Cause" required class="w-full p-3 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
            <select name="risk_source" class="w-full p-3 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="Internal">Internal</option>
                <option value="External">External</option>
            </select>
            <input type="text" name="qualitative" placeholder="Severity (Qualitative)" required class="w-full p-3 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
            <input type="text" name="quantitative" placeholder="Severity (Quantitative)" oninput="formatRupiah(this)" required class="w-full p-3 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
            <input type="text" name="risk_owner" placeholder="Risk Owner" required class="w-full p-3 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
            <input type="text" name="department" placeholder="Department" required class="w-full p-3 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />

            <div class="col-span-1 md:col-span-2 text-right">
                <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white px-5 py-2 rounded transition">Simpan</button>
            </div>
        </form>
    </section>
    <?php endif; ?>

    <section class="bg-white shadow-md rounded p-6 border border-purple-300">
        <h2 class="text-2xl font-semibold mb-5 text-purple-800">Daftar Risiko</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border border-purple-400">
                <thead class="bg-purple-200 text-purple-900">
                    <tr>
                        <th class="px-4 py-3 border border-purple-300 text-left">Kode</th>
                        <th class="px-4 py-3 border border-purple-300 text-left">Objective</th>
                        <th class="px-4 py-3 border border-purple-300 text-left">Process</th>
                        <th class="px-4 py-3 border border-purple-300 text-left">Category</th>
                        <th class="px-4 py-3 border border-purple-300 text-left">Event</th>
                        <th class="px-4 py-3 border border-purple-300 text-left">Cause</th>
                        <th class="px-4 py-3 border border-purple-300 text-left">Source</th>
                        <th class="px-4 py-3 border border-purple-300 text-left">Qualitative</th>
                        <th class="px-4 py-3 border border-purple-300 text-left">Quantitative</th>
                        <th class="px-4 py-3 border border-purple-300 text-left">Owner</th>
                        <th class="px-4 py-3 border border-purple-300 text-left">Department</th>
                        <?php if ($role === 'admin') echo '<th class="px-4 py-3 border border-purple-300 text-left">Staff</th>'; ?>
                        <?php if ($is_admin) echo '<th class="px-4 py-3 border border-purple-300 text-left">Aksi</th>'; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($role === 'admin') {
                        $stmt = $conn->query("SELECT risks.*, staff.staff_name FROM risks LEFT JOIN staff ON risks.staff_id = staff.id");
                    } else {
                        $stmt = $conn->prepare("SELECT * FROM risks WHERE staff_id = :staff_id");
                        $stmt->execute(['staff_id' => $staff_id]);
                    }

                    while ($row = $stmt->fetch()) {
                        echo "<tr class='hover:bg-purple-100'>";
                        echo "<td class='px-4 py-2 border border-purple-300'>{$row['risk_code']}</td>";
                        echo "<td class='px-4 py-2 border border-purple-300'>{$row['objective']}</td>";
                        echo "<td class='px-4 py-2 border border-purple-300'>{$row['process_business']}</td>";
                        echo "<td class='px-4 py-2 border border-purple-300'>{$row['risk_category']}</td>";
                        echo "<td class='px-4 py-2 border border-purple-300'>{$row['risk_event']}</td>";
                        echo "<td class='px-4 py-2 border border-purple-300'>{$row['risk_cause']}</td>";
                        echo "<td class='px-4 py-2 border border-purple-300'>{$row['risk_source']}</td>";
                        echo "<td class='px-4 py-2 border border-purple-300'>{$row['qualitative']}</td>";
                        echo "<td class='px-4 py-2 border border-purple-300'>Rp " . number_format($row['quantitative'], 0, ',', '.') . "</td>";
                        echo "<td class='px-4 py-2 border border-purple-300'>{$row['risk_owner']}</td>";
                        echo "<td class='px-4 py-2 border border-purple-300'>{$row['department']}</td>";
                        if ($role === 'admin') echo "<td class='px-4 py-2 border border-purple-300'>{$row['staff_name']}</td>";
                        if ($is_admin) {
                            echo "<td class='px-4 py-2 border border-purple-300'>
                                <a href='edit_risk.php?id={$row['id']}' class='text-purple-700 hover:underline'>Edit</a> |
                                <a href='delete_risk.php?id={$row['id']}' class='text-purple-700 hover:text-purple-900 hover:underline' onclick='return confirm(\"Yakin hapus risiko ini?\")'>Hapus</a>
                            </td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<style>
/* Remove old @apply, replaced by explicit Tailwind classes */
</style>

<script>
function formatRupiah(input) {
    let value = input.value.replace(/[^0-9]/g, "");
    if (value) {
        input.value = "Rp " + parseInt(value, 10).toLocaleString("id-ID");
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>
