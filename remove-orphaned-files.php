<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    
    // Get MySQL data directory
    $stmt = $pdo->query("SHOW VARIABLES LIKE 'datadir'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $datadir = $result['Value'];
    
    $dbPath = rtrim($datadir, '/\\') . DIRECTORY_SEPARATOR . 'inflight_catering_db';
    
    echo "Database directory: $dbPath\n\n";
    
    if (!is_dir($dbPath)) {
        echo "Database directory does not exist.\n";
        exit(0);
    }
    
    // Get all .ibd files
    $ibdFiles = glob($dbPath . DIRECTORY_SEPARATOR . '*.ibd');
    $frmFiles = glob($dbPath . DIRECTORY_SEPARATOR . '*.frm');
    $allFiles = array_merge($ibdFiles, $frmFiles);
    
    if (count($allFiles) == 0) {
        echo "No orphaned files found.\n";
        exit(0);
    }
    
    echo "Found " . count($allFiles) . " orphaned files to remove:\n\n";
    
    $removed = 0;
    $failed = 0;
    
    foreach ($allFiles as $file) {
        $filename = basename($file);
        echo "Removing: $filename ... ";
        
        if (unlink($file)) {
            echo "✓ Done\n";
            $removed++;
        } else {
            echo "✗ Failed\n";
            $failed++;
        }
    }
    
    echo "\n";
    echo "Summary:\n";
    echo "  Removed: $removed\n";
    echo "  Failed: $failed\n";
    
    if ($removed > 0) {
        echo "\nOrphaned files removed successfully!\n";
        echo "You can now run 'php artisan migrate' to create the tables.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
