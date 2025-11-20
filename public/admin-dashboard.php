<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Dashboard Admin";
?>
<?php include '../components/header.php'; ?>

<main class="flex-1 overflow-y-auto p-4 lg:p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600">Selamat datang di panel admin</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <p class="text-gray-600">Konten dashboard akan ditambahkan kemudian...</p>
    </div>
</main>

<?php include '../components/footer.php'; ?>