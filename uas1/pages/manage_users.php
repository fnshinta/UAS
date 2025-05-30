<?php 
require_once __DIR__ .'/../includes/init.php';
require_once __DIR__ . '/../classes/UserManager.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit("Anda tidak memiliki izin untuk mengakses halaman ini.");
}

try {
    $userManager = new UserManager();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $username = $_POST['username'];
        $role = $_POST['role'];
        $staff_id = $_POST['staff_id'];
        $password = $_POST['password'] ?? null;

        if ($id) {
            $userManager->setId($id);
        }
        $userManager->setUsername($username);
        $userManager->setRole($role);
        $userManager->setStaffId($staff_id);

        if (!empty($password)) {
            $userManager->setPassword($password);
        }

        $userManager->saveUser();
        header("Location: manage_users.php");
        exit();
    }

    if (isset($_GET['delete'])) {
        $userManager->setId($_GET['delete']);
        $userManager->deleteUser();
        header("Location: manage_users.php");
        exit();
    }

    $users = $userManager->getAllUsers();
    $faculties = $userManager->getAllFaculties();

} catch (Exception $e) {
    echo "Terjadi kesalahan: " . htmlspecialchars($e->getMessage());
    exit();
}

require_once '../includes/header.php';
?>

<main class="flex flex-col md:ml-64 p-6 bg-purple-50 min-h-screen">
    <h1 class="text-3xl font-extrabold text-purple-900 mb-6">Manage Users</h1>

    <!-- Form Add/Edit User -->
    <section class="bg-white rounded-lg shadow-md p-6 mb-8 max-w-xl w-full">
        <h2 class="text-2xl font-semibold text-purple-700 mb-5">Add/Edit User</h2>
        <form method="POST" action="manage_users.php" class="space-y-5">
            <input type="hidden" name="id" id="id">
            <div>
                <label for="username" class="block mb-1 font-medium text-purple-800">Username</label>
                <input type="text" name="username" id="username" required
                    class="w-full px-4 py-2 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
            </div>
            <div>
                <label for="password" class="block mb-1 font-medium text-purple-800">Password (Optional)</label>
                <input type="password" name="password" id="password"
                    placeholder="Kosongkan jika tidak ingin mengubah password"
                    class="w-full px-4 py-2 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
            </div>
            <div>
                <label for="role" class="block mb-1 font-medium text-purple-800">Role</label>
                <select name="role" id="role" required
                    class="w-full px-4 py-2 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="sub-admin">Sub-Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div>
                <label for="staff_id" class="block mb-1 font-medium text-purple-800">Staff</label>
                <select name="staff_id" id="staff_id" required
                    class="w-full px-4 py-2 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="" disabled selected>-- Pilih Staff --</option>
                    <?php foreach ($faculties as $faculty): ?>
                        <option value="<?= htmlspecialchars($faculty['id']); ?>"><?= htmlspecialchars($faculty['faculty_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit"
                class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded font-semibold transition">Simpan</button>
        </form>
    </section>

    <!-- User List Table -->
    <section class="bg-white rounded-lg shadow-md p-6 max-w-full overflow-x-auto">
        <h2 class="text-2xl font-semibold text-purple-700 mb-5">User List</h2>
        <table class="min-w-full table-auto border border-purple-300">
            <thead class="bg-purple-100 text-purple-900 font-semibold">
                <tr>
                    <th class="border border-purple-300 px-4 py-2 text-left">ID</th>
                    <th class="border border-purple-300 px-4 py-2 text-left">Username</th>
                    <th class="border border-purple-300 px-4 py-2 text-left">Role</th>
                    <th class="border border-purple-300 px-4 py-2 text-left">Faculty</th>
                    <th class="border border-purple-300 px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-purple-50 transition">
                    <td class="border border-purple-300 px-4 py-2"><?= htmlspecialchars($user['id']); ?></td>
                    <td class="border border-purple-300 px-4 py-2"><?= htmlspecialchars($user['username']); ?></td>
                    <td class="border border-purple-300 px-4 py-2 capitalize"><?= htmlspecialchars($user['role']); ?></td>
                    <td class="border border-purple-300 px-4 py-2"><?= htmlspecialchars($user['faculty_name'] ?? 'N/A'); ?></td>
                    <td class="border border-purple-300 px-4 py-2 space-x-3">
                        <a href="?edit=<?= $user['id']; ?>" class="text-indigo-600 hover:underline font-medium">Edit</a>
                        <a href="?delete=<?= $user['id']; ?>" onclick="return confirm('Hapus user ini?');" class="text-red-600 hover:underline font-medium">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<footer class="bg-purple-900 text-white text-center py-4 mt-auto">
    <div class="text-sm">
        © 2025 Risk Management Dashboard. All rights reserved.
    </div>
</footer>

<script>
    // Edit button handler to fill form
    document.querySelectorAll('a[href^="?edit="]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const row = link.closest('tr');

            document.getElementById('id').value = row.cells[0].innerText.trim();
            document.getElementById('username').value = row.cells[1].innerText.trim();
            document.getElementById('role').value = row.cells[2].innerText.trim();
            const facultyName = row.cells[3].innerText.trim();

            const facultySelect = document.getElementById('staff_id');
            Array.from(facultySelect.options).forEach(opt => {
                opt.selected = (opt.text === facultyName);
            });

            let notifDiv = document.getElementById('editNotif');
            if (!notifDiv) {
                notifDiv = document.createElement('div');
                notifDiv.id = 'editNotif';
                notifDiv.className = 'text-red-600 font-semibold mt-3';
                notifDiv.innerText = 'Silahkan edit data di form.';
                const formTitle = document.querySelector('section form h2, section h2');
                formTitle.insertAdjacentElement('afterend', notifDiv);
            } else {
                notifDiv.innerText = 'Silahkan edit data di form.';
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
