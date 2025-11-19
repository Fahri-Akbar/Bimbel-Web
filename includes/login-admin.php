<?php
session_start();

// Koneksi database
require_once '../db/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Validasi input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan password harus diisi!";
        header("Location: ../login.php");
        exit();
    } else {
        // Query untuk mencari user
        $query = "SELECT id, username, password FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Verifikasi password yang sudah di-hash
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect ke halaman dashboard atau home
                header("Location: ../public/admin-dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Password salah!";
                header("Location: ../index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Username tidak ditemukan!";
            header("Location: ../index.php");
            exit();
        }
        
        mysqli_free_result($result);
    }
} else {
    // Jika bukan method POST, redirect ke login
    header("Location: ../index.php");
    exit();
}
?>