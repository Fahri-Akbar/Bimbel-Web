<?php
session_start();
require_once '../../db/koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'create') {
        $nama_kelas = mysqli_real_escape_string($conn, $_POST['nama_kelas']);
        
        $query = "INSERT INTO kelas (nama_kelas) VALUES (?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $nama_kelas);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambah kelas']);
        }
    }
    elseif ($action == 'delete') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        
        $query = "DELETE FROM kelas WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus kelas']);
        }
    }
}
?>