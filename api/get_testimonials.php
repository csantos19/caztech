<?php
header('Content-Type: application/json');
include __DIR__ . '/../includes/db_connect.php';

$result = $conn->query("SELECT name, business, role, review, rating FROM testimonials WHERE approved = 1 ORDER BY created_at DESC");
$testimonials = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

echo json_encode($testimonials);
$conn->close();
