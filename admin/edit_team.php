<?php
require_once '../includes/auth.php';
require_auth();
require_once '../includes/db_connect.php';

$error = '';
$member = null;

if (!isset($_GET['id'])) {
    header("Location: team.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM team_members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: team.php");
    exit;
}

$member = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $image_path = $member['image_path'];

    if (empty($name) || empty($role)) {
        $error = 'Name and Role are required.';
    }

    if (!$error && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/team/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_info = pathinfo($_FILES['photo']['name']);
        $ext = strtolower($file_info['extension']);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

        if (!in_array($ext, $allowed_exts)) {
            $error = 'Invalid image format. Only JPG, PNG, GIF, SVG, and WebP are allowed.';
        } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
            $error = 'Image file size must be less than 5 MB.';
        } else {
            $new_filename = uniqid('team_') . '.' . $ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                // Delete old image if it exists
                if (!empty($member['image_path']) && file_exists('../' . $member['image_path'])) {
                    unlink('../' . $member['image_path']);
                }
                $image_path = 'uploads/team/' . $new_filename;
            } else {
                $error = 'Failed to upload photo. Please check folder permissions.';
            }
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE team_members SET name=?, role=?, image_path=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $role, $image_path, $id);
        if ($stmt->execute()) {
            header("Location: team.php?updated=1");
            exit;
        } else {
            $error = "Database Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
$page_title = "Edit Team Member - CAZTECH Admin";
include '../includes/head.php';
?>
<body class="bg-background min-h-screen font-sans antialiased flex flex-col items-center p-4 sm:p-8">

  <div class="w-full max-w-2xl flex items-center justify-between mb-8">
    <div class="flex items-center gap-3">
      <div class="h-8 w-16 bg-primary text-primary-foreground flex items-center justify-center font-bold text-sm rounded-md tracking-wider">
        CAZ
      </div>
      <h1 class="text-xl font-bold tracking-tight">Admin Edit</h1>
    </div>
    <a href="team.php" class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors hidden sm:flex space-x-2">
      <span>&larr; Back to Team</span>
    </a>
  </div>

  <main class="w-full max-w-2xl bg-card border rounded-3xl shadow-2xl overflow-hidden">
    <div class="p-6 sm:p-10">
      <div class="mb-8">
        <h2 class="text-2xl font-bold tracking-tight mb-2">Edit Team Member</h2>
        <p class="text-muted-foreground">Update details for <?php echo htmlspecialchars($member['name']); ?>.</p>
      </div>

      <?php if ($error): ?>
        <div class="mb-6 rounded-md bg-destructive/15 border border-destructive/20 p-4 text-sm text-destructive" role="alert">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST" enctype="multipart/form-data" class="space-y-8">
        <div class="space-y-6 bg-muted/30 p-6 rounded-2xl border">
          <!-- Name -->
          <div class="space-y-2">
            <label for="name" class="text-sm font-medium">Name <span class="text-destructive">*</span></label>
            <input type="text" id="name" name="name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" value="<?php echo htmlspecialchars($member['name']); ?>" required>
          </div>

          <!-- Role -->
          <div class="space-y-2">
            <label for="role" class="text-sm font-medium">Role <span class="text-destructive">*</span></label>
            <input type="text" id="role" name="role" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" value="<?php echo htmlspecialchars($member['role']); ?>" required>
          </div>

          <!-- Photo Upload -->
          <div class="space-y-2">
            <label class="text-sm font-medium flex justify-between">
              Profile Photo
              <?php if (!empty($member['image_path'])): ?>
                <span class="text-xs text-muted-foreground font-normal">Replacing will delete old photo</span>
              <?php endif; ?>
            </label>
            <div id="drop-zone" class="relative group mt-2 flex justify-center rounded-lg border border-dashed border-input bg-background/50 px-6 py-10 transition-colors hover:border-primary/50 hover:bg-accent/50 cursor-pointer overflow-hidden">
                <div class="text-center <?php echo !empty($member['image_path']) ? 'opacity-0' : ''; ?>" id="upload-prompt">
                    <svg class="mx-auto h-10 w-10 text-muted-foreground group-hover:text-primary transition-colors mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div class="mt-4 flex text-sm leading-6 text-muted-foreground justify-center">
                        <label for="photo" class="relative cursor-pointer rounded-md font-semibold text-primary focus-within:outline-none hover:text-primary/80">
                            <span>Upload a new photo</span>
                            <input id="photo" name="photo" type="file" class="sr-only" accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">
                        </label>
                    </div>
                </div>
                
                <div id="image-preview-container" class="absolute inset-0 flex items-center justify-center bg-background/80 backdrop-blur-sm transition-opacity <?php echo empty($member['image_path']) ? 'opacity-0 pointer-events-none' : ''; ?>">
                    <img id="image-preview" src="<?php echo !empty($member['image_path']) ? '../' . htmlspecialchars($member['image_path']) : ''; ?>" alt="Preview" class="max-h-full max-w-full object-contain p-4 drop-shadow-md rounded-2xl">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 bg-background/50 backdrop-blur-sm transition-opacity">
                         <span class="text-sm font-semibold">Click to change</span>
                    </div>
                </div>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between border-t border-border pt-6 mt-8">
          <a href="team.php" class="text-sm font-medium text-muted-foreground hover:text-foreground">Cancel</a>
          <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-8">
            Update Member
          </button>
        </div>
      </form>
    </div>
  </main>

  <script>
    const fileInput = document.getElementById('photo');
    const dropZone = document.getElementById('drop-zone');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');
    const prompt = document.getElementById('upload-prompt');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('opacity-0', 'pointer-events-none');
                prompt.classList.add('opacity-0');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    dropZone.addEventListener('click', () => {
        if(previewContainer.classList.contains('opacity-0') === false) {
           fileInput.click();
        }
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-primary/50', 'bg-accent/50');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary/50', 'bg-accent/50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary/50', 'bg-accent/50');
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });
  </script>
</body>
</html>
