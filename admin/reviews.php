<?php
require_once '../includes/auth.php';
require_auth();
include '../includes/db_connect.php';

$testimonial_table_sql = "CREATE TABLE IF NOT EXISTS testimonials (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    business VARCHAR(255) DEFAULT NULL,
    role VARCHAR(100) DEFAULT NULL,
    review TEXT NOT NULL,
    rating TINYINT UNSIGNED NOT NULL DEFAULT 5,
    approved TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_testimonials_approved_created (approved, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

$table_error = '';
$pending = null;
$approved = null;

try {
    $conn->query($testimonial_table_sql);

    // Handle approval
    if (isset($_GET['approve_id'])) {
        $id = intval($_GET['approve_id']);
        $conn->query("UPDATE testimonials SET approved = 1 WHERE id = $id");
        header("Location: reviews.php?approved=1");
        exit;
    }

    // Handle deletion
    if (isset($_GET['delete_id'])) {
        $id = intval($_GET['delete_id']);
        $conn->query("DELETE FROM testimonials WHERE id = $id");
        header("Location: reviews.php?deleted=1");
        exit;
    }

    // Fetch pending and approved
    $pending  = $conn->query("SELECT * FROM testimonials WHERE approved = 0 ORDER BY created_at DESC");
    $approved = $conn->query("SELECT * FROM testimonials WHERE approved = 1 ORDER BY created_at DESC");
} catch (Throwable $e) {
    $table_error = 'Review storage is unavailable. Please import the database schema and try again.';
    error_log('CAZTech reviews storage error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Reviews | CAZTech Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body { background: #f4f6f9; font-family: 'Inter', sans-serif; }
    .card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .star-gold { color: #f59e0b; }
    .star-empty { color: #d1d5db; }
  </style>
</head>
<body>

<div class="container py-4" style="max-width:960px;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-chat-quote me-2"></i>Manage Reviews</h4>
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Dashboard</a>
  </div>

  <?php if (isset($_GET['approved'])): ?>
    <div class="alert alert-success alert-dismissible fade show">Review approved successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>
  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">Review deleted.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>

  <?php if ($table_error): ?>
    <div class="alert alert-danger">
      <?php echo htmlspecialchars($table_error, ENT_QUOTES, 'UTF-8'); ?>
    </div>
  <?php else: ?>

  <!-- Pending Reviews -->
  <div class="card mb-4">
    <div class="card-header bg-warning bg-opacity-10 fw-semibold">
      <i class="bi bi-clock me-1"></i> Pending Approval (<?php echo $pending->num_rows; ?>)
    </div>
    <div class="card-body p-0">
      <?php if ($pending->num_rows === 0): ?>
        <p class="text-muted text-center py-4 mb-0">No pending reviews.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th>Name</th><th>Review</th><th>Rating</th><th>Date</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
              <?php while ($r = $pending->fetch_assoc()): ?>
              <tr>
                <td><strong><?php echo htmlspecialchars($r['name']); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars($r['business']); ?></small></td>
                <td style="max-width:300px;"><?php echo htmlspecialchars($r['review']); ?></td>
                <td>
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="bi bi-star-fill <?php echo $i <= $r['rating'] ? 'star-gold' : 'star-empty'; ?>"></i>
                  <?php endfor; ?>
                </td>
                <td><small><?php echo date('M j, Y', strtotime($r['created_at'])); ?></small></td>
                <td class="text-center">
                  <a href="?approve_id=<?php echo $r['id']; ?>" class="btn btn-sm btn-success me-1" onclick="return confirm('Approve this review?')"><i class="bi bi-check-lg"></i> Approve</a>
                  <a href="?delete_id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this review?')"><i class="bi bi-trash3"></i></a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Approved Reviews -->
  <div class="card">
    <div class="card-header bg-success bg-opacity-10 fw-semibold">
      <i class="bi bi-check-circle me-1"></i> Approved (<?php echo $approved->num_rows; ?>)
    </div>
    <div class="card-body p-0">
      <?php if ($approved->num_rows === 0): ?>
        <p class="text-muted text-center py-4 mb-0">No approved reviews yet.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th>Name</th><th>Review</th><th>Rating</th><th>Date</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
              <?php while ($r = $approved->fetch_assoc()): ?>
              <tr>
                <td><strong><?php echo htmlspecialchars($r['name']); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars($r['role']); ?></small></td>
                <td style="max-width:300px;"><?php echo htmlspecialchars($r['review']); ?></td>
                <td>
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="bi bi-star-fill <?php echo $i <= $r['rating'] ? 'star-gold' : 'star-empty'; ?>"></i>
                  <?php endfor; ?>
                </td>
                <td><small><?php echo date('M j, Y', strtotime($r['created_at'])); ?></small></td>
                <td class="text-center">
                  <a href="?delete_id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this review?')"><i class="bi bi-trash3"></i></a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
