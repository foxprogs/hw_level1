<?php

function get_page_title(array $main_menu)
{
    $header = 'Страница не найдена';
    foreach ($main_menu as $el) {
        if (is_current_page($el['path'])) {
            $header = $el['title'];
            break;
        }
    }
    return $header;
}

function is_current_page(string $path)
{
    return $path == parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

function cut_title($title)
{
    return mb_strlen($title) > 15 ? mb_substr($title, 0, 12) . '...' : $title;
}
