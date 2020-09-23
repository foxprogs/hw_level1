<?php

function format_sections(array $sections, int $level = 0)
{
    $format_sections = [];
    $next_level = $level + 1;
    foreach ($sections as $section) {
        $format_sections[$section['id']] = [
            'name' => str_repeat('- ', $level) . $section['name'],
            'color' => $section['color_id'],
        ];
        if (isset($section['children'])) {
            $format_sections += format_sections($section['children'], $next_level);
        }
    }
    return $format_sections;
}
