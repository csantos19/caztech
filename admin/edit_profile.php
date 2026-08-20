<?php
require_once '../includes/auth.php';
require_auth();
require_once '../includes/db_connect.php';
require_once '../includes/team_profile_helpers.php';

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

    $values['skills'] = caztech_profile_serialize_skills(
        caztech_profile_parse_skills($values['skills'])
    );

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

$editable_skills = caztech_profile_parse_skills((string) ($member['skills'] ?? ''));
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
            <p class="mt-1 text-sm text-muted-foreground">Use one skill per line using <code class="rounded bg-secondary px-1.5 py-0.5 text-xs">[Category] Skill|score</code>. Scores are evidence-based verified project usage values from 0 to 100, not subjective proficiency claims. Example: <code class="rounded bg-secondary px-1.5 py-0.5 text-xs">[Languages] PHP|95</code>.</p>
          </div>
          <div class="space-y-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
              <div>
                <label class="text-sm font-medium">Editable skill rows</label>
                <p class="mt-1 text-xs leading-5 text-muted-foreground">Edit the category, skill name, or score. Empty names are ignored and scores are clamped to 0–100 when saved.</p>
              </div>
              <button type="button" id="add-skill-row" class="inline-flex h-9 items-center justify-center rounded-md border border-primary/30 bg-primary/10 px-3 text-xs font-semibold text-primary transition-colors hover:bg-primary/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">+ Add skill</button>
            </div>

            <div id="skill-editor-scroll" class="max-h-[34rem] overflow-y-auto rounded-xl border bg-background/30 p-3" tabindex="0" aria-label="Scrollable editable skill rows">
              <nav id="skill-category-spy" class="sticky top-0 z-10 mb-3 flex gap-2 overflow-x-auto rounded-lg border bg-card/95 p-2 backdrop-blur" aria-label="Skill category scrollspy"></nav>
              <div id="skill-editor-rows" class="space-y-3">
              <?php foreach ($editable_skills as $skill):
                  $skill_category_value = htmlspecialchars((string) $skill['category'], ENT_QUOTES, 'UTF-8');
                  $skill_name_value = htmlspecialchars((string) $skill['name'], ENT_QUOTES, 'UTF-8');
                  $skill_level_value = max(0, min(100, (int) $skill['level']));
              ?>
                <div data-skill-row class="grid grid-cols-1 gap-3 rounded-xl border bg-background/70 p-4 sm:grid-cols-[1fr_1.6fr_7rem_auto] sm:items-end">
                  <div class="space-y-1.5">
                    <label class="text-xs font-medium text-muted-foreground">Category</label>
                    <input type="text" data-skill-category maxlength="80" value="<?php echo $skill_category_value; ?>" placeholder="Languages" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                  </div>
                  <div class="space-y-1.5">
                    <label class="text-xs font-medium text-muted-foreground">Skill name</label>
                    <input type="text" data-skill-name maxlength="150" value="<?php echo $skill_name_value; ?>" placeholder="PHP" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                  </div>
                  <div class="space-y-1.5">
                    <label class="text-xs font-medium text-muted-foreground">Score</label>
                    <input type="number" data-skill-level min="0" max="100" step="1" value="<?php echo $skill_level_value; ?>" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                  </div>
                  <button type="button" data-remove-skill class="inline-flex h-10 items-center justify-center rounded-md border border-destructive/30 px-3 text-xs font-semibold text-destructive transition-colors hover:bg-destructive/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">Delete</button>
                </div>
              <?php endforeach; ?>
              </div>
            </div>

            <div class="space-y-2 rounded-xl border border-dashed bg-background/50 p-4">
              <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <label for="skills-bulk" class="text-xs font-semibold uppercase tracking-[0.12em] text-muted-foreground">Bulk text loader</label>
                <button type="button" id="load-skill-text" class="text-xs font-semibold text-primary hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">Load text into rows</button>
              </div>
              <textarea id="skills-bulk" rows="4" placeholder="[Languages] PHP|96&#10;[Deployment] Hostinger|72" class="flex min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 font-mono text-xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"><?php echo htmlspecialchars((string) ($member['skills'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
              <p class="text-xs leading-5 text-muted-foreground">Use one row per line in <code class="rounded bg-secondary px-1 py-0.5">[Category] Skill|score</code> format, then click “Load text into rows”.</p>
            </div>
            <textarea id="skills" name="skills" class="hidden" aria-hidden="true"><?php echo htmlspecialchars((string) ($member['skills'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
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
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const rows = document.getElementById('skill-editor-rows');
      const skillScroll = document.getElementById('skill-editor-scroll');
      const skillSpy = document.getElementById('skill-category-spy');
      const rawSkills = document.getElementById('skills');
      const bulkSkills = document.getElementById('skills-bulk');
      const addSkillButton = document.getElementById('add-skill-row');
      const loadSkillButton = document.getElementById('load-skill-text');
      const form = document.querySelector('form.admin-project-form');
      let activeSkillSpyCategory = '';
      if (!rows || !rawSkills || !bulkSkills || !addSkillButton) return;

      const clampScore = value => {
        const parsed = Number.parseInt(value, 10);
        return Number.isFinite(parsed) ? Math.max(0, Math.min(100, parsed)) : 0;
      };

      const createSkillRow = (category = 'General', name = '', level = 0) => {
        const row = document.createElement('div');
        row.setAttribute('data-skill-row', '');
        row.className = 'grid grid-cols-1 gap-3 rounded-xl border bg-background/70 p-4 sm:grid-cols-[1fr_1.6fr_7rem_auto] sm:items-end';
        row.innerHTML = `
          <div class="space-y-1.5">
            <label class="text-xs font-medium text-muted-foreground">Category</label>
            <input type="text" data-skill-category maxlength="80" placeholder="Languages" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          </div>
          <div class="space-y-1.5">
            <label class="text-xs font-medium text-muted-foreground">Skill name</label>
            <input type="text" data-skill-name maxlength="150" placeholder="PHP" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          </div>
          <div class="space-y-1.5">
            <label class="text-xs font-medium text-muted-foreground">Score</label>
            <input type="number" data-skill-level min="0" max="100" step="1" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          </div>
          <button type="button" data-remove-skill class="inline-flex h-10 items-center justify-center rounded-md border border-destructive/30 px-3 text-xs font-semibold text-destructive transition-colors hover:bg-destructive/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">Delete</button>`;
        row.querySelector('[data-skill-category]').value = category;
        row.querySelector('[data-skill-name]').value = name;
        row.querySelector('[data-skill-level]').value = clampScore(level);
        return row;
      };

      const getSkillRows = () => [...rows.querySelectorAll('[data-skill-row]')];
      const getSkillCategory = row => (row.querySelector('[data-skill-category]')?.value || 'General').trim() || 'General';

      const setActiveSkillSpyCategory = category => {
        activeSkillSpyCategory = category || '';
        skillSpy?.querySelectorAll('[data-spy-category]').forEach(button => {
          const isActive = button.dataset.spyCategory === activeSkillSpyCategory;
          button.classList.toggle('is-active', isActive);
          if (isActive) button.setAttribute('aria-current', 'true');
          else button.removeAttribute('aria-current');
        });
      };

      const refreshSkillSpy = () => {
        if (!skillSpy) return;
        const firstRows = new Map();
        getSkillRows().forEach(row => {
          const category = getSkillCategory(row);
          if (!firstRows.has(category)) firstRows.set(category, row);
        });

        skillSpy.replaceChildren();
        firstRows.forEach((targetRow, category) => {
          const button = document.createElement('button');
          button.type = 'button';
          button.className = 'skill-spy-link';
          button.dataset.spyCategory = category;
          button.textContent = category;
          button.addEventListener('click', () => {
            if (!skillScroll) return;
            const scrollRect = skillScroll.getBoundingClientRect();
            const targetTop = targetRow.getBoundingClientRect().top - scrollRect.top + skillScroll.scrollTop - 12;
            skillScroll.scrollTo({ top: Math.max(0, targetTop), behavior: 'smooth' });
            setActiveSkillSpyCategory(category);
          });
          skillSpy.appendChild(button);
        });

        const firstCategory = firstRows.keys().next().value || '';
        setActiveSkillSpyCategory(firstRows.has(activeSkillSpyCategory) ? activeSkillSpyCategory : firstCategory);
      };

      const updateSkillSpy = () => {
        if (!skillScroll) return;
        const scrollRect = skillScroll.getBoundingClientRect();
        const threshold = skillScroll.scrollTop + 48;
        let currentCategory = '';
        getSkillRows().forEach(row => {
          const rowTop = row.getBoundingClientRect().top - scrollRect.top + skillScroll.scrollTop;
          if (rowTop <= threshold) currentCategory = getSkillCategory(row);
        });
        if (currentCategory) setActiveSkillSpyCategory(currentCategory);
      };

      const syncRowsToField = () => {
        const lines = [...rows.querySelectorAll('[data-skill-row]')].map(row => {
          const category = (row.querySelector('[data-skill-category]')?.value || 'General')
            .replace(/[\[\]\r\n]/g, '')
            .trim() || 'General';
          const name = (row.querySelector('[data-skill-name]')?.value || '')
            .replace(/[\r\n]+/g, ' ')
            .trim();
          const level = clampScore(row.querySelector('[data-skill-level]')?.value || 0);
          return name ? `[${category}] ${name}|${level}` : '';
        }).filter(Boolean);
        rawSkills.value = lines.join('\n');
        refreshSkillSpy();
      };

      const loadBulkText = () => {
        rows.replaceChildren();
        const entries = bulkSkills.value.split(/[\r\n,]+/).map(entry => entry.trim()).filter(Boolean);
        entries.forEach(entry => {
          const parts = entry.split(/\s*[|:]\s*/);
          let name = (parts.shift() || '').trim();
          const level = clampScore(parts.join('|'));
          let category = 'General';
          const categoryMatch = name.match(/^\[([^\]]+)\]\s*(.*)$/);
          if (categoryMatch) {
            category = categoryMatch[1].trim() || 'General';
            name = categoryMatch[2].trim();
          }
          if (name) rows.appendChild(createSkillRow(category, name, level));
        });
        syncRowsToField();
      };

      addSkillButton.addEventListener('click', () => {
        const row = createSkillRow();
        rows.appendChild(row);
        row.querySelector('[data-skill-name]')?.focus();
        syncRowsToField();
      });

      rows.addEventListener('click', event => {
        const removeButton = event.target.closest('[data-remove-skill]');
        if (!removeButton) return;
        removeButton.closest('[data-skill-row]')?.remove();
        syncRowsToField();
      });

      rows.addEventListener('input', syncRowsToField);
      loadSkillButton?.addEventListener('click', loadBulkText);
      form?.addEventListener('submit', syncRowsToField);
      let scrollSpyFrame = 0;
      skillScroll?.addEventListener('scroll', () => {
        if (scrollSpyFrame) return;
        scrollSpyFrame = window.requestAnimationFrame(() => {
          scrollSpyFrame = 0;
          updateSkillSpy();
        });
      }, { passive: true });
      syncRowsToField();
      updateSkillSpy();
    });
  </script>
</body>
</html>
