<?php
session_start();
require_once '../../db/koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'create') {
        $materi_pokok_id = mysqli_real_escape_string($conn, $_POST['materi_pokok_id']);
        $judul_sub_materi = mysqli_real_escape_string($conn, $_POST['judul_sub_materi']);
        
        // Check if sub materi already exists
        $check_query = "SELECT id FROM sub_materi WHERE materi_pokok_id = ? AND judul_sub_materi = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "is", $materi_pokok_id, $judul_sub_materi);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            echo json_encode(['success' => false, 'message' => 'Sub materi sudah ada']);
            exit;
        }
        
        $query = "INSERT INTO sub_materi (materi_pokok_id, judul_sub_materi) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "is", $materi_pokok_id, $judul_sub_materi);
        
        if (mysqli_stmt_execute($stmt)) {
            $sub_materi_id = mysqli_insert_id($conn);
            echo json_encode(['success' => true, 'id' => $sub_materi_id, 'message' => 'Sub materi berhasil dibuat']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal membuat sub materi']);
        }
    }
    elseif ($action == 'update') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $judul_sub_materi = mysqli_real_escape_string($conn, $_POST['judul_sub_materi']);
        
        $query = "UPDATE sub_materi SET judul_sub_materi = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $judul_sub_materi, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Sub materi berhasil diupdate']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengupdate sub materi']);
        }
    }
    elseif ($action == 'delete') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
    
        // First delete all topics related to this sub_materi
        $delete_topics_query = "DELETE FROM topik WHERE sub_materi_id = ?";
        $delete_topics_stmt = mysqli_prepare($conn, $delete_topics_query);
        mysqli_stmt_bind_param($delete_topics_stmt, "i", $id);
        mysqli_stmt_execute($delete_topics_stmt);
        
        // Then delete the sub_materi
        $query = "DELETE FROM sub_materi WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Sub materi berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus sub materi']);
        }
    }
}
?>