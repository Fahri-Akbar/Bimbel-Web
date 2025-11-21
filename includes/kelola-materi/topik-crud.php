<?php
session_start();
require_once '../../db/koneksi.php';

header('Content-Type: application/json');

// Create uploads directory if not exists
if (!file_exists('../../uploads/excel')) {
    mkdir('../../uploads/excel', 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sub_materi_id = mysqli_real_escape_string($conn, $_POST['sub_materi_id']);
    
    if (isset($_FILES['excel_topik']) && $_FILES['excel_topik']['error'] == 0) {
        $file_type = pathinfo($_FILES['excel_topik']['name'], PATHINFO_EXTENSION);
        
        if (in_array($file_type, ['xlsx', 'xls', 'csv'])) {
            $upload_path = '../../uploads/excel/' . uniqid() . '_' . $_FILES['excel_topik']['name'];
            
            if (move_uploaded_file($_FILES['excel_topik']['tmp_name'], $upload_path)) {
                try {
                    // Try using PhpSpreadsheet first
                    if (file_exists('../../vendor/autoload.php')) {
                        require_once '../../vendor/autoload.php';
                        $result = processExcelWithPhpSpreadsheet($conn, $sub_materi_id, $upload_path);
                    } else {
                        // Fallback to CSV processing
                        $result = processCSVFile($conn, $sub_materi_id, $upload_path);
                    }
                    
                    // Delete temporary file
                    unlink($upload_path);
                    
                    echo json_encode($result);
                    
                } catch (Exception $e) {
                    // Delete temporary file on error
                    if (file_exists($upload_path)) {
                        unlink($upload_path);
                    }
                    echo json_encode(['success' => false, 'message' => 'Error processing file: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal upload file']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Format file tidak didukung. Gunakan Excel atau CSV']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak ada file yang diupload']);
    }
}

function processExcelWithPhpSpreadsheet($conn, $sub_materi_id, $file_path) {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    return processDataRows($conn, $sub_materi_id, $rows);
}

function processCSVFile($conn, $sub_materi_id, $file_path) {
    $rows = [];
    if (($handle = fopen($file_path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $rows[] = $data;
        }
        fclose($handle);
    }
    
    return processDataRows($conn, $sub_materi_id, $rows);
}

function processDataRows($conn, $sub_materi_id, $rows) {
    $success_count = 0;
    $error_count = 0;
    $errors = [];
    
    // Skip header row (row 0)
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        
        // Skip empty rows
        if (empty($row[0]) && empty($row[1])) {
            continue;
        }
        
        $judul_topik = isset($row[0]) ? trim($row[0]) : '';
        $penjelasan = isset($row[1]) ? trim($row[1]) : '';
        $link_video = isset($row[2]) ? trim($row[2]) : null;
        
        // Validate required fields
        if (empty($judul_topik) || empty($penjelasan)) {
            $error_count++;
            $errors[] = "Baris " . ($i + 1) . ": Judul topik dan penjelasan wajib diisi";
            continue;
        }
        
        // Validate video URL format if provided
        if ($link_video && !filter_var($link_video, FILTER_VALIDATE_URL)) {
            $error_count++;
            $errors[] = "Baris " . ($i + 1) . ": Format link video tidak valid";
            continue;
        }
        
        // Check if topic already exists for this sub_materi
        $check_query = "SELECT id FROM topik WHERE sub_materi_id = ? AND judul_topik = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "is", $sub_materi_id, $judul_topik);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        $judul_topik_escaped = mysqli_real_escape_string($conn, $judul_topik);
        $penjelasan_escaped = mysqli_real_escape_string($conn, $penjelasan);
        $link_video_escaped = $link_video ? mysqli_real_escape_string($conn, $link_video) : null;
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // Update existing topic
            $query = "UPDATE topik SET penjelasan = ?, link_video = ?, updated_at = CURRENT_TIMESTAMP 
                     WHERE sub_materi_id = ? AND judul_topik = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssis", $penjelasan_escaped, $link_video_escaped, $sub_materi_id, $judul_topik_escaped);
        } else {
            // Insert new topic
            $urutan = $i;
            $query = "INSERT INTO topik (sub_materi_id, judul_topik, penjelasan, link_video, urutan) 
                     VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "isssi", $sub_materi_id, $judul_topik_escaped, $penjelasan_escaped, $link_video_escaped, $urutan);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $success_count++;
        } else {
            $error_count++;
            $errors[] = "Baris " . ($i + 1) . ": Gagal menyimpan ke database";
        }
        
        mysqli_stmt_close($stmt);
    }
    
    $message = "Berhasil memproses {$success_count} topik";
    if ($error_count > 0) {
        $message .= ", {$error_count} error" . (count($errors) > 0 ? ": " . implode(", ", array_slice($errors, 0, 3)) : "");
    }
    
    return [
        'success' => true,
        'message' => $message,
        'processed' => $success_count,
        'errors' => $errors
    ];
}
?>