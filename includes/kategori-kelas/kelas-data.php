<?php
session_start();
require_once '../../db/koneksi.php';

header('Content-Type: application/json');

if (isset($_GET['kelas_id'])) {
    // Get detail mata pelajaran untuk kelas tertentu
    $kelas_id = mysqli_real_escape_string($conn, $_GET['kelas_id']);
    
    $query = "SELECT nama_mapel FROM mata_pelajaran WHERE kelas_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $kelas_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $mapel = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $mapel[] = $row;
    }
    
    echo json_encode(['mapel' => $mapel]);
} else {
    // Get semua kelas dengan jumlah mapel
    $query = "SELECT k.id, k.nama_kelas, COUNT(m.id) as jumlah_mapel 
              FROM kelas k 
              LEFT JOIN mata_pelajaran m ON k.id = m.kelas_id 
              GROUP BY k.id, k.nama_kelas 
              ORDER BY k.nama_kelas";
    
    $result = mysqli_query($conn, $query);
    $kelas = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $kelas[] = $row;
    }
    
    echo json_encode($kelas);
}
?>