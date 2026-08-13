<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db_connect.php';

require_auth('../login.php');

// Fetch stats
$total_leads = 0;
$result = $conn->query("SELECT COUNT(*) as cnt FROM leads");
if ($result) {
    $row         = $result->fetch_assoc();
    $total_leads = (int) ($row['cnt'] ?? 0);
}

$total_projects = 0;
$result2 = $conn->query("SELECT COUNT(*) as cnt FROM projects");
if ($result2) {
    $row2           = $result2->fetch_assoc();
    $total_projects = (int) ($row2['cnt'] ?? 0);
}

$total_team = 0;
$result3 = $conn->query("SELECT COUNT(*) as cnt FROM team_members");
if ($result3) {
    $row3       = $result3->fetch_assoc();
    $total_team = (int) ($row3['cnt'] ?? 0);
}

$admin_email = $_SESSION['caztech_admin_email'] ?? 'Admin';
$login_time  = isset($_SESSION['caztech_login_time'])
    ? date('M d, Y H:i', $_SESSION['caztech_login_time'])
    : '—';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - CAZTECH</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body {
      background: #f0f4f8;
      font-family: 'Inter','Segoe UI', sans-serif;
    }
    .badge-caz {
      background: #0f172a;
      color: #fff;
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 0.1em;
      padding: 4px 10px;
      border-radius: 6px;
    }
    .card-main {
      border-radius: 1.25rem;
      box-shadow: 0 10px 40px rgba(0,0,0,0.07);
      border: none;
    }
    .stat-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      flex-shrink: 0;
    }
    .action-card {
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      text-decoration: none;
      color: inherit;
      transition: all 0.18s;
      background: #fff;
      cursor: pointer;
      position: relative;
      z-index: 1;
    }
    .action-card:hover {
      border-color: #94a3b8;
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      color: inherit;
      text-decoration: none;
    }
    .action-card:active {
      transform: translateY(1px);
    }
    .action-card .ac-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      flex-shrink: 0;
    }
    .action-card.danger {
      border-color: #fecdd3;
      background: #fff1f2;
    }
    .action-card.danger:hover {
      border-color: #fca5a5;
      box-shadow: 0 4px 16px rgba(225,29,72,0.08);
    }
    .welcome-card {
      background: linear-gradient(135deg, #0f172a 60%, #1e3a5f);
      color: #fff;
      border-radius: 1.25rem;
      box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    }
    .nav-top {
      background: #ffffff;
      border-bottom: 1px solid #e2e8f0;
    }
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar px-4 py-3 nav-top sticky-top">
    <div class="container-fluid" style="max-width:1100px; margin:auto;">
      <div class="d-flex align-items-center gap-3">
        <img src="../image/Logo1.png" alt="CAZTech" style="height:36px; object-fit:contain;">
        <span class="text-muted fw-medium" style="font-size:0.85rem;">Admin Panel</span>
      </div>
      <div class="d-flex align-items-center gap-3">
        <span class="text-muted d-none d-md-inline" style="font-size:0.85rem;"><?php echo htmlspecialchars($admin_email); ?></span>
        <a href="../includes/logout.php" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="border-radius:8px;">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container py-5" style="max-width:1100px;">

    <!-- Welcome Banner -->
    <div class="welcome-card p-4 p-md-5 mb-4 d-flex align-items-center gap-4">
      <div class="stat-icon" style="background:rgba(255,255,255,0.15); font-size:1.5rem;">
        <i class="bi bi-shield-check text-white"></i>
      </div>
      <div>
        <h1 class="fw-bold mb-1" style="font-size:1.5rem;">Welcome back!</h1>
        <p class="mb-0 opacity-75" style="font-size:0.88rem;">
          Logged in as <strong><?php echo htmlspecialchars($admin_email); ?></strong>
          &nbsp;·&nbsp; Session started <?php echo $login_time; ?>
        </p>
      </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
      <div class="col-12 col-sm-4">
        <div class="card card-main p-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="text-muted mb-0 fw-semibold" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:.06em;">Total Inquiries</p>
            <div class="stat-icon" style="background:#eff6ff;"><i class="bi bi-chat-text text-primary"></i></div>
          </div>
          <p class="fw-bold mb-1" style="font-size:2.2rem;"><?php echo $total_leads; ?></p>
          <p class="text-muted mb-0" style="font-size:0.78rem;">From the contact form</p>
        </div>
      </div>
      <div class="col-12 col-sm-4">
        <div class="card card-main p-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="text-muted mb-0 fw-semibold" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:.06em;">Projects</p>
            <div class="stat-icon" style="background:#f0fdf4;"><i class="bi bi-kanban text-success"></i></div>
          </div>
          <p class="fw-bold mb-1" style="font-size:2.2rem;"><?php echo $total_projects; ?></p>
          <p class="text-muted mb-0" style="font-size:0.78rem;">Active showcase projects</p>
        </div>
      </div>
      <div class="col-12 col-sm-4">
        <div class="card card-main p-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="text-muted mb-0 fw-semibold" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:.06em;">Team Members</p>
            <div class="stat-icon" style="background:#fdf4ff;"><i class="bi bi-people text-purple" style="color:#a855f7;"></i></div>
          </div>
          <p class="fw-bold mb-1" style="font-size:2.2rem;"><?php echo $total_team; ?></p>
          <p class="text-muted mb-0" style="font-size:0.78rem;">Listed on homepage</p>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="card card-main p-4 p-md-5">
      <h5 class="fw-bold mb-4">Quick Actions</h5>
      <div class="row g-3">

        <!-- Manage Projects -->
        <div class="col-12 col-sm-6 col-md-4">
          <a href="projects.php" class="action-card">
            <div class="ac-icon"><i class="bi bi-kanban text-dark"></i></div>
            <div>
              <p class="fw-semibold mb-0" style="font-size:0.88rem;">Manage Projects</p>
              <p class="text-muted mb-0" style="font-size:0.76rem;">Add, edit, or remove projects</p>
            </div>
          </a>
        </div>

        <!-- Manage Team -->
        <div class="col-12 col-sm-6 col-md-4">
          <a href="team.php" class="action-card">
            <div class="ac-icon"><i class="bi bi-people text-dark"></i></div>
            <div>
              <p class="fw-semibold mb-0" style="font-size:0.88rem;">Manage Team</p>
              <p class="text-muted mb-0" style="font-size:0.76rem;">Add, edit, or remove members</p>
            </div>
          </a>
        </div>

        <!-- View Homepage -->
        <div class="col-12 col-sm-6 col-md-4">
          <a href="../index.php" class="action-card" target="_blank">
            <div class="ac-icon"><i class="bi bi-house text-dark"></i></div>
            <div>
              <p class="fw-semibold mb-0" style="font-size:0.88rem;">View Homepage</p>
              <p class="text-muted mb-0" style="font-size:0.76rem;">See the public site</p>
            </div>
          </a>
        </div>

        <!-- Manage Reviews -->
        <div class="col-12 col-sm-6 col-md-4">
          <a href="reviews.php" class="action-card">
            <div class="ac-icon"><i class="bi bi-chat-quote text-dark"></i></div>
            <div>
              <p class="fw-semibold mb-0" style="font-size:0.88rem;">Manage Reviews</p>
              <p class="text-muted mb-0" style="font-size:0.76rem;">Approve or delete client reviews</p>
            </div>
          </a>
        </div>

        <!-- Logout -->
        <div class="col-12 col-sm-6 col-md-4">
          <a href="../includes/logout.php" class="action-card danger">
            <div class="ac-icon" style="background:#fff1f2;"><i class="bi bi-box-arrow-right text-danger"></i></div>
            <div>
              <p class="fw-semibold mb-0 text-danger" style="font-size:0.88rem;">Logout</p>
              <p class="text-muted mb-0" style="font-size:0.76rem;">End current session</p>
            </div>
          </a>
        </div>

      </div>
    </div>

  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Debug: Log clicks on action cards
    document.querySelectorAll('.action-card').forEach(card => {
      card.addEventListener('click', function(e) {
        console.log('Action card clicked:', this.href);
      });
    });
  </script>
</body>
</html>
