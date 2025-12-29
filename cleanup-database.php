<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=inflight_catering_db', 'root', '');
    
    echo "Disabling foreign key checks...\n";
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
    
    echo "Getting list of tables...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "Found " . count($tables) . " tables\n";
        foreach ($tables as $table) {
            echo "Dropping table: $table\n";
            try {
                $pdo->exec("DROP TABLE IF EXISTS `$table`");
                echo "  ✓ Dropped $table\n";
            } catch (PDOException $e) {
                echo "  ✗ Error dropping $table: " . $e->getMessage() . "\n";
                // Try to discard tablespace first
                try {
                    $pdo->exec("ALTER TABLE `$table` DISCARD TABLESPACE");
                    echo "  → Discarded tablespace for $table\n";
                    $pdo->exec("DROP TABLE IF EXISTS `$table`");
                    echo "  ✓ Dropped $table after discarding tablespace\n";
                } catch (PDOException $e2) {
                    echo "  ✗ Still failed: " . $e2->getMessage() . "\n";
                }
            }
        }
    } else {
        echo "No tables found in database\n";
    }
    
    // Check for .ibd files that might be orphaned
    echo "\nChecking for remaining issues...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $remaining = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($remaining) == 0) {
        echo "All tables removed successfully!\n";
    } else {
        echo "Warning: " . count($remaining) . " tables still remain\n";
    }
    
    echo "\nRe-enabling foreign key checks...\n";
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    
    echo "\nDatabase cleanup complete!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
