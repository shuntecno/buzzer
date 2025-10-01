<?php
$host = "localhost";
$db_name = "buzzer_db";
$username = "root"; // default Laragon
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>