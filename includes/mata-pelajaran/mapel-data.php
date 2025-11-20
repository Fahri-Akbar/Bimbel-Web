<?php
session_start();
require_once '../../db/koneksi.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    // Get single mapel by ID
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query = "SELECT m.*, k.nama_kelas 
              FROM mata_pelajaran m 
              JOIN kelas k ON m.kelas_id = k.id 
              WHERE m.id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        echo json_encode(null);
    }
} else {
    // Get all mapel with kelas name
    $query = "SELECT m.*, k.nama_kelas 
              FROM mata_pelajaran m 
              JOIN kelas k ON m.kelas_id = k.id 
              ORDER BY k.nama_kelas, m.nama_mapel";
    
    $result = mysqli_query($conn, $query);
    $mapel = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $mapel[] = $row;
    }
    
    echo json_encode($mapel);
}
?>