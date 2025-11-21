<?php
session_start();
require_once '../../db/koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'create') {
        $judul_materi_pokok = mysqli_real_escape_string($conn, $_POST['judul_materi_pokok']);
        $mata_pelajaran_id = mysqli_real_escape_string($conn, $_POST['mata_pelajaran_id']);
        $kelas_id = mysqli_real_escape_string($conn, $_POST['kelas_id']);
        
        // Check if materi pokok already exists for this mapel and kelas
        $check_query = "SELECT id FROM materi_pokok 
                       WHERE mata_pelajaran_id = ? AND kelas_id = ? AND judul_materi_pokok = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "iis", $mata_pelajaran_id, $kelas_id, $judul_materi_pokok);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            echo json_encode(['success' => false, 'message' => 'Materi pokok sudah ada untuk mata pelajaran dan kelas ini']);
            exit;
        }
        
        $query = "INSERT INTO materi_pokok (judul_materi_pokok, mata_pelajaran_id, kelas_id) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sii", $judul_materi_pokok, $mata_pelajaran_id, $kelas_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Materi pokok berhasil dibuat']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal membuat materi pokok: ' . mysqli_error($conn)]);
        }
    }
    elseif ($action == 'update') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $judul_materi_pokok = mysqli_real_escape_string($conn, $_POST['judul_materi_pokok']);
        $mata_pelajaran_id = mysqli_real_escape_string($conn, $_POST['mata_pelajaran_id']);
        $kelas_id = mysqli_real_escape_string($conn, $_POST['kelas_id']);
        
        $query = "UPDATE materi_pokok SET judul_materi_pokok = ?, mata_pelajaran_id = ?, kelas_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "siii", $judul_materi_pokok, $mata_pelajaran_id, $kelas_id, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Materi pokok berhasil diupdate']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengupdate materi pokok']);
        }
    }
    elseif ($action == 'delete') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        
        $query = "DELETE FROM materi_pokok WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Materi pokok berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus materi pokok']);
        }
    }
}
?>