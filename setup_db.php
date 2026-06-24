<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS recipehub_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db("recipehub_db");

// Create table resep
$sql_table = "CREATE TABLE IF NOT EXISTS resep (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_resep VARCHAR(255) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    bahan TEXT NOT NULL,
    langkah TEXT NOT NULL,
    gambar VARCHAR(255) NULL,
    sumber VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql_table) === TRUE) {
    echo "Table 'resep' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();
?>
