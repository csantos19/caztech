<?php
include 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic validation and sanitization
    $name     = isset($_POST['name']) ? trim($_POST['name']) : '';
    $business = isset($_POST['business']) ? trim($_POST['business']) : '';
    $project  = isset($_POST['project']) ? trim($_POST['project']) : '';
    $rating   = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
    $form_type = isset($_POST['form_type']) ? trim($_POST['form_type']) : 'contact';

    if ($form_type === 'review') {
        // ── Review / Testimonial submission ──
        if (!empty($name) && !empty($project)) {
            $stmt = $conn->prepare("INSERT INTO testimonials (name, business, role, review, rating, approved) VALUES (?, ?, ?, ?, ?, 0)");
            if ($stmt) {
                $role = 'Client';
                $stmt->bind_param("ssssi", $name, $business, $role, $project, $rating);
                $stmt->execute();
                $stmt->close();
            }
        }
        header('Location: ../index.php?review=success#testimonials');
        exit;
    }

    // ── Contact / Lead submission ──
    if (!empty($name) && !empty($business) && !empty($project)) {
        // Use prepared statements to prevent SQL Injection
        $stmt = $conn->prepare("INSERT INTO leads (name, business, project_type) VALUES (?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sss", $name, $business, $project);

            if ($stmt->execute()) {
                echo "Thank you! We'll contact you soon.";
            } else {
                echo "Error executing query: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Please fill in all fields.";
    }
}

$conn->close();
?>