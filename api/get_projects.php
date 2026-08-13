<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$sql = "SELECT id, title, category, description, icon_svg, icon_image, bg_class, project_url, created_at FROM projects ORDER BY created_at DESC";
$result = $conn->query($sql);

$projects = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

echo json_encode($projects);
?>
