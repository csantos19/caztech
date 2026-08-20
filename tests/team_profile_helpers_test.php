<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/team_profile_helpers.php';

function expect_true(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function expect_same($expected, $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message . "\nExpected: " . var_export($expected, true) . "\nActual: " . var_export($actual, true));
    }
}

$skills = caztech_profile_parse_skills(
    "[Languages] PHP|95\n[Databases] MySQL / MariaDB:80\nLegacy JavaScript|130\nPlain CSS3|-10\n[Languages] PHP|70"
);

expect_same(4, count($skills), 'Duplicate normalized skills should collapse to one entry.');
expect_same('Languages', $skills[0]['category'], 'Bracket-prefixed category should be parsed.');
expect_same('PHP', $skills[0]['name'], 'Category prefix should be removed from the display name.');
expect_same(95, $skills[0]['level'], 'Duplicate entries should retain the strongest score.');
expect_same(80, $skills[1]['level'], 'Colon-separated scores should be parsed.');
expect_same(100, $skills[2]['level'], 'Scores above 100 should be clamped.');
expect_same(0, $skills[3]['level'], 'Negative scores should be clamped to zero.');
expect_same('General', $skills[2]['category'], 'Legacy ungrouped entries should use General.');

$json_skills = caztech_profile_parse_skills('[{"name":"Laravel","category":"Backend","percentage":78},"[Languages] JavaScript|90"]');
expect_same(2, count($json_skills), 'JSON skill arrays should remain supported.');
expect_same('Backend', $json_skills[0]['category'], 'JSON category should be preserved.');
expect_same(78, $json_skills[0]['level'], 'JSON percentage should be supported.');
expect_same('Languages', $json_skills[1]['category'], 'JSON strings should support category prefixes.');

$serialized = caztech_profile_serialize_skills([
    ['category' => 'Deployment', 'name' => 'Hostinger', 'level' => 130],
    ['category' => 'Languages', 'name' => 'PHP', 'level' => 95],
    ['category' => 'Languages', 'name' => 'PHP', 'level' => 80],
    ['category' => 'Tools', 'name' => "VS\nCode", 'level' => -5],
]);
expect_same("[Deployment] Hostinger|100" . PHP_EOL . "[Languages] PHP|95" . PHP_EOL . "[Tools] VS Code|0", $serialized, 'Serializer should canonicalize rows and clamp/deduplicate them.');
$serialized_round_trip = caztech_profile_parse_skills($serialized);
expect_same(3, count($serialized_round_trip), 'Serialized skill rows should parse back into normalized records.');
expect_same('Hostinger', $serialized_round_trip[0]['name'], 'Serialized deployment skill should round-trip.');

$featured = caztech_profile_featured_skills([
    ['category' => 'Tooling', 'name' => 'Composer', 'level' => 80],
    ['category' => 'Languages', 'name' => 'PHP', 'level' => 95],
    ['category' => 'Backend', 'name' => 'Laravel', 'level' => 85],
], 2);
expect_same('PHP', $featured[0]['name'], 'Featured skills should prioritize verified languages.');
expect_same('Laravel', $featured[1]['name'], 'Featured skills should then prioritize backend skills.');
expect_same('bg-blue-500 dark:bg-blue-400', caztech_profile_skill_color_class('Languages'), 'Known categories should receive stable color classes.');
expect_same('bg-primary', caztech_profile_skill_color_class('Unknown'), 'Unknown categories should use the safe fallback color.');

fwrite(STDOUT, "team_profile_helpers_test.php: all assertions passed\n");
