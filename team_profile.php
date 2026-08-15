<?php
require_once 'includes/db_connect.php';
require_once 'includes/team_profile_helpers.php';

function caztech_profile_escape($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

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
    http_response_code(404);
    $page_title = 'Team Profile Not Found | CAZTech Solutions';
    ?>
    <!DOCTYPE html>
    <html lang="en" class="scroll-smooth">
    <?php include 'includes/head.php'; ?>
    <body class="min-h-screen bg-background font-sans antialiased text-foreground">
      <?php include 'includes/navbar.php'; ?>
      <main class="flex min-h-[60vh] items-center justify-center px-4 py-20">
        <div class="w-full max-w-xl rounded-3xl border bg-card p-8 text-center shadow-xl sm:p-12">
          <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-primary/10 text-2xl font-black text-primary">404</div>
          <h1 class="mt-6 text-3xl font-bold tracking-tight">Profile not found</h1>
          <p class="mx-auto mt-3 max-w-md leading-relaxed text-muted-foreground">That team profile may have been removed or the link is no longer available.</p>
          <a href="index.php#team" class="mt-8 inline-flex h-11 items-center justify-center rounded-md bg-primary px-7 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">Back to Team</a>
        </div>
      </main>
      <?php include 'includes/footer.php'; ?>
    </body>
    </html>
    <?php
    exit;
}

$name = trim((string) ($member['name'] ?? 'CAZTech Team Member'));
$role = trim((string) ($member['role'] ?? 'Team Member')) ?: 'CAZTech Team Member';
$headline = trim((string) ($member['profile_headline'] ?? '')) ?: 'Building practical digital solutions with CAZTech.';
$bio = trim((string) ($member['bio'] ?? '')) ?: 'A dedicated CAZTech team member contributing to thoughtful websites, modern systems, and digital experiences for real-world teams.';
$photo = caztech_profile_safe_asset_url((string) ($member['image_path'] ?? ''));
$skills = caztech_profile_parse_skills((string) ($member['skills'] ?? ''));
$first_name = preg_split('/\s+/', $name)[0] ?? $name;

$profile_email = trim((string) ($member['profile_email'] ?? ''));
$profile_email = filter_var($profile_email, FILTER_VALIDATE_EMAIL) ? $profile_email : 'caztechsolutions.works@gmail.com';

$socials = [
    ['label' => 'GitHub', 'url' => caztech_profile_safe_link((string) ($member['github_url'] ?? ''))],
    ['label' => 'Facebook', 'url' => caztech_profile_safe_link((string) ($member['facebook_url'] ?? ''))],
    ['label' => 'LinkedIn', 'url' => caztech_profile_safe_link((string) ($member['linkedin_url'] ?? ''))],
];
$socials = array_values(array_filter($socials, static fn(array $social): bool => $social['url'] !== ''));

$projects = [];
$project_result = $conn->query('SELECT id, title, category, description, icon_image, project_url FROM projects ORDER BY created_at DESC LIMIT 6');
if ($project_result) {
    while ($project = $project_result->fetch_assoc()) {
        $projects[] = $project;
    }
}

$page_title = $name . ' | CAZTech Solutions';
$page_description = $name . ' — ' . $role . ' at CAZTech Solutions.';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<?php include 'includes/head.php'; ?>
<body class="min-h-screen bg-background font-sans antialiased text-foreground">
  <?php include 'includes/navbar.php'; ?>

  <main>
    <style>
      .profile-grid-lines {
        background-image: linear-gradient(rgba(148, 163, 184, .08) 1px, transparent 1px), linear-gradient(90deg, rgba(148, 163, 184, .08) 1px, transparent 1px);
        background-size: 42px 42px;
      }
      .dark .profile-grid-lines {
        background-image: linear-gradient(rgba(148, 163, 184, .08) 1px, transparent 1px), linear-gradient(90deg, rgba(148, 163, 184, .08) 1px, transparent 1px);
      }
      .profile-orbit {
        box-shadow: 0 0 0 1px hsl(var(--primary) / .18), 0 0 80px hsl(var(--primary) / .18);
      }
      .profile-code-card {
        background: linear-gradient(145deg, hsl(var(--card) / .96), hsl(var(--secondary) / .86));
      }
    </style>

    <!-- Profile hero -->
    <section class="relative isolate overflow-hidden border-b py-16 sm:py-20 lg:py-24">
      <div class="profile-grid-lines pointer-events-none absolute inset-0 opacity-60"></div>
      <div class="pointer-events-none absolute -left-32 top-20 h-80 w-80 rounded-full bg-primary/10 blur-3xl"></div>
      <div class="pointer-events-none absolute -right-32 bottom-0 h-96 w-96 rounded-full bg-primary/10 blur-3xl"></div>

      <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-4 sm:px-6 lg:grid-cols-[1.08fr_.92fr] lg:gap-20 lg:px-8">
        <div class="order-2 space-y-8 lg:order-1">
          <a href="index.php#team" class="inline-flex items-center gap-2 text-sm font-medium text-muted-foreground transition-colors hover:text-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 18-6-6 6-6"/></svg>
            Back to the team
          </a>

          <div class="space-y-5">
            <div class="inline-flex items-center rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[0.68rem] font-bold uppercase tracking-[0.18em] text-primary">CAZTech team member</div>
            <h1 class="max-w-3xl text-5xl font-black tracking-tight sm:text-6xl lg:text-7xl">Hi, I'm <span class="text-primary"><?php echo caztech_profile_escape($first_name); ?></span></h1>
            <p class="max-w-2xl text-2xl font-bold leading-tight tracking-tight sm:text-3xl"><?php echo caztech_profile_escape($headline); ?></p>
            <p class="max-w-2xl leading-8 text-muted-foreground sm:text-lg"><?php echo caztech_profile_escape($role); ?> at CAZTech Solutions, helping turn practical ideas into useful digital experiences.</p>
          </div>

          <div class="flex flex-wrap items-center gap-3">
            <a href="index.php#contact" class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-primary px-7 text-sm font-semibold text-primary-foreground shadow-lg shadow-primary/20 transition-all hover:-translate-y-0.5 hover:bg-primary/90">Work with CAZTech <span aria-hidden="true">↗</span></a>
            <a href="#profile-projects" class="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-input bg-background/70 px-7 text-sm font-semibold transition-colors hover:bg-accent hover:text-accent-foreground">View work <span aria-hidden="true">↓</span></a>
            <?php if (!empty($member['resume_url']) && caztech_profile_safe_link((string) $member['resume_url']) !== ''): ?>
              <a href="<?php echo caztech_profile_escape(caztech_profile_safe_link((string) $member['resume_url'])); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-input bg-background/70 px-5 text-sm font-semibold transition-colors hover:bg-accent hover:text-accent-foreground">Open CV <span aria-hidden="true">↓</span></a>
            <?php endif; ?>
          </div>

          <div class="flex flex-wrap items-center gap-3 pt-1" aria-label="Profile links">
            <a href="mailto:<?php echo caztech_profile_escape($profile_email); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-muted-foreground transition-colors hover:text-primary"><span class="flex h-9 w-9 items-center justify-center rounded-full border bg-background/70 text-primary">@</span>Contact profile</a>
            <?php foreach ($socials as $social): ?>
              <a href="<?php echo caztech_profile_escape($social['url']); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex h-9 items-center rounded-full border bg-background/70 px-4 text-sm font-medium text-muted-foreground transition-colors hover:border-primary/40 hover:text-primary"><?php echo caztech_profile_escape($social['label']); ?></a>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="order-1 flex justify-center lg:order-2 lg:justify-end">
          <div class="relative w-full max-w-[25rem]">
            <div class="profile-orbit absolute left-1/2 top-1/2 h-[19rem] w-[19rem] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/20 blur-[1px] sm:h-[23rem] sm:w-[23rem]"></div>
            <div class="absolute -right-3 top-5 h-20 w-20 rounded-2xl border border-primary/20 bg-primary/10 blur-sm"></div>
            <div class="relative mx-auto aspect-[4/5] overflow-hidden rounded-[2rem] border border-border/80 bg-card/80 p-3 shadow-2xl shadow-primary/10 backdrop-blur sm:p-4">
              <div class="relative flex h-full items-center justify-center overflow-hidden rounded-[1.5rem] bg-gradient-to-br from-primary/25 via-background to-secondary">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_20%,hsl(var(--primary)/.28),transparent_52%)]"></div>
                <?php if ($photo !== ''): ?>
                  <img src="<?php echo caztech_profile_escape($photo); ?>" alt="<?php echo caztech_profile_escape($name); ?>" class="relative z-10 h-full w-full object-cover object-center">
                <?php else: ?>
                  <span class="relative z-10 text-8xl font-black tracking-tight text-primary/80"><?php echo caztech_profile_escape(strtoupper(substr($name, 0, 1))); ?></span>
                <?php endif; ?>
                <div class="absolute bottom-4 left-4 right-4 z-20 rounded-2xl border border-white/10 bg-slate-950/70 p-4 text-white shadow-xl backdrop-blur-md">
                  <p class="text-[0.65rem] font-bold uppercase tracking-[0.18em] text-primary-foreground/70">Currently contributing as</p>
                  <p class="mt-1 text-sm font-semibold"><?php echo caztech_profile_escape($role); ?></p>
                </div>
              </div>
            </div>
            <div class="profile-code-card absolute -bottom-6 -left-5 hidden w-44 rounded-2xl border border-border/80 p-4 shadow-2xl sm:block">
              <div class="mb-3 flex items-center justify-between text-[0.62rem] text-muted-foreground"><span>&lt;/&gt; profile</span><span class="h-2 w-2 rounded-full bg-emerald-400"></span></div>
              <div class="space-y-1 font-mono text-[0.62rem] leading-5 text-muted-foreground"><p><span class="text-primary">role:</span> <?php echo caztech_profile_escape($role); ?></p><p><span class="text-primary">team:</span> CAZTech</p><p><span class="text-primary">status:</span> available</p></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Non-invented profile highlights -->
    <section class="border-b bg-accent/20 py-8 sm:py-10">
      <div class="mx-auto grid max-w-7xl grid-cols-2 gap-3 px-4 sm:grid-cols-4 sm:gap-5 sm:px-6 lg:px-8">
        <?php
        $highlights = [
            ['01', 'Experience', trim((string) ($member['years_experience'] ?? '')) ?: 'CAZTech member'],
            ['02', 'Projects', trim((string) ($member['projects_completed'] ?? '')) ?: 'Selected work'],
            ['03', 'Clients', trim((string) ($member['clients_served'] ?? '')) ?: 'CAZTech team'],
            ['04', 'Satisfaction', trim((string) ($member['satisfaction_rate'] ?? '')) ?: 'Quality focused'],
        ];
        foreach ($highlights as $highlight):
        ?>
          <div class="rounded-2xl border bg-card/70 p-4 shadow-sm sm:p-5">
            <div class="flex items-center justify-between gap-2"><span class="text-xs font-black text-primary"><?php echo caztech_profile_escape($highlight[0]); ?></span><span class="h-1.5 w-1.5 rounded-full bg-primary"></span></div>
            <p class="mt-4 text-xs font-semibold uppercase tracking-[0.14em] text-muted-foreground"><?php echo caztech_profile_escape($highlight[1]); ?></p>
            <p class="mt-1 truncate text-sm font-bold sm:text-base" title="<?php echo caztech_profile_escape($highlight[2]); ?>"><?php echo caztech_profile_escape($highlight[2]); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- About and skills -->
    <section class="py-16 sm:py-20 lg:py-24">
      <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:gap-20 lg:px-8">
        <div class="space-y-6">
          <div class="inline-flex items-center rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[0.68rem] font-bold uppercase tracking-[0.18em] text-primary">About this profile</div>
          <h2 class="text-3xl font-black tracking-tight sm:text-4xl">Thoughtful work.<br><span class="text-primary">Useful outcomes.</span></h2>
          <p class="whitespace-pre-line leading-8 text-muted-foreground"><?php echo caztech_profile_escape($bio); ?></p>
          <div class="flex flex-wrap gap-2 pt-2">
            <span class="rounded-full border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground">CAZTech Solutions</span>
            <span class="rounded-full border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground"><?php echo caztech_profile_escape($role); ?></span>
          </div>
        </div>

        <div class="rounded-3xl border bg-card/70 p-6 shadow-xl shadow-primary/5 sm:p-8">
          <div class="flex items-end justify-between gap-4 border-b pb-5">
            <div><p class="text-xs font-bold uppercase tracking-[0.18em] text-primary">Core strengths</p><h2 class="mt-2 text-2xl font-bold tracking-tight">Skills and technologies</h2></div>
            <span class="hidden text-3xl font-black text-foreground/10 sm:block">STACK</span>
          </div>
          <?php if ($skills): ?>
            <div class="mt-7 grid gap-5 sm:grid-cols-2">
              <?php foreach ($skills as $index => $skill):
                  $skill_name = caztech_profile_escape($skill['name']);
                  $skill_level = (int) $skill['level'];
                  $skill_color = ['bg-primary', 'bg-violet-500 dark:bg-violet-400', 'bg-cyan-500 dark:bg-cyan-400'][$index % 3];
              ?>
                <div class="space-y-2">
                  <div class="flex items-center justify-between gap-3 text-sm font-semibold"><span><?php echo $skill_name; ?></span><?php if ($skill_level > 0): ?><span class="text-xs text-muted-foreground"><?php echo $skill_level; ?>%</span><?php endif; ?></div>
                  <?php if ($skill_level > 0): ?>
                    <div class="h-2 overflow-hidden rounded-full bg-secondary"><div class="h-full rounded-full <?php echo $skill_color; ?>" style="width:<?php echo $skill_level; ?>%" role="progressbar" aria-valuenow="<?php echo $skill_level; ?>" aria-valuemin="0" aria-valuemax="100" aria-label="<?php echo $skill_name; ?> proficiency"></div></div>
                  <?php else: ?>
                    <div class="h-2 rounded-full bg-primary/20"></div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="mt-7 rounded-2xl border border-dashed bg-background/60 p-6">
              <div class="flex flex-wrap gap-2">
                <span class="rounded-full bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary"><?php echo caztech_profile_escape($role); ?></span>
                <span class="rounded-full bg-secondary px-3 py-1.5 text-xs font-semibold text-secondary-foreground">Digital solutions</span>
                <span class="rounded-full bg-secondary px-3 py-1.5 text-xs font-semibold text-secondary-foreground">Team collaboration</span>
              </div>
              <p class="mt-4 text-sm leading-6 text-muted-foreground">Detailed skills can be added by the CAZTech admin from the profile editor.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Selected work -->
    <section id="profile-projects" class="border-y bg-accent/20 py-16 sm:py-20 lg:py-24">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
          <div><p class="text-xs font-bold uppercase tracking-[0.18em] text-primary">Featured projects</p><h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">Selected CAZTech work</h2><p class="mt-3 max-w-2xl leading-7 text-muted-foreground">A look at digital experiences built by the wider CAZTech team.</p></div>
          <a href="index.php#projects" class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:gap-3 transition-all">View all projects <span aria-hidden="true">→</span></a>
        </div>

        <?php if ($projects): ?>
          <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($projects as $index => $project):
                $project_title = trim((string) ($project['title'] ?? 'Untitled project')) ?: 'Untitled project';
                $project_category = trim((string) ($project['category'] ?? 'Project')) ?: 'Project';
                $project_description = trim((string) ($project['description'] ?? '')) ?: 'A tailored digital solution built by CAZTech.';
                $project_image = caztech_profile_safe_asset_url((string) ($project['icon_image'] ?? ''));
                $project_link = caztech_profile_safe_link((string) ($project['project_url'] ?? ''));
            ?>
              <article class="group overflow-hidden rounded-3xl border bg-card shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-2xl hover:shadow-primary/10">
                <div class="relative flex h-44 items-center justify-center overflow-hidden bg-gradient-to-br from-primary/20 via-background to-secondary p-6">
                  <span class="absolute left-5 top-4 text-xs font-black text-foreground/40"><?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?></span>
                  <?php if ($project_image !== ''): ?>
                    <img src="<?php echo caztech_profile_escape($project_image); ?>" alt="<?php echo caztech_profile_escape($project_title); ?> preview" class="h-full w-full object-contain transition-transform duration-500 group-hover:scale-105">
                  <?php else: ?>
                    <span class="text-6xl font-black tracking-tighter text-primary/70">&lt;/&gt;</span>
                  <?php endif; ?>
                </div>
                <div class="flex min-h-[14rem] flex-col p-6">
                  <span class="w-fit rounded-full bg-primary/10 px-3 py-1 text-[0.65rem] font-bold uppercase tracking-[0.14em] text-primary"><?php echo caztech_profile_escape($project_category); ?></span>
                  <h3 class="mt-4 text-xl font-bold tracking-tight"><?php echo caztech_profile_escape($project_title); ?></h3>
                  <p class="mt-3 text-sm leading-6 text-muted-foreground"><?php echo caztech_profile_escape($project_description); ?></p>
                  <div class="mt-auto border-t pt-5">
                    <?php if ($project_link !== ''): ?>
                      <a href="<?php echo caztech_profile_escape($project_link); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition-all hover:gap-3">View live project <span aria-hidden="true">↗</span></a>
                    <?php else: ?>
                      <a href="index.php#contact" class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition-all hover:gap-3">Discuss a similar project <span aria-hidden="true">→</span></a>
                    <?php endif; ?>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="rounded-3xl border border-dashed bg-card/60 px-6 py-14 text-center"><h3 class="text-xl font-bold">Projects are on the way</h3><p class="mx-auto mt-2 max-w-md text-sm leading-6 text-muted-foreground">The team portfolio will appear here as projects are added.</p></div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-16 sm:py-20 lg:py-24">
      <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[2rem] border bg-card p-8 shadow-2xl shadow-primary/10 sm:p-12 lg:p-16">
          <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-primary/15 blur-3xl"></div>
          <div class="relative grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
            <div><p class="text-xs font-bold uppercase tracking-[0.18em] text-primary">Let's work together</p><h2 class="mt-3 max-w-2xl text-3xl font-black tracking-tight sm:text-4xl">Have a project in mind?</h2><p class="mt-4 max-w-2xl leading-7 text-muted-foreground">Tell CAZTech what you are building and our team will help shape the next step.</p></div>
            <a href="mailto:<?php echo caztech_profile_escape($profile_email); ?>" class="inline-flex h-12 items-center justify-center gap-2 rounded-md bg-primary px-7 text-sm font-semibold text-primary-foreground shadow-lg shadow-primary/20 transition-all hover:-translate-y-0.5 hover:bg-primary/90">Get in touch <span aria-hidden="true">↗</span></a>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
