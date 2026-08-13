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
function render_skill(string $name, int $pct, int $delay_ms = 0): void {
    $delay_style = $delay_ms > 0 ? ' style="transition-delay:' . $delay_ms . 'ms"' : '';
    echo '
    <div class="space-y-2">
      <div class="flex justify-between items-center text-sm font-medium">
        <span>' . htmlspecialchars($name) . '</span>
        <span class="text-muted-foreground">' . $pct . '%</span>
      </div>
      <div class="h-2 w-full rounded-full bg-secondary overflow-hidden">
        <div class="h-full rounded-full bg-primary skill-progress w-0 transition-all duration-[1500ms] ease-out"
             data-width="' . $pct . '%"' . $delay_style . '></div>
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
function render_team_member(string $name, string $role, string $image_path = ''): void {
    $initial = htmlspecialchars(substr($name, 0, 1));
    
    if (empty($image_path)) {
        $avatar_content = $initial;
        $div_start = '<div class="h-24 w-24 mx-auto rounded-full bg-secondary overflow-hidden mb-5 border-2 border-transparent group-hover:border-primary/40 transition-colors flex items-center justify-center font-bold text-2xl">';
        $div_end = '</div>';
    } else {
        $avatar_content = '<img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($name) . '" class="h-full w-full object-cover">';
        $div_start = '<button type="button" data-img="' . htmlspecialchars($image_path) . '" data-title="' . htmlspecialchars($name) . '" data-category="' . htmlspecialchars($role) . '" class="team-photo-btn h-24 w-24 mx-auto block rounded-full bg-secondary overflow-hidden mb-5 border-2 border-transparent group-hover:border-primary/40 transition-transform hover:scale-110 cursor-zoom-in flex items-center justify-center font-bold text-2xl focus:outline-none focus:ring-2 focus:ring-primary/70">';
        $div_end = '</button>';
    }

    echo '
    <div class="group p-6 rounded-2xl border bg-card/50 text-card-foreground text-center transition-all duration-300 hover:shadow-xl hover:border-primary/20 backdrop-blur-sm">
      ' . $div_start . $avatar_content . $div_end . '
      <h3 class="font-bold text-lg">' . htmlspecialchars($name) . '</h3>
      <div class="mt-2 text-xs text-muted-foreground">' . htmlspecialchars($role) . '</div>
    </div>';
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
