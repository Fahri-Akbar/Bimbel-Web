<?php
// Check if PhpSpreadsheet is available
if (file_exists('../../vendor/autoload.php')) {
    require_once '../../vendor/autoload.php';
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="template_topik.xlsx"');
    header('Cache-Control: max-age=0');
    
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set header
    $sheet->setCellValue('A1', 'JUDUL_TOPIK');
    $sheet->setCellValue('B1', 'PENJELASAN');
    $sheet->setCellValue('C1', 'LINK_VIDEO');
    
    // Set width
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(50);
    $sheet->getColumnDimension('C')->setWidth(40);
    
    // Style header
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => '4F81BD']
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ]
    ];
    
    $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);
    
    // Contoh data
    $examples = [
        ['Pengenalan Aljabar', 'Aljabar adalah cabang matematika yang mempelajari struktur, hubungan, dan kuantitas.', 'https://youtube.com/embed/example1'],
        ['Operasi Dasar Aljabar', 'Memahami operasi penjumlahan, pengurangan, perkalian, dan pembagian dalam aljabar.', 'https://youtube.com/embed/example2'],
        ['Persamaan Linear', 'Belajar menyelesaikan persamaan linear dengan satu variabel.', ''],
    ];
    
    $row = 2;
    foreach ($examples as $example) {
        $sheet->setCellValue('A' . $row, $example[0]);
        $sheet->setCellValue('B' . $row, $example[1]);
        $sheet->setCellValue('C' . $row, $example[2]);
        $row++;
    }
    
    // Style for examples
    $exampleStyle = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => 'F2F2F2']
        ]
    ];
    $sheet->getStyle('A2:C4')->applyFromArray($exampleStyle);
    
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    
} else {
    // Fallback to CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="template_topik.csv"');
    header('Cache-Control: max-age=0');
    
    $output = fopen('php://output', 'w');
    
    // Header
    fputcsv($output, ['JUDUL_TOPIK', 'PENJELASAN', 'LINK_VIDEO']);
    
    // Contoh data
    $examples = [
        ['Pengenalan Aljabar', 'Aljabar adalah cabang matematika yang mempelajari struktur, hubungan, dan kuantitas.', 'https://youtube.com/embed/example1'],
        ['Operasi Dasar Aljabar', 'Memahami operasi penjumlahan, pengurangan, perkalian, dan pembagian dalam aljabar.', 'https://youtube.com/embed/example2'],
        ['Persamaan Linear', 'Belajar menyelesaikan persamaan linear dengan satu variabel.', ''],
    ];
    
    foreach ($examples as $example) {
        fputcsv($output, $example);
    }
    
    fclose($output);
}

exit;
?>