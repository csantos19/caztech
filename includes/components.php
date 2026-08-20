<?php
/**
 * components.php
 * Reusable PHP rendering functions — no repeated HTML blocks needed.
 * Include this file once and call functions wherever needed.
 */

/**
 * Render N filled star SVGs (yellow).
 * @param int $count  Number of filled stars (max 5)
 */
function render_stars(int $count = 5): void {
    $star_path = 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z';
    $count = max(0, min(5, $count));
    echo '<div class="flex items-center gap-1 text-yellow-400">';
    for ($i = 0; $i < $count; $i++) {
        echo '<svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="' . $star_path . '"/></svg>';
    }
    echo '</div>';
}

/**
 * Render a single skill progress bar.
 * @param string $name      Skill label
 * @param int    $pct       Percentage (0-100)
 * @param int    $delay_ms  CSS transition delay in milliseconds
 */
function render_skill(string $name, int $pct, int $delay_ms = 0, string $color_class = 'bg-primary'): void {
    $pct = max(0, min(100, $pct));
    $delay_style = $delay_ms > 0 ? ' style="transition-delay:' . $delay_ms . 'ms"' : '';
    $safe_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safe_color_class = htmlspecialchars($color_class, ENT_QUOTES, 'UTF-8');
    echo '
    <div class="skill-progress-item group cursor-pointer overflow-hidden rounded-xl border border-transparent p-3 transition-all duration-300 ease-out hover:-translate-y-0.5 hover:border-primary/30 hover:bg-primary/5 hover:shadow-lg hover:shadow-primary/10 active:scale-[0.99] focus-within:border-primary/50 focus-within:bg-primary/10 focus-within:shadow-lg focus-within:shadow-primary/15">
      <div class="flex justify-between items-center text-sm font-medium">
        <span>' . $safe_name . '</span>
        <span class="text-muted-foreground">' . $pct . '%<span class="sr-only"> verified project usage</span></span>
      </div>
      <div class="h-2.5 w-full rounded-full bg-secondary/80 border border-border/60 overflow-hidden transition-colors duration-300 group-hover:bg-primary/10 group-focus-within:bg-primary/10 group-active:bg-primary/20" title="' . $pct . '% verified project usage">
        <div class="h-full rounded-full ' . $safe_color_class . ' skill-progress w-0 cursor-pointer transition-all duration-[1500ms] ease-out shadow-sm hover:brightness-110 hover:shadow-lg hover:shadow-primary/30 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 focus-visible:ring-offset-2 focus-visible:brightness-110 focus-visible:shadow-lg focus-visible:shadow-primary/30 active:scale-[0.995] active:brightness-125" data-width="' . $pct . '%" role="progressbar" tabindex="0" aria-valuenow="' . $pct . '" aria-valuemin="0" aria-valuemax="100" aria-label="' . $safe_name . ' verified project usage"' . $delay_style . '></div>
      </div>
    </div>';
}

/**
 * Render a testimonial card.
 * @param string $initials  1-4 chars displayed in the avatar circle
 * @param string $name      Client/company name
 * @param string $role      Role/type label (e.g. "Dental Partner")
 * @param string $text      Testimonial body text
 * @param int    $stars     Star rating (default 5)
 */
function render_testimonial(string $initials, string $name, string $role, string $text, int $stars = 5): void {
    echo '
    <div class="rounded-xl border bg-card text-card-foreground shadow-sm p-6 flex flex-col justify-between space-y-6 hover:shadow-md transition-shadow">
      <div class="space-y-4">';
        render_stars($stars);
        echo '
        <p class="text-sm text-card-foreground leading-relaxed">&ldquo;' . htmlspecialchars($text) . '&rdquo;</p>
      </div>
      <div class="flex items-center gap-3 pt-4 border-t">
        <div class="h-10 w-10 rounded-full bg-secondary flex items-center justify-center font-bold text-sm flex-shrink-0">' . htmlspecialchars($initials) . '</div>
        <div>
          <p class="text-sm font-semibold">' . htmlspecialchars($name) . '</p>
          <p class="text-xs text-muted-foreground">' . htmlspecialchars($role) . '</p>
        </div>
      </div>
    </div>';
}

/**
 * Render a team member card.
 * @param string $name       Member display name
 * @param string $role       Job role
 * @param string $image_path Optional path to uploaded photo
 */
function render_team_member(string $name, string $role, string $image_path = '', int $member_id = 0): void {
    $safe_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safe_role = htmlspecialchars($role, ENT_QUOTES, 'UTF-8');
    $initial = htmlspecialchars(substr($name, 0, 1), ENT_QUOTES, 'UTF-8');
    $profile_url = $member_id > 0 ? 'team_profile.php?id=' . $member_id : '';

    if (empty($image_path)) {
        $avatar_content = '<span class="text-2xl font-bold">' . $initial . '</span>';
    } else {
        $avatar_content = '<img src="' . htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8') . '" alt="' . $safe_name . '" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">';
    }

    $card_start = $profile_url
        ? '<a href="' . htmlspecialchars($profile_url, ENT_QUOTES, 'UTF-8') . '" aria-label="View profile of ' . $safe_name . '" class="group relative flex h-full flex-col rounded-2xl border bg-card/50 p-6 text-center text-card-foreground backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-primary/30 hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">'
        : '<div class="group relative flex h-full flex-col rounded-2xl border bg-card/50 p-6 text-center text-card-foreground backdrop-blur-sm">';
    $card_end = $profile_url ? '</a>' : '</div>';

    echo $card_start;
    echo '<div class="mx-auto mb-5 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border-2 border-transparent bg-secondary font-bold transition-colors group-hover:border-primary/40">' . $avatar_content . '</div>';
    echo '<h3 class="whitespace-nowrap text-base font-bold tracking-tight sm:text-[1.05rem]">' . $safe_name . '</h3>';
    echo '<div class="mt-2 text-xs text-muted-foreground">' . $safe_role . '</div>';

    if ($profile_url) {
        echo '<span class="mt-5 inline-flex items-center justify-center gap-2 text-xs font-semibold text-primary transition-all group-hover:gap-3">View profile <span aria-hidden="true">→</span></span>';
    }

    echo $card_end;
}

/**
 * Render a project skeleton card (for loading state).
 * @param string $extra_classes  Additional Tailwind classes (e.g. "hidden md:flex")
 */
function render_project_skeleton(string $extra_classes = ''): void {
    echo '
    <div class="rounded-xl border bg-card shadow-sm p-4 space-y-4 flex flex-col h-[320px] animate-pulse ' . $extra_classes . '">
      <div class="w-full h-40 bg-muted rounded-lg"></div>
      <div class="h-5 w-1/2 bg-muted rounded"></div>
      <div class="h-4 w-full bg-muted rounded"></div>
      <div class="h-4 w-3/4 bg-muted rounded"></div>
    </div>';
}

/**
 * Render a filter pill button for the projects section.
 * @param string $label   Button label
 * @param bool   $active  Whether this pill is active/selected
 */
function render_filter_pill(string $label, bool $active = false): void {
    $base = 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 h-9 px-4 py-2';
    $style = $active
        ? 'bg-primary text-primary-foreground hover:bg-primary/90'
        : 'border border-input bg-background hover:bg-accent hover:text-accent-foreground';
    echo '<button class="' . $base . ' ' . $style . '" data-filter="' . htmlspecialchars(strtolower($label)) . '">' . htmlspecialchars($label) . '</button>';
}
?>
