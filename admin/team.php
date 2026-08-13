<?php
require_once '../includes/auth.php';
require_auth();
require_once '../includes/db_connect.php';

$deletion_error = '';

// Handle Deletion via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);

    // First, fetch the image path to delete the physical file
    $stmt = $conn->prepare("SELECT image_path FROM team_members WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if (!empty($row['image_path']) && file_exists('../' . $row['image_path'])) {
                @unlink('../' . $row['image_path']);
            }
        }
        $stmt->close();
    }

    // Delete the record
    $stmt2 = $conn->prepare("DELETE FROM team_members WHERE id = ?");
    if ($stmt2) {
        $stmt2->bind_param("i", $id);
        if ($stmt2->execute()) {
            $stmt2->close();
            header("Location: team.php?deleted=1");
            exit;
        } else {
            $deletion_error = "DB Error: " . $stmt2->error;
            $stmt2->close();
        }
    } else {
        $deletion_error = "DB Prepare Error: " . $conn->error;
    }
}

// Fetch all team members
$sql = "SELECT * FROM team_members ORDER BY id ASC";
$result = $conn->query($sql);
$team = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $team[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Team - CAZTECH Admin</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Tailwind via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body {
      background: #f0f4f8;
      font-family: 'Inter', 'Segoe UI', sans-serif;
    }
    .avatar-circle {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #e2e8f0;
      font-weight: 700;
      font-size: 15px;
      color: #64748b;
      border: 2px solid #cbd5e1;
    }
    .avatar-circle img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .card-main {
      border-radius: 1.25rem;
      box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
    .navbar-brand .badge-caz {
      background: #0f172a;
      color: #fff;
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 0.1em;
      padding: 4px 10px;
      border-radius: 6px;
    }
    .table th {
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.07em;
      color: #64748b;
    }
    .btn-action {
      width: 34px;
      height: 34px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      border: none;
      font-size: 15px;
      transition: all 0.15s;
    }
    .btn-edit {
      background: #f1f5f9;
      color: #475569;
    }
    .btn-edit:hover {
      background: #e2e8f0;
      color: #1e293b;
    }
    .btn-delete {
      background: #fff1f2;
      color: #e11d48;
    }
    .btn-delete:hover {
      background: #ffe4e6;
      color: #be123c;
    }
    .role-badge {
      font-size: 0.72rem;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 50px;
      background: #f1f5f9;
      color: #475569;
    }
    .alert-soft-success {
      background: #f0fdf4;
      border-color: #bbf7d0;
      color: #166534;
    }
    .alert-soft-danger {
      background: #fff1f2;
      border-color: #fecdd3;
      color: #9f1239;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar px-4 py-3" style="background:#ffffff; border-bottom:1px solid #e2e8f0;">
    <div class="container-fluid max-w-5xl mx-auto d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-3">
        <span class="badge-caz navbar-brand mb-0">CAZ</span>
        <span class="fw-bold fs-6 text-dark">Manage Team</span>
      </div>
      <a href="index.php" class="text-muted text-decoration-none d-flex align-items-center gap-1 fs-sm">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
      </a>
    </div>
  </nav>

  <!-- Main -->
  <div class="container py-5" style="max-width:980px;">
    <div class="card card-main border-0">
      <div class="card-body p-4 p-md-5">

        <!-- Header Row -->
        <div class="d-flex align-items-start justify-content-between mb-4">
          <div>
            <h2 class="fw-bold mb-1">Team Roster</h2>
            <p class="text-muted mb-0" style="font-size:0.9rem;">Manage the visionaries behind CAZTech.</p>
          </div>
          <a href="add_team.php" class="btn btn-dark d-flex align-items-center gap-2" style="border-radius:10px;">
            <i class="bi bi-plus-lg"></i> Add Member
          </a>
        </div>

        <!-- Alerts -->
        <?php if ($deletion_error): ?>
          <div class="alert alert-soft-danger alert-dismissible border fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($deletion_error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
          <div class="alert alert-soft-success alert-dismissible border fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>Member deleted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
          <div class="alert alert-soft-success alert-dismissible border fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>Member added successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        <?php if (isset($_GET['updated'])): ?>
          <div class="alert alert-soft-success alert-dismissible border fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>Member updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <!-- Table -->
        <div class="table-responsive rounded-3 border">
          <table class="table table-hover align-middle mb-0">
            <thead style="background:#f8fafc;">
              <tr>
                <th class="px-3 py-3" style="width:70px;">Photo</th>
                <th class="py-3">Name</th>
                <th class="py-3">Role</th>
                <th class="py-3 text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($team)): ?>
              <tr>
                <td colspan="4" class="text-center text-muted py-5">
                  <i class="bi bi-people" style="font-size:2rem; display:block; margin-bottom:8px;"></i>
                  No team members found. <a href="add_team.php" class="text-dark fw-semibold">Add one</a>
                </td>
              </tr>
              <?php else: ?>
                <?php foreach ($team as $m): ?>
                <tr>
                  <td class="ps-4">
                    <div class="avatar-circle">
                      <?php if (!empty($m['image_path'])): ?>
                        <img src="../<?php echo htmlspecialchars($m['image_path']); ?>" alt="">
                      <?php else: ?>
                        <?php echo htmlspecialchars(strtoupper(substr($m['name'], 0, 1))); ?>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td class="fw-semibold"><?php echo htmlspecialchars($m['name']); ?></td>
                  <td><span class="role-badge"><?php echo htmlspecialchars($m['role']); ?></span></td>
                  <td class="text-end pe-4">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                      <a href="edit_team.php?id=<?php echo $m['id']; ?>" class="btn-action btn-edit" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>

                      <!-- Delete triggers Bootstrap modal -->
                      <button type="button"
                              class="btn-action btn-delete"
                              data-bs-toggle="modal"
                              data-bs-target="#deleteModal"
                              data-id="<?php echo $m['id']; ?>"
                              data-name="<?php echo htmlspecialchars($m['name']); ?>"
                              title="Delete">
                        <i class="bi bi-trash3"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
        <div class="modal-body text-center p-5">
          <div class="mb-3" style="width:60px;height:60px;border-radius:50%;background:#fff1f2;display:flex;align-items:center;justify-content:center;margin:0 auto;">
            <i class="bi bi-trash3-fill text-danger" style="font-size:1.5rem;"></i>
          </div>
          <h5 class="fw-bold mb-2">Delete Team Member</h5>
          <p class="text-muted mb-4">Are you sure you want to delete <strong id="modal-member-name"></strong>? This action cannot be undone.</p>

          <form method="POST" action="team.php" id="delete-form">
            <input type="hidden" name="delete_id" id="modal-delete-id" value="">
            <div class="d-flex gap-2 justify-content-center">
              <button type="button" class="btn btn-light px-4 fw-semibold" style="border-radius:10px;" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-danger px-4 fw-semibold" style="border-radius:10px;">
                <i class="bi bi-trash3 me-1"></i> Delete
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Bootstrap modal: populate member ID and name before showing
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget; // Button that triggered the modal
      const id = button.getAttribute('data-id');
      const name = button.getAttribute('data-name');

      document.getElementById('modal-member-name').textContent = name;
      document.getElementById('modal-delete-id').value = id;
    });
  </script>
</body>
</html>
