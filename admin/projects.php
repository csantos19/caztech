<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db_connect.php';

require_auth('../login.php');

$result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

$page_title = 'Manage Projects | CAZTech Admin';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<?php include __DIR__ . '/../includes/head.php'; ?>
<body class="min-h-screen bg-background font-sans antialiased text-foreground">

  <header class="sticky top-0 z-50 w-full border-b border-border/40 bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <a href="index.php" class="flex items-center gap-2 group">
        <img src="../image/Logo1.png" alt="CAZTech" class="h-10 w-auto object-contain mix-blend-multiply dark:filter dark:invert dark:brightness-200">
        <span class="text-xs text-muted-foreground font-medium">Admin Panel</span>
      </a>
      <div class="flex items-center gap-3">
        <a href="index.php" class="text-sm font-medium hover:text-primary transition-colors">Dashboard</a>
        <a href="../includes/logout.php" class="text-sm font-medium text-destructive hover:opacity-80 transition-opacity">Logout</a>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold tracking-tight">Projects</h1>
        <p class="text-muted-foreground mt-1">Manage the projects displayed on your homepage.</p>
      </div>
      <a href="add_project.php" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
        Add Project
      </a>
    </div>

    <div class="rounded-xl border bg-card text-card-foreground shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-muted/50 border-b">
            <tr>
              <th class="px-6 py-4 text-left font-medium">Icon</th>
              <th class="px-6 py-4 text-left font-medium">Title</th>
              <th class="px-6 py-4 text-left font-medium">Category</th>
              <th class="px-6 py-4 text-left font-medium">Created</th>
              <th class="px-6 py-4 text-right font-medium">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php if (empty($projects)): ?>
              <tr>
                <td colspan="5" class="px-6 py-10 text-center text-muted-foreground">No projects found.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($projects as $project): ?>
                <tr class="hover:bg-muted/30 transition-colors">
                  <td class="px-6 py-4">
                    <div class="h-12 w-12 <?php echo htmlspecialchars($project['bg_class'] ?? 'bg-gray-50'); ?> rounded-lg flex items-center justify-center overflow-hidden">
                      <?php if (!empty($project['icon_image'])): ?>
                        <img src="../<?php echo htmlspecialchars($project['icon_image']); ?>" alt="" class="h-8 w-8 object-contain">
                      <?php elseif (!empty($project['icon_svg'])): ?>
                        <?php echo $project['icon_svg']; ?>
                      <?php else: ?>
                        <svg class="h-6 w-6 text-muted-foreground" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td class="px-6 py-4 font-medium"><?php echo htmlspecialchars($project['title']); ?></td>
                  <td class="px-6 py-4"><span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold"><?php echo htmlspecialchars($project['category']); ?></span></td>
                  <td class="px-6 py-4 text-muted-foreground"><?php echo date('M d, Y', strtotime($project['created_at'])); ?></td>
                  <td class="px-6 py-4 text-right space-x-2">
                    <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="text-primary hover:underline font-medium">Edit</a>
                    <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="text-destructive hover:underline font-medium" onclick="return confirm('Are you sure you want to delete this project?')">Delete</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script src="../assets/js/main.js"></script>
</body>
</html>
