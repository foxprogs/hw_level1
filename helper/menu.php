<?php

function order_menu($a, $b)
{
    return $a['sort']<=>$b['sort'];
}

function order_menu_revert($a, $b)
{
    return $b['sort']<=>$a['sort'];
}

function create_menu(array $main_menu, string $sort = 'desc', string $ul_class = '')
{
    usort($main_menu, $sort == 'desc' ? "order_menu_revert" : "order_menu");
    
    include $_SERVER['DOCUMENT_ROOT'] . '/template/menu.php';
}
