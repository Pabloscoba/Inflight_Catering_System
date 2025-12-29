<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    
    echo "Dropping database...\n";
    $pdo->exec('DROP DATABASE IF EXISTS inflight_catering_db');
    
    echo "Creating database...\n";
    $pdo->exec('CREATE DATABASE inflight_catering_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    
    echo "Database recreated successfully!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
