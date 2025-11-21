<?php
session_start();
require_once '../../db/koneksi.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    // Get single materi pokok by ID
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query = "SELECT mp.*, mpl.nama_mapel, k.nama_kelas 
              FROM materi_pokok mp
              JOIN mata_pelajaran mpl ON mp.mata_pelajaran_id = mpl.id
              JOIN kelas k ON mp.kelas_id = k.id
              WHERE mp.id = ?";
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
    // Get all materi pokok with counts
    $query = "SELECT mp.*, 
                     mpl.nama_mapel, 
                     k.nama_kelas,
                     COUNT(DISTINCT sm.id) as jumlah_sub_materi,
                     COUNT(DISTINCT t.id) as jumlah_topik
              FROM materi_pokok mp
              JOIN mata_pelajaran mpl ON mp.mata_pelajaran_id = mpl.id
              JOIN kelas k ON mp.kelas_id = k.id
              LEFT JOIN sub_materi sm ON mp.id = sm.materi_pokok_id
              LEFT JOIN topik t ON sm.id = t.sub_materi_id
              GROUP BY mp.id
              ORDER BY k.nama_kelas, mpl.nama_mapel, mp.judul_materi_pokok";

    $result = mysqli_query($conn, $query);
    $materi = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $materi[] = $row;
    }
    
    echo json_encode($materi);
}
?>