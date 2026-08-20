<?php
declare(strict_types=1);

require_once __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php#contact');
    exit;
}

$name = trim((string) ($_POST['name'] ?? ''));
$business = trim((string) ($_POST['business'] ?? ''));
$project = trim((string) ($_POST['project'] ?? ''));
$rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
$form_type = trim((string) ($_POST['form_type'] ?? 'contact'));

if ($form_type === 'review') {
    if ($name === '' || $project === '') {
        header('Location: ../index.php?review=error#testimonials');
        exit;
    }

    $role = 'Client';
    $stmt = $conn->prepare('INSERT INTO testimonials (name, business, role, review, rating, approved) VALUES (?, ?, ?, ?, ?, 0)');
    $inserted = false;

    if ($stmt) {
        $stmt->bind_param('ssssi', $name, $business, $role, $project, $rating);
        $inserted = $stmt->execute();
        if (!$inserted) {
            error_log('CAZTech review insert failed: ' . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log('CAZTech review statement preparation failed: ' . $conn->error);
    }

    $conn->close();
    header('Location: ../index.php?review=' . ($inserted ? 'success' : 'error') . '#testimonials');
    exit;
}

if ($name === '' || $business === '' || $project === '') {
    http_response_code(422);
    echo 'Please fill in all fields.';
    $conn->close();
    exit;
}

$stmt = $conn->prepare('INSERT INTO leads (name, business, project_type) VALUES (?, ?, ?)');
if (!$stmt) {
    error_log('CAZTech contact statement preparation failed: ' . $conn->error);
    http_response_code(500);
    echo 'We could not save your message right now. Please try again later.';
    $conn->close();
    exit;
}

$stmt->bind_param('sss', $name, $business, $project);
$inserted = $stmt->execute();
if (!$inserted) {
    error_log('CAZTech contact insert failed: ' . $stmt->error);
}
$stmt->close();
$conn->close();

if ($inserted) {
    echo "Thank you! We'll contact you soon.";
} else {
    http_response_code(500);
    echo 'We could not save your message right now. Please try again later.';
}
?>