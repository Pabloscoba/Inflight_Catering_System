<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    
    echo "Getting MySQL data directory...\n";
    $stmt = $pdo->query("SHOW VARIABLES LIKE 'datadir'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $datadir = $result['Value'];
    
    echo "MySQL data directory: $datadir\n";
    
    $dbPath = rtrim($datadir, '/\\') . DIRECTORY_SEPARATOR . 'inflight_catering_db';
    
    echo "\nDatabase directory: $dbPath\n";
    
    if (is_dir($dbPath)) {
        echo "Directory exists. Listing contents:\n\n";
        $files = scandir($dbPath);
        
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $fullPath = $dbPath . DIRECTORY_SEPARATOR . $file;
                $size = filesize($fullPath);
                echo "  - $file (" . number_format($size) . " bytes)\n";
            }
        }
        
        // Count .ibd and .frm files
        $ibdFiles = glob($dbPath . DIRECTORY_SEPARATOR . '*.ibd');
        $frmFiles = glob($dbPath . DIRECTORY_SEPARATOR . '*.frm');
        
        echo "\nOrphaned files:\n";
        echo "  .ibd files: " . count($ibdFiles) . "\n";
        echo "  .frm files: " . count($frmFiles) . "\n";
        
        if (count($ibdFiles) > 0 || count($frmFiles) > 0) {
            echo "\nThese orphaned files need to be manually removed.\n";
            echo "You can delete them manually or I can try to remove them.\n";
        }
    } else {
        echo "Database directory does not exist.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
