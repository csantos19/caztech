<?php
require_once '../includes/auth.php';
require_auth();
require_once '../includes/db_connect.php';

$error = '';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id = $id && $id > 0 ? (int) $id : 0;
$member = null;

if ($id > 0) {
    $stmt = $conn->prepare('SELECT * FROM team_members WHERE id = ? LIMIT 1');
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $member = $result ? $result->fetch_assoc() : null;
        $stmt->close();
    }
}

if (!$member) {
    header('Location: team.php');
    exit;
}

$profile_fields = [
    'profile_headline',
    'bio',
    'skills',
    'years_experience',
    'projects_completed',
    'clients_served',
    'satisfaction_rate',
    'github_url',
    'facebook_url',
    'linkedin_url',
    'profile_email',
    'resume_url',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [];
    foreach ($profile_fields as $field) {
        $values[$field] = trim((string) ($_POST[$field] ?? ''));
    }

    if ($values['profile_email'] !== '' && !filter_var($values['profile_email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid profile email address or leave it blank.';
    }

    if (!$error) {
        $stmt = $conn->prepare('UPDATE team_members SET profile_headline=?, bio=?, skills=?, years_experience=?, projects_completed=?, clients_served=?, satisfaction_rate=?, github_url=?, facebook_url=?, linkedin_url=?, profile_email=?, resume_url=? WHERE id=?');
        if ($stmt) {
            $stmt->bind_param(
                str_repeat('s', 12) . 'i',
                $values['profile_headline'],
                $values['bio'],
                $values['skills'],
                $values['years_experience'],
                $values['projects_completed'],
                $values['clients_served'],
                $values['satisfaction_rate'],
                $values['github_url'],
                $values['facebook_url'],
                $values['linkedin_url'],
                $values['profile_email'],
                $values['resume_url'],
                $id
            );

            if ($stmt->execute()) {
                header('Location: team.php?updated=1');
                exit;
            }
            $error = 'Database Error: ' . $stmt->error;
            $stmt->close();
        } else {
            $error = 'Database Error: ' . $conn->error;
        }
    }

    foreach ($values as $field => $value) {
        $member[$field] = $value;
    }
}

$page_title = 'Edit Profile - CAZTECH Admin';
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../includes/head.php'; ?>
<body class="bg-background min-h-screen font-sans antialiased flex flex-col items-center p-4 sm:p-8">
  <div class="w-full max-w-4xl flex items-center justify-between mb-8">
    <div class="flex items-center gap-3">
      <div class="h-8 w-16 bg-primary text-primary-foreground flex items-center justify-center font-bold text-sm rounded-md tracking-wider">CAZ</div>
      <div>
        <h1 class="text-xl font-bold tracking-tight">Edit Profile</h1>
        <p class="text-xs text-muted-foreground"><?php echo htmlspecialchars($member['name']); ?> · <?php echo htmlspecialchars($member['role']); ?></p>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <a href="../team_profile.php?id=<?php echo $id; ?>" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-primary hover:text-primary/80 transition-colors hidden sm:flex">Preview Profile ↗</a>
      <a href="team.php" class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">&larr; Back to Team</a>
    </div>
  </div>

  <main class="w-full max-w-4xl bg-card border rounded-3xl shadow-2xl overflow-hidden">
    <div class="p-6 sm:p-10">
      <div class="mb-8">
        <h2 class="text-2xl font-bold tracking-tight mb-2">Profile content</h2>
        <p class="text-muted-foreground">These optional fields power the public CodeCraft-inspired profile page. Leave fields blank when you do not want them displayed.</p>
      </div>

      <?php if ($error): ?>
        <div class="mb-6 rounded-md bg-destructive/15 border border-destructive/20 p-4 text-sm text-destructive" role="alert"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form action="" method="POST" class="admin-project-form space-y-8">
        <section class="space-y-6 rounded-2xl border bg-muted/30 p-6">
          <div>
            <h3 class="font-bold">Introduction</h3>
            <p class="mt-1 text-sm text-muted-foreground">Shown in the profile hero and About section.</p>
          </div>
          <div class="space-y-2">
            <label for="profile_headline" class="text-sm font-medium">Headline</label>
            <input type="text" id="profile_headline" name="profile_headline" maxlength="255" value="<?php echo htmlspecialchars($member['profile_headline'] ?? ''); ?>" placeholder="e.g. Building thoughtful digital experiences for real-world teams." class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          </div>
          <div class="space-y-2">
            <label for="bio" class="text-sm font-medium">About / Bio</label>
            <textarea id="bio" name="bio" rows="6" placeholder="Write a short professional introduction..." class="flex min-h-[140px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"><?php echo htmlspecialchars($member['bio'] ?? ''); ?></textarea>
          </div>
        </section>

        <section class="space-y-6 rounded-2xl border bg-muted/30 p-6">
          <div>
            <h3 class="font-bold">Skills and highlights</h3>
            <p class="mt-1 text-sm text-muted-foreground">Use one skill per line. Add an optional level with the format <code class="rounded bg-secondary px-1.5 py-0.5 text-xs">PHP|90</code>.</p>
          </div>
          <div class="space-y-2">
            <label for="skills" class="text-sm font-medium">Skills</label>
            <textarea id="skills" name="skills" rows="6" placeholder="PHP|90&#10;JavaScript|85&#10;MySQL|80" class="flex min-h-[140px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"><?php echo htmlspecialchars($member['skills'] ?? ''); ?></textarea>
          </div>
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <?php
            $stats = [
                'years_experience' => 'Experience label',
                'projects_completed' => 'Projects label',
                'clients_served' => 'Clients label',
                'satisfaction_rate' => 'Satisfaction label',
            ];
            foreach ($stats as $field => $label):
            ?>
              <div class="space-y-2">
                <label for="<?php echo $field; ?>" class="text-sm font-medium"><?php echo $label; ?></label>
                <input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" maxlength="50" value="<?php echo htmlspecialchars($member[$field] ?? ''); ?>" placeholder="Optional" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="space-y-6 rounded-2xl border bg-muted/30 p-6">
          <div>
            <h3 class="font-bold">Contact and social links</h3>
            <p class="mt-1 text-sm text-muted-foreground">Only completed links will appear publicly on this member's profile.</p>
          </div>
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <?php
            $links = [
                'github_url' => 'GitHub URL',
                'facebook_url' => 'Facebook URL',
                'linkedin_url' => 'LinkedIn URL',
                'profile_email' => 'Profile email',
                'resume_url' => 'CV / Resume URL',
            ];
            foreach ($links as $field => $label):
            ?>
              <div class="space-y-2 <?php echo $field === 'resume_url' ? 'sm:col-span-2' : ''; ?>">
                <label for="<?php echo $field; ?>" class="text-sm font-medium"><?php echo $label; ?></label>
                <input type="<?php echo $field === 'profile_email' ? 'email' : 'url'; ?>" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo htmlspecialchars($member[$field] ?? ''); ?>" placeholder="<?php echo $field === 'profile_email' ? 'name@example.com' : 'https://...'; ?>" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <div class="flex flex-col-reverse gap-3 border-t border-border pt-6 sm:flex-row sm:items-center sm:justify-between">
          <a href="team.php" class="text-sm font-medium text-muted-foreground hover:text-foreground">Cancel</a>
          <button type="submit" class="inline-flex h-11 items-center justify-center rounded-md bg-primary px-8 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">Save Profile</button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
