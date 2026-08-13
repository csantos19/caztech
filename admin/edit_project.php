<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db_connect.php';

require_auth('../login.php');

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();

if (!$project) {
    header("Location: projects.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $project_url = trim($_POST['project_url'] ?? '');
    $bg_class    = $_POST['bg_class'] ?? $project['bg_class'];
    $icon_image  = $project['icon_image']; // keep existing if no new upload

    if (empty($title) || empty($category)) {
        $error = 'Title and Category are required.';
    } else {
        if (!empty($_FILES['icon_image']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
            $mime    = $_FILES['icon_image']['type'];

            if (!in_array($mime, $allowed)) {
                $error = 'Invalid file type. Allowed: JPG, PNG, GIF, SVG, WebP.';
            } elseif ($_FILES['icon_image']['size'] > 2 * 1024 * 1024) {
                $error = 'File too large. Max 2 MB.';
            } else {
                $ext      = pathinfo($_FILES['icon_image']['name'], PATHINFO_EXTENSION);
                $filename = 'proj_' . uniqid() . '.' . $ext;
                $dest     = __DIR__ . '/../uploads/projects/' . $filename;
                if (move_uploaded_file($_FILES['icon_image']['tmp_name'], $dest)) {
                    // Delete old image if it exists
                    if ($project['icon_image'] && file_exists(__DIR__ . '/../' . $project['icon_image'])) {
                        unlink(__DIR__ . '/../' . $project['icon_image']);
                    }
                    $icon_image = 'uploads/projects/' . $filename;
                } else {
                    $error = 'Failed to save the uploaded image.';
                }
            }
        }

        if (!$error) {
            $stmt = $conn->prepare("UPDATE projects SET title=?, category=?, description=?, icon_image=?, bg_class=?, project_url=? WHERE id=?");
            $stmt->bind_param("ssssssi", $title, $category, $description, $icon_image, $bg_class, $project_url, $id);
            if ($stmt->execute()) {
                header("Location: projects.php?updated=1");
                exit;
            } else {
                $error = 'Error updating: ' . $conn->error;
            }
        }
    }
}

// Map bg_class for dropdown selection
$bg_options = [
    'bg-blue-50 dark:bg-blue-900/30'   => 'Blue',
    'bg-purple-50 dark:bg-purple-900/30' => 'Purple',
    'bg-green-50 dark:bg-green-900/30'  => 'Green',
    'bg-orange-50 dark:bg-orange-900/30' => 'Orange',
    'bg-pink-50 dark:bg-pink-900/30'    => 'Pink',
    'bg-yellow-50 dark:bg-yellow-900/30' => 'Yellow',
    'bg-gray-50 dark:bg-gray-800/30'    => 'Gray',
];

$page_title = 'Edit Project | CAZTech Admin';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<?php include __DIR__ . '/../includes/head.php'; ?>
<body class="min-h-screen bg-background font-sans antialiased text-foreground">

  <header class="sticky top-0 z-50 w-full border-b border-border/40 bg-background/95 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <a href="index.php" class="flex items-center gap-2">
        <img src="../image/CAZTECH.png" alt="CAZTech" class="h-10 w-auto object-contain">
        <span class="text-xs text-muted-foreground font-medium">Admin Panel</span>
      </a>
      <a href="projects.php" class="text-sm font-medium hover:text-primary transition-colors">← Back to Projects</a>
    </div>
  </header>

  <main class="max-w-3xl mx-auto px-4 py-10">
    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold tracking-tight">Edit Project</h1>
        <p class="text-muted-foreground mt-1">Updating "<?php echo htmlspecialchars($project['title']); ?>"</p>
      </div>

      <?php if ($error): ?>
        <div class="p-4 rounded-md bg-destructive/10 text-destructive text-sm border border-destructive/20">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="space-y-6 rounded-xl border bg-card p-8 shadow-sm">
        <div class="grid grid-cols-1 gap-6">

          <div class="space-y-2">
            <label for="title" class="text-sm font-medium">Project Title <span class="text-destructive">*</span></label>
            <input type="text" id="title" name="title" class="flex h-10 w-full rounded-md border border-input bg-white dark:bg-slate-800 px-3 py-2 text-sm text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring dark:border-slate-600" value="<?php echo htmlspecialchars($project['title']); ?>" required>
          </div>

          <div class="space-y-2">
            <label for="category" class="text-sm font-medium">Category <span class="text-destructive">*</span></label>
            <input type="text" id="category" name="category" class="flex h-10 w-full rounded-md border border-input bg-white dark:bg-slate-800 px-3 py-2 text-sm text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring dark:border-slate-600" value="<?php echo htmlspecialchars($project['category'] ?? ''); ?>" required>
          </div>

          <div class="space-y-2">
            <label for="project_url" class="text-sm font-medium">Project URL (Optional)</label>
            <input type="url" id="project_url" name="project_url" class="flex h-10 w-full rounded-md border border-input bg-white dark:bg-slate-800 px-3 py-2 text-sm text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring dark:border-slate-600" value="<?php echo htmlspecialchars($project['project_url'] ?? ''); ?>" placeholder="https://example.com">
          </div>

          <!-- Logo upload / preview -->
          <div class="space-y-2">
            <label class="text-sm font-medium">Client / Project Logo</label>

            <?php if ($project['icon_image']): ?>
              <div class="flex items-center gap-4 p-4 rounded-lg border bg-muted/30">
                <img src="../<?php echo htmlspecialchars($project['icon_image']); ?>" alt="Current logo" class="h-14 w-14 object-contain rounded-lg shadow-sm bg-white p-1">
                <div>
                  <p class="text-sm font-medium">Current logo</p>
                  <p class="text-xs text-muted-foreground">Upload a new file below to replace it.</p>
                </div>
              </div>
            <?php endif; ?>

            <div id="logo-drop-zone" class="relative flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-input p-8 cursor-pointer bg-background hover:bg-muted/50 transition-colors">
              <img id="logo-preview" src="#" alt="Preview" class="hidden h-20 w-20 object-contain rounded-lg shadow">
              <div id="logo-placeholder" class="flex flex-col items-center gap-2 text-center">
                <div class="h-12 w-12 rounded-full bg-secondary flex items-center justify-center">
                  <svg class="h-6 w-6 text-muted-foreground" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
                <p class="text-sm font-medium">Click to upload a new logo</p>
                <p class="text-xs text-muted-foreground">PNG, JPG, SVG, WebP · Max 2 MB</p>
              </div>
              <input type="file" id="icon_image" name="icon_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
            </div>
          </div>

          <!-- Badge background dropdown -->
          <div class="space-y-2">
            <label for="bg_class" class="text-sm font-medium">Logo Badge Background</label>
            <select id="bg_class" name="bg_class" class="flex h-10 w-full rounded-md border border-input bg-white dark:bg-slate-800 px-3 py-2 text-sm text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring dark:border-slate-600">
              <?php foreach ($bg_options as $val => $label): ?>
                <option value="<?php echo $val; ?>" <?php echo $project['bg_class'] === $val ? 'selected' : ''; ?>><?php echo $label; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="space-y-2">
            <label for="description" class="text-sm font-medium">Description</label>
            <textarea id="description" name="description" class="flex min-h-[120px] w-full rounded-md border border-input bg-white dark:bg-slate-800 px-3 py-2 text-sm text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring dark:border-slate-600"><?php echo htmlspecialchars($project['description']); ?></textarea>
          </div>

        </div>

        <div class="flex items-center gap-4 pt-4 border-t">
          <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary px-8 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
            Update Project
          </button>
          <a href="projects.php" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Cancel</a>
        </div>
      </form>
    </div>
  </main>

  <script>
    document.getElementById('icon_image').addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const preview = document.getElementById('logo-preview');
        const placeholder = document.getElementById('logo-placeholder');
        preview.src = e.target.result;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
      };
      reader.readAsDataURL(file);
    });
  </script>

  <script src="../assets/js/main.js"></script>
</body>
</html>
