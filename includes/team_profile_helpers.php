<?php
/**
 * Helpers shared by public team profiles and their admin editor.
 */

if (!function_exists('caztech_profile_parse_skills')) {
    /**
     * Parse profile skills stored as JSON or newline/comma-separated text.
     * Accepted text formats include Skill|85 and [Category] Skill|85.
     *
     * The parser keeps legacy records working while returning normalized
     * category/name/level values for the public profile and homepage.
     *
     * @return array<int, array{category:string, name:string, level:int}>
     */
    function caztech_profile_parse_skills(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }

        $skills = [];
        $seen = [];
        $add_skill = static function (string $raw_name, $raw_level = 0, string $raw_category = '') use (&$skills, &$seen): void {
            $name = trim($raw_name);
            $category = trim($raw_category);
            if ($name === '') {
                return;
            }

            if (preg_match('/^\[([^\]]+)\]\s*(.+)$/', $name, $matches)) {
                if ($category === '') {
                    $category = trim((string) $matches[1]);
                }
                $name = trim((string) $matches[2]);
            }

            if ($name === '') {
                return;
            }

            $category = $category !== '' ? preg_replace('/\s+/', ' ', $category) : 'General';
            $category = trim((string) $category) ?: 'General';
            $level = is_numeric($raw_level) ? (int) $raw_level : 0;
            $level = max(0, min(100, $level));
            $key = strtolower($category . "\0" . $name);

            if (isset($seen[$key])) {
                $existing_index = $seen[$key];
                $skills[$existing_index]['level'] = max($skills[$existing_index]['level'], $level);
                return;
            }

            $seen[$key] = count($skills);
            $skills[] = [
                'category' => $category,
                'name' => $name,
                'level' => $level,
            ];
        };

        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            if (isset($decoded['name']) || isset($decoded['skill'])) {
                $decoded = [$decoded];
            }

            foreach ($decoded as $item) {
                if (is_string($item)) {
                    $parts = preg_split('/\s*[|:]\s*/', trim($item), 2);
                    $add_skill((string) ($parts[0] ?? ''), $parts[1] ?? 0);
                } elseif (is_array($item)) {
                    $add_skill(
                        (string) ($item['name'] ?? $item['skill'] ?? ''),
                        $item['level'] ?? $item['percentage'] ?? 0,
                        (string) ($item['category'] ?? $item['group'] ?? '')
                    );
                }
            }

            return $skills;
        }

        foreach (preg_split('/[\r\n,]+/', $raw) ?: [] as $entry) {
            $entry = trim($entry);
            if ($entry === '') {
                continue;
            }

            $parts = preg_split('/\s*[|:]\s*/', $entry, 2);
            $add_skill((string) ($parts[0] ?? ''), $parts[1] ?? 0);
        }

        return $skills;
    }
}

if (!function_exists('caztech_profile_serialize_skills')) {
    /**
     * Serialize normalized skills into the canonical editable text format.
     *
     * @param array<int, array{category?:string, name?:string, level?:int|string}> $skills
     */
    function caztech_profile_serialize_skills(array $skills): string
    {
        $lines = [];
        $seen = [];

        foreach ($skills as $skill) {
            $category = trim((string) ($skill['category'] ?? 'General'));
            $category = preg_replace('/\s+/', ' ', str_replace(['[', ']'], '', $category));
            $category = trim((string) $category) ?: 'General';

            $name = trim((string) ($skill['name'] ?? ''));
            $name = trim((string) preg_replace('/[\r\n]+/', ' ', $name));
            if ($name === '') {
                continue;
            }

            $level = is_numeric($skill['level'] ?? null) ? (int) $skill['level'] : 0;
            $level = max(0, min(100, $level));
            $key = strtolower($category . "\0" . $name);
            $line = '[' . $category . '] ' . $name . '|' . $level;

            if (isset($seen[$key])) {
                $existing_index = $seen[$key];
                $existing_level = (int) preg_replace('/^.*\|/', '', $lines[$existing_index]);
                if ($level > $existing_level) {
                    $lines[$existing_index] = $line;
                }
                continue;
            }

            $seen[$key] = count($lines);
            $lines[] = $line;
        }

        return implode(PHP_EOL, $lines);
    }
}

if (!function_exists('caztech_profile_skill_color_class')) {
    /**
     * Return a fixed, safe Tailwind class for a normalized skill category.
     */
    function caztech_profile_skill_color_class(string $category): string
    {
        $colors = [
            'Languages' => 'bg-blue-500 dark:bg-blue-400',
            'Templates' => 'bg-fuchsia-500 dark:bg-fuchsia-400',
            'Databases' => 'bg-emerald-500 dark:bg-emerald-400',
            'Backend' => 'bg-violet-500 dark:bg-violet-400',
            'Frontend' => 'bg-cyan-500 dark:bg-cyan-400',
            'Email' => 'bg-amber-500 dark:bg-amber-400',
            'Reports' => 'bg-orange-500 dark:bg-orange-400',
            'Tooling' => 'bg-indigo-500 dark:bg-indigo-400',
            'Deployment' => 'bg-rose-500 dark:bg-rose-400',
            'Tools' => 'bg-slate-500 dark:bg-slate-400',
            'Automation' => 'bg-lime-600 dark:bg-lime-400',
        ];

        return $colors[$category] ?? 'bg-primary';
    }
}

if (!function_exists('caztech_profile_featured_skills')) {
    /**
     * Keep the homepage compact while leaving the complete registry on the profile.
     * Skills retain their source order inside each category.
     *
     * @param array<int, array{category:string, name:string, level:int}> $skills
     * @return array<int, array{category:string, name:string, level:int}>
     */
    function caztech_profile_featured_skills(array $skills, int $limit = 16): array
    {
        $limit = max(1, $limit);
        $priority = [
            'Languages' => 10,
            'Templates' => 20,
            'Databases' => 30,
            'Backend' => 40,
            'Frontend' => 50,
            'Email' => 60,
            'Reports' => 70,
            'Tooling' => 80,
            'Deployment' => 90,
            'Tools' => 100,
            'Automation' => 110,
        ];

        $ranked = [];
        foreach ($skills as $index => $skill) {
            $ranked[] = [
                'priority' => $priority[$skill['category']] ?? 999,
                'index' => $index,
                'skill' => $skill,
            ];
        }

        usort($ranked, static function (array $left, array $right): int {
            return ($left['priority'] <=> $right['priority']) ?: ($left['index'] <=> $right['index']);
        });

        return array_map(
            static fn(array $ranked_skill): array => $ranked_skill['skill'],
            array_slice($ranked, 0, $limit)
        );
    }
}

if (!function_exists('caztech_profile_safe_asset_url')) {
    function caztech_profile_safe_asset_url(string $value): string
    {
        $value = trim($value);
        if ($value === '' || preg_match('/^(?:data|javascript|vbscript):/i', $value)) {
            return '';
        }

        return preg_match('/^[a-zA-Z0-9._\/-]+$/', $value) ? $value : '';
    }
}

if (!function_exists('caztech_profile_safe_link')) {
    function caztech_profile_safe_link(string $value): string
    {
        $value = trim($value);
        if ($value === '' || preg_match('/^(?:javascript|data|vbscript):/i', $value)) {
            return '';
        }

        return preg_match('/^(?:https?:\/\/|\/|\.\/|\.\.\/|#)/i', $value) ? $value : '';
    }
}
