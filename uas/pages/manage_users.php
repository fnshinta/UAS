<?php
require_once '../includes/init.php';
require_once '../classes/UserManager.php';

// Pastikan session hanya dimulai jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit("Anda tidak memiliki izin untuk mengakses halaman ini.");
}

try {
    // Inisialisasi UserManager
    $userManager = new UserManager();

    // Tambah/Edit User (POST Request)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $username = $_POST['username'];
        $role = $_POST['role'];
        $staff_id = $_POST['staff_id'];
        $password = $_POST['password'] ?? null;

        // Gunakan setter untuk mengatur data pengguna
        if ($id) {
            $userManager->setId($id);  // Set ID untuk update
        }
        $userManager->setUsername($username);
        $userManager->setRole($role);
        $userManager->setStaffId($staff_id);

        // Set password jika ada
        if (!empty($password)) {
            $userManager->setPassword($password);
        }

        // Simpan atau perbarui pengguna
        $userManager->saveUser();
        header("Location: manage_users.php");
        exit();
    }

    // Hapus User (GET Request)
    if (isset($_GET['delete'])) {
        $userManager->setId($_GET['delete']);
        $userManager->deleteUser();
        header("Location: manage_users.php");
        exit();
    }

    // Ambil semua pengguna dan staff
    $users = $userManager->getAllUsers();
    $staffs = $userManager->getAllStaffs();

} catch (Exception $e) {
    // Tangani error
    echo "Terjadi kesalahan: " . htmlspecialchars($e->getMessage());
    exit();
}

require_once '../includes/header.php';
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Monitoring Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<!-- Main Content -->
<main class="flex flex-col items-center p-6 bg-purple-50 min-h-screen space-y-10">

    <h1 class="text-3xl font-extrabold text-purple-900 mb-4">Manage Users</h1>

    <!-- Form Add/Edit User -->
    <section class="bg-white rounded-lg shadow-md p-6 w-full max-w-6xl">
        <h2 class="text-2xl font-semibold text-purple-700 mb-5">Add/Edit User</h2>
        <form method="POST" action="manage_users.php" class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    <?php foreach ($staffs as $staff): ?>
                        <option value="<?= htmlspecialchars($staff['id']); ?>"><?= htmlspecialchars($staff['staff_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2">
                <button type="submit"
                    class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded font-semibold transition">
                    Save
                </button>
            </div>
        </form>
    </section>

    <!-- User List Table -->
    <section class="bg-white rounded-lg shadow-md p-6 w-full max-w-6xl">
        <h2 class="text-2xl font-semibold text-purple-700 mb-5">User List</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border border-purple-300 text-sm md:text-base">
                <thead class="bg-purple-100 text-purple-900 font-semibold">
                    <tr>
                        <th class="border border-purple-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-purple-300 px-4 py-2 text-left">Username</th>
                        <th class="border border-purple-300 px-4 py-2 text-left">Role</th>
                        <th class="border border-purple-300 px-4 py-2 text-left">Staff</th>
                        <th class="border border-purple-300 px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-purple-50 transition">
                        <td class="border border-purple-300 px-4 py-2"><?= htmlspecialchars($user['id']); ?></td>
                        <td class="border border-purple-300 px-4 py-2"><?= htmlspecialchars($user['username']); ?></td>
                        <td class="border border-purple-300 px-4 py-2 capitalize"><?= htmlspecialchars($user['role']); ?></td>
                        <td class="border border-purple-300 px-4 py-2"><?= htmlspecialchars($user['staff_name'] ?? 'N/A'); ?></td>
                        <td class="border border-purple-300 px-4 py-2 space-x-3">
                            <a href="?edit=<?= $user['id']; ?>" class="text-indigo-600 hover:underline font-medium">Edit</a>
                            <a href="?delete=<?= $user['id']; ?>" onclick="return confirm('Hapus user ini?');" class="text-red-600 hover:underline font-medium">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>


<?php require_once '../includes/footer.php'; ?>

<!-- JavaScript -->

<script>
    document.querySelectorAll('[href^="?edit="]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();

            // Ambil baris data yang ingin di-edit
            const row = link.closest('tr');

            // Isi form dengan data dari baris tabel
            document.getElementById('id').value = row.cells[0].innerText.trim();
            document.getElementById('username').value = row.cells[1].innerText.trim();
            document.getElementById('role').value = row.cells[2].innerText.trim();
            
            const staffValue = row.cells[3].innerText.trim(); // Ambil nama staff dari tabel

            // Set dropdown Staff
            const staffDropdown = document.getElementById('staff_id');
            Array.from(staffDropdown.options).forEach(option => {
                if (option.text === staffValue) {
                    option.selected = true; // Pilih opsi yang cocok dengan staff
                }
            });

            // Tampilkan notifikasi berwarna merah
            let notifDiv = document.getElementById('editNotif');
            if (!notifDiv) {
                notifDiv = document.createElement('div');
                notifDiv.id = 'editNotif';
                notifDiv.style.color = 'red';
                notifDiv.style.marginTop = '10px';
                notifDiv.style.fontWeight = 'bold';
                notifDiv.innerText = 'Silahkan edit data di form.';
                const formTitle = document.querySelector('h2'); // Lokasi notifikasi di bawah judul
                formTitle.insertAdjacentElement('afterend', notifDiv);
            } else {
                notifDiv.innerText = 'Silahkan edit data di form.';
            }

            // Scroll ke bagian atas form
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // Smooth scroll effect
            });
        });
    });
</script>
</body>
</html>