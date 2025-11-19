<?php
// components/header.php
$pageTitle = $pageTitle ?? 'Dashboard';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Bimbel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e40af'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <!-- Mobile Menu Overlay -->
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden"></div>
    
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include __DIR__ . '/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 z-10">
                <div class="flex items-center justify-between px-4 py-3">
                    <!-- Mobile menu button -->
                    <button id="sidebarToggle" class="lg:hidden text-gray-600 hover:text-primary focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Page Title -->
                    <h1 class="text-xl font-semibold text-gray-800 ml-2 lg:ml-0">
                        <?php echo htmlspecialchars($pageTitle); ?>
                    </h1>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-2 text-gray-700 hover:text-primary focus:outline-none">
                                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">
                                        <?php 
                                            echo isset($_SESSION['username']) 
                                                ? strtoupper(substr($_SESSION['username'], 0, 1)) 
                                                : 'A'; 
                                        ?>
                                    </span>
                                </div>
                                <span class="hidden md:block font-medium">
                                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?>
                                </span>
                                <i class="fas fa-chevron-down text-xs hidden md:block"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 hidden z-30">
                                <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                <a href="settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Pengaturan
                                </a>
                                <div class="border-t border-gray-200"></div>
                                <a href="../process/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>