<?php
// db_connect.php - reusable connection file

$host     = "localhost";          // or "127.0.0.1"
$user     = "root";
$pass     = "";                   // empty by default in XAMPP
$dbname   = "caztech";          // your database name

// Using MySQLi (recommended for beginners)
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully!"; // test line (remove later)
?>