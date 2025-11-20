<?php
// components/sidebar.php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<aside id="sidebar" class="bg-white w-64 min-h-screen border-r border-gray-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 fixed lg:relative z-30">
    <!-- Logo -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-primary to-secondary rounded-lg flex items-center justify-center">
                <i class="fas fa-graduation-cap text-white"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Bimbel Admin</h2>
                <p class="text-xs text-gray-500">Panel Administrator</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="admin-dashboard.php" class="flex items-center space-x-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 <?php echo $currentPage == 'admin-dashboard.php' ? 'bg-primary text-white' : ''; ?>">
                    <i class="fas fa-tachometer-alt w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Kategori Kelas -->
            <li>
                <a href="admin-kategori_kelas.php" class="flex items-center space-x-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 <?php echo $currentPage == 'admin-kategori_kelas.php' ? 'bg-primary text-white' : ''; ?>">
                    <i class="fas fa-layer-group w-5 text-center"></i>
                    <span>Kategori Kelas</span>
                </a>
            </li>
            
            <!-- Mata Pelajaran -->
            <li>
                <a href="admin-mata_pelajaran.php" class="flex items-center space-x-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 <?php echo $currentPage == 'admin-mata_pelajaran.php' ? 'bg-primary text-white' : ''; ?>">
                    <i class="fas fa-book w-5 text-center"></i>
                    <span>Mata Pelajaran</span>
                </a>
            </li>
            
            <!-- Kelola Materi -->
            <li>
                <a href="admin-kelola_materi.php" class="flex items-center space-x-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 <?php echo $currentPage == 'kelola-materi.php' ? 'bg-primary text-white' : ''; ?>">
                    <i class="fas fa-file-alt w-5 text-center"></i>
                    <span>Kelola Materi</span>
                </a>
            </li>
            
            <!-- Kelola Soal -->
            <li>
                <a href="admin-kelola_soal.php" class="flex items-center space-x-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 <?php echo $currentPage == 'kelola-soal.php' ? 'bg-primary text-white' : ''; ?>">
                    <i class="fas fa-tasks w-5 text-center"></i>
                    <span>Kelola Soal</span>
                </a>
            </li>
            
            <!-- Logout -->
            <li class="pt-4 mt-4 border-t border-gray-200">
                <a href="../process/logout.php" class="flex items-center space-x-3 px-3 py-2 text-red-600 rounded-lg hover:bg-red-50 transition-colors duration-200">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>