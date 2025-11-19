<?php
session_start();
// Ambil pesan error dari session
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
// Hapus error dari session setelah ditampilkan
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card Login -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-center">
                <h1 class="text-2xl font-bold text-white">Masuk ke Akun Anda</h1>
                <p class="text-blue-100 mt-2">Silakan masukkan username dan password</p>
            </div>
            
            <!-- Error Message -->
            <?php if (!empty($error)): ?>
            <div class="m-4 mb-0 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                <i class="fas fa-exclamation-triangle mr-2"></i><?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <!-- Form Login -->
            <form method="POST" action="includes/login-admin.php" class="p-6 space-y-6">
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-user mr-2 text-blue-500"></i>Username
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Masukkan username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        required
                    >
                </div>
                
                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        required
                    >
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:-translate-y-0.5"
                >
                    Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>