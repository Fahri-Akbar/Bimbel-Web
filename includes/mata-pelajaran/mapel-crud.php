<?php
session_start();
require_once '../../db/koneksi.php';

header('Content-Type: application/json');

// Create uploads directory if not exists
if (!file_exists('../../uploads')) {
    mkdir('../../uploads', 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'create' || $action == 'update') {
        $id = isset($_POST['id']) ? mysqli_real_escape_string($conn, $_POST['id']) : null;
        $kelas_id = mysqli_real_escape_string($conn, $_POST['kelas_id']);
        $nama_mapel = mysqli_real_escape_string($conn, $_POST['nama_mapel']);
        $gambar = null;
        
        // Handle file upload
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 2 * 1024 * 1024; // 2MB
            
            if (in_array($_FILES['gambar']['type'], $allowed_types) && 
                $_FILES['gambar']['size'] <= $max_size) {
                
                $extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                $filename = 'mapel_' . time() . '_' . uniqid() . '.' . $extension;
                $upload_path = '../../uploads/' . $filename;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                    $gambar = $filename;
                    
                    // Delete old image if updating
                    if ($action == 'update' && $id) {
                        $old_query = "SELECT gambar FROM mata_pelajaran WHERE id = ?";
                        $old_stmt = mysqli_prepare($conn, $old_query);
                        mysqli_stmt_bind_param($old_stmt, "i", $id);
                        mysqli_stmt_execute($old_stmt);
                        $old_result = mysqli_stmt_get_result($old_stmt);
                        
                        if ($old_row = mysqli_fetch_assoc($old_result) && $old_row['gambar']) {
                            $old_file = '../../uploads/' . $old_row['gambar'];
                            if (file_exists($old_file)) {
                                unlink($old_file);
                            }
                        }
                    }
                }
            }
        }
        
        if ($action == 'create') {
            $query = "INSERT INTO mata_pelajaran (kelas_id, nama_mapel, gambar) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "iss", $kelas_id, $nama_mapel, $gambar);
        } else {
            if ($gambar) {
                $query = "UPDATE mata_pelajaran SET kelas_id = ?, nama_mapel = ?, gambar = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "issi", $kelas_id, $nama_mapel, $gambar, $id);
            } else {
                $query = "UPDATE mata_pelajaran SET kelas_id = ?, nama_mapel = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "isi", $kelas_id, $nama_mapel, $id);
            }
        }
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data']);
        }
    }
    elseif ($action == 'delete') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        
        // Get image filename before delete
        $query = "SELECT gambar FROM mata_pelajaran WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result) && $row['gambar']) {
            $file_path = '../../uploads/' . $row['gambar'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $query = "DELETE FROM mata_pelajaran WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus data']);
        }
    }
}
?>