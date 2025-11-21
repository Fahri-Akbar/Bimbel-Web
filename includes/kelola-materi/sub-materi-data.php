<?php
session_start();
require_once '../../db/koneksi.php';

header('Content-Type: application/json');

if (isset($_GET['materi_pokok_id'])) {
    $materi_pokok_id = mysqli_real_escape_string($conn, $_GET['materi_pokok_id']);
    
    $query = "SELECT sm.*, COUNT(t.id) as jumlah_topik
              FROM sub_materi sm
              LEFT JOIN topik t ON sm.id = t.sub_materi_id
              WHERE sm.materi_pokok_id = ?
              GROUP BY sm.id
              ORDER BY sm.id";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $materi_pokok_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $sub_materi = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sub_materi[] = $row;
    }
    
    echo json_encode($sub_materi);
} else {
    echo json_encode([]);
}
?>