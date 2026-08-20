<?php
declare(strict_types=1);

require_once '../includes/auth.php';
require_auth();
require_once '../includes/db_connect.php';

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

$lead_table_sql = "CREATE TABLE IF NOT EXISTS leads (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    business VARCHAR(255) NOT NULL,
    project_type TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_leads_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

$table_error = '';
$pending = null;
$approved = null;
$leads = null;

try {
    if (!$conn->query($testimonial_table_sql)) {
        throw new RuntimeException('Unable to initialize testimonials storage.');
    }
    if (!$conn->query($lead_table_sql)) {
        throw new RuntimeException('Unable to initialize leads storage.');
    }

    if (isset($_GET['approve_id'])) {
        $id = (int) ($_GET['approve_id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE testimonials SET approved = 1 WHERE id = ?');
            if (!$stmt) {
                throw new RuntimeException('Unable to prepare review approval.');
            }
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) {
                throw new RuntimeException('Unable to approve review.');
            }
            $stmt->close();
        }
        header('Location: reviews.php?approved=1');
        exit;
    }

    if (isset($_GET['delete_id'])) {
        $id = (int) ($_GET['delete_id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare('DELETE FROM testimonials WHERE id = ?');
            if (!$stmt) {
                throw new RuntimeException('Unable to prepare review deletion.');
            }
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) {
                throw new RuntimeException('Unable to delete review.');
            }
            $stmt->close();
        }
        header('Location: reviews.php?deleted=1');
        exit;
    }

    if (isset($_GET['delete_lead_id'])) {
        $id = (int) ($_GET['delete_lead_id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare('DELETE FROM leads WHERE id = ?');
            if (!$stmt) {
                throw new RuntimeException('Unable to prepare message deletion.');
            }
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) {
                throw new RuntimeException('Unable to delete message.');
            }
            $stmt->close();
        }
        header('Location: reviews.php?lead_deleted=1');
        exit;
    }

    $pending = $conn->query('SELECT * FROM testimonials WHERE approved = 0 ORDER BY created_at DESC');
    $approved = $conn->query('SELECT * FROM testimonials WHERE approved = 1 ORDER BY created_at DESC');
    $leads = $conn->query('SELECT * FROM leads ORDER BY created_at DESC');

    if (!$pending || !$approved || !$leads) {
        throw new RuntimeException('Unable to load reviews and messages.');
    }
} catch (Throwable $e) {
    $table_error = 'Review and message storage is unavailable. Please import the database schema and try again.';
    error_log('CAZTech reviews storage error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../image/CAZTECH.png">
  <title>Manage Reviews | CAZTech Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body { background: #f4f6f9; font-family: 'Inter', sans-serif; }
    .card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .star-gold { color: #f59e0b; }
    .star-empty { color: #d1d5db; }
    .confirmation-dialog { width: calc(100% - 1.5rem); max-width: 440px; margin-right: auto; margin-left: auto; }
    .confirmation-modal .modal-content { border: 0; border-radius: 22px; overflow: hidden; box-shadow: 0 1.5rem 3.5rem rgba(15, 23, 42, .24); }
    .confirmation-modal .modal-header { border-bottom: 0; background: linear-gradient(135deg, #eff6ff, #f8fafc); padding: 1.35rem 1.45rem 1.1rem; }
    .confirmation-modal .modal-body { padding: 1.25rem 1.45rem .65rem; color: #64748b; font-size: 1rem; line-height: 1.65; }
    .confirmation-modal .modal-footer { border-top: 0; padding: 1.05rem 1.45rem 1.45rem; }
    .confirmation-icon { width: 2.9rem; height: 2.9rem; flex: 0 0 auto; display: inline-flex; align-items: center; justify-content: center; border-radius: 15px; font-size: 1.2rem; }
    .confirmation-icon.success { color: #047857; background: #d1fae5; }
    .confirmation-icon.danger { color: #b91c1c; background: #fee2e2; }
    .confirmation-modal .modal-title { color: #1e293b; font-size: 1.3rem; line-height: 1.25; }
    .confirmation-modal .btn { min-height: 44px; padding: .6rem 1rem; border-radius: 11px; font-weight: 700; }
    .confirmation-modal .btn-light { border: 1px solid #e2e8f0; color: #475569; }
    .confirmation-modal .btn-light:hover { background: #f1f5f9; }
    @media (max-width: 480px) {
      .confirmation-dialog { width: calc(100% - 1rem); }
      .confirmation-modal .modal-header { padding: 1.1rem 1.1rem .9rem; }
      .confirmation-modal .modal-body { padding: 1.05rem 1.1rem .45rem; }
      .confirmation-modal .modal-footer { padding: .9rem 1.1rem 1.1rem; }
      .confirmation-modal .modal-footer .btn { flex: 1 1 0; }
    }
  </style>
</head>
<body>

<div class="container py-4" style="max-width:960px;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-chat-quote me-2"></i>Manage Reviews &amp; Messages</h4>
    <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Dashboard</a>
  </div>

  <?php if (isset($_GET['approved'])): ?>
    <div class="alert alert-success alert-dismissible fade show">Review approved successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>
  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">Review deleted.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>
  <?php if (isset($_GET['lead_deleted'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">Message deleted.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
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
                  <a href="?approve_id=<?php echo (int) $r['id']; ?>" class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#reviewActionModal" data-confirm-url="?approve_id=<?php echo (int) $r['id']; ?>" data-confirm-title="Approve this review?" data-confirm-message="This review from <?php echo htmlspecialchars((string) $r['name'], ENT_QUOTES, 'UTF-8'); ?> will become visible on the public site." data-confirm-label="Approve review" data-confirm-variant="success"><i class="bi bi-check-lg"></i> Approve</a>
                  <a href="?delete_id=<?php echo (int) $r['id']; ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reviewActionModal" data-confirm-url="?delete_id=<?php echo (int) $r['id']; ?>" data-confirm-title="Delete this review?" data-confirm-message="This will permanently remove the review from <?php echo htmlspecialchars((string) $r['name'], ENT_QUOTES, 'UTF-8'); ?>." data-confirm-label="Delete review" data-confirm-variant="danger" aria-label="Delete review from <?php echo htmlspecialchars((string) $r['name'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash3"></i></a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Contact Messages -->
  <div class="card mb-4">
    <div class="card-header bg-info bg-opacity-10 fw-semibold">
      <i class="bi bi-envelope me-1"></i> Contact Messages (<?php echo $leads->num_rows; ?>)
    </div>
    <div class="card-body p-0">
      <?php if ($leads->num_rows === 0): ?>
        <p class="text-muted text-center py-4 mb-0">No contact messages yet.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th>Name</th><th>Email / Business</th><th>Message</th><th>Date</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
              <?php while ($lead = $leads->fetch_assoc()): ?>
              <tr>
                <td><strong><?php echo htmlspecialchars((string) $lead['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                <td><?php echo htmlspecialchars((string) $lead['business'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="min-width:280px; max-width:420px; white-space:pre-wrap;"><?php echo htmlspecialchars((string) $lead['project_type'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><small><?php echo htmlspecialchars(date('M j, Y g:i A', strtotime((string) $lead['created_at'])), ENT_QUOTES, 'UTF-8'); ?></small></td>
                <td class="text-center">
                  <a href="?delete_lead_id=<?php echo (int) $lead['id']; ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reviewActionModal" data-confirm-url="?delete_lead_id=<?php echo (int) $lead['id']; ?>" data-confirm-title="Delete this message?" data-confirm-message="This will permanently remove the message from <?php echo htmlspecialchars((string) $lead['name'], ENT_QUOTES, 'UTF-8'); ?>." data-confirm-label="Delete message" data-confirm-variant="danger" aria-label="Delete message from <?php echo htmlspecialchars((string) $lead['name'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash3"></i></a>
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
                  <a href="?delete_id=<?php echo (int) $r['id']; ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reviewActionModal" data-confirm-url="?delete_id=<?php echo (int) $r['id']; ?>" data-confirm-title="Delete this review?" data-confirm-message="This will permanently remove the review from <?php echo htmlspecialchars((string) $r['name'], ENT_QUOTES, 'UTF-8'); ?>." data-confirm-label="Delete review" data-confirm-variant="danger" aria-label="Delete review from <?php echo htmlspecialchars((string) $r['name'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash3"></i></a>
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

<!-- Styled confirmation modal for review/message actions -->
<div class="modal fade confirmation-modal" id="reviewActionModal" tabindex="-1" aria-labelledby="confirmation-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered confirmation-dialog">
    <div class="modal-content">
      <div class="modal-header align-items-start gap-3">
        <div id="confirmation-icon" class="confirmation-icon danger" aria-hidden="true"><i class="bi bi-trash3"></i></div>
        <div class="flex-grow-1">
          <p class="mb-1 text-uppercase small fw-bold text-secondary" style="letter-spacing:.12em;">CAZTech Admin</p>
          <h5 class="modal-title fw-bold mb-0" id="confirmation-title">Confirm action</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close confirmation"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0" id="confirmation-message">Are you sure you want to continue?</p>
      </div>
      <div class="modal-footer gap-2">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <a id="confirmation-submit" href="#" class="btn btn-danger">Confirm</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  (() => {
    const modal = document.getElementById('reviewActionModal');
    const title = document.getElementById('confirmation-title');
    const message = document.getElementById('confirmation-message');
    const submit = document.getElementById('confirmation-submit');
    const icon = document.getElementById('confirmation-icon');

    document.querySelectorAll('[data-bs-toggle="modal"][data-confirm-url]').forEach(trigger => {
      trigger.addEventListener('click', event => event.preventDefault());
    });

    modal?.addEventListener('show.bs.modal', event => {
      const trigger = event.relatedTarget;
      if (!trigger) return;

      const variant = trigger.getAttribute('data-confirm-variant') === 'success' ? 'success' : 'danger';
      title.textContent = trigger.getAttribute('data-confirm-title') || 'Confirm action';
      message.textContent = trigger.getAttribute('data-confirm-message') || 'Are you sure you want to continue?';
      submit.href = trigger.getAttribute('data-confirm-url') || '#';
      submit.textContent = trigger.getAttribute('data-confirm-label') || 'Confirm';
      submit.classList.toggle('btn-success', variant === 'success');
      submit.classList.toggle('btn-danger', variant !== 'success');
      icon.classList.toggle('success', variant === 'success');
      icon.classList.toggle('danger', variant !== 'success');
      icon.innerHTML = variant === 'success'
        ? '<i class="bi bi-check-lg"></i>'
        : '<i class="bi bi-trash3"></i>';
    });

    modal?.addEventListener('hidden.bs.modal', () => {
      submit.href = '#';
    });
  })();
</script>
</body>
</html>
