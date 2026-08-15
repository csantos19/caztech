<?php
/**
 * Helpers shared by public team profiles and their admin editor.
 */

if (!function_exists('caztech_profile_parse_skills')) {
    /**
     * Parse profile skills stored as JSON or as newline/comma-separated text.
     * Accepted text format: Skill name|85 (the level is optional).
     *
     * @return array<int, array{name:string, level:int}>
     */
    function caztech_profile_parse_skills(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }

        $skills = [];
        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            foreach ($decoded as $item) {
                if (is_string($item)) {
                    $name = trim($item);
                    $level = 0;
                } elseif (is_array($item)) {
                    $name = trim((string) ($item['name'] ?? $item['skill'] ?? ''));
                    $level = (int) ($item['level'] ?? $item['percentage'] ?? 0);
                } else {
                    continue;
                }

                if ($name !== '') {
                    $skills[] = [
                        'name' => $name,
                        'level' => max(0, min(100, $level)),
                    ];
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
            $name = trim((string) ($parts[0] ?? ''));
            $level = isset($parts[1]) && is_numeric($parts[1]) ? (int) $parts[1] : 0;
            if ($name !== '') {
                $skills[] = [
                    'name' => $name,
                    'level' => max(0, min(100, $level)),
                ];
            }
        }

        return $skills;
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
