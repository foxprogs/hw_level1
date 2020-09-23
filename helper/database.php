<?php
/*

test1@mail.ru 1111
test2@mail.ru 2222
test3@mail.ru 3333
test4@mail.ru 4444
test5@mail.ru 5555

*/
function connect()
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/config/constants.php";
    static $connect = null;
    $connect = $connect ?? mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die(mysqli_connect_error());
    return $connect;
}

/**
* Возвращает null или массив с данными по пользователю + группы, в которых он состоит
*/
function get_user(string $email)
{
    $email = mysqli_escape_string(connect(), $email);
    $query = "SELECT * FROM users WHERE email = '$email' ";
    $result = mysqli_query(connect(), $query);
    if (!$result) {
        die(mysqli_error(connect()));
    }
    if (mysqli_num_rows($result) == 0) {
         return null;
    }
    $user = mysqli_fetch_assoc($result);

    $user['groups'] = [];
    $query = "
        SELECT g.* 
        FROM group_user as gu 
        LEFT JOIN groups as g ON gu.group_id = g.id 
        WHERE user_id = " . $user['id'];
    $result = mysqli_query(connect(), $query);
    if (!$result) {
        die(mysqli_error(connect()));
    }
    while ($group = mysqli_fetch_assoc($result)) {
        $user['groups'][$group['name']] = $group['description'];
    }

    return $user;
}

function get_posts(int $user_id)
{
    $posts = ['readed' => [], 'unreaded' => []];
    $user_id = mysqli_escape_string(connect(), $user_id);
    $query = "
        SELECT p.id, p.title, s.name as section_name, p.readed_at
        FROM posts as p INNER JOIN sections as s ON (p.section_id = s.id)
        WHERE p.to_user_id = $user_id
        ORDER BY p.readed_at DESC";
    $result = mysqli_query(connect(), $query);
    if (!$result) {
        die(mysqli_error(connect()));
    }
    while ($post = mysqli_fetch_assoc($result)) {
        $posts[$post['readed_at'] ? 'readed' : 'unreaded'][] = $post;
    }
    return $posts;
}

function get_post(int $post_id, int $to_user_id)
{
    $to_user_id = mysqli_escape_string(connect(), $to_user_id);
    $post_id = mysqli_escape_string(connect(), $post_id);
    $query = "SELECT p.title, p.text, p.created_at, p.readed_at, u.user_name, u.email 
        FROM posts as p INNER JOIN users as u ON (p.from_user_id = u.id)
        WHERE p.id = $post_id AND p.to_user_id = $to_user_id";
    $result = mysqli_query(connect(), $query);
    if (!$result) {
        die(mysqli_error(connect()));
    }
    if (mysqli_num_rows($result) == 0) {
         return null;
    }
    $post = mysqli_fetch_assoc($result);
    if (empty($post['readed_at'])) {
        check_read($post_id);
    }
    return $post;
}

function check_read(int $post_id)
{
    $post_id = mysqli_escape_string(connect(), $post_id);
    $date = date("Y-m-d H:i:s");
    $query = "
        UPDATE posts
        SET readed_at = '$date'
        WHERE id = $post_id";
    return mysqli_query(connect(), $query);
}

function add_post(int $from_user_id)
{
    if (empty($_POST['add_post'])) {
        return false;
    }
    if (empty($_POST['title'] || empty($_POST['text']) || empty($_POST['user']) || empty($_POST['section']))) {
        return false;
    }
    $title = mysqli_escape_string(connect(), $_POST['title']);
    $text = mysqli_escape_string(connect(), $_POST['text']);
    $to_user_id = (int) $_POST['user'];
    $section_id = (int) $_POST['section'];
    $from_user_id = (int) $from_user_id;
    $query = "
        INSERT INTO posts
        (`from_user_id`, `to_user_id`, `title`, `text`, `section_id`)
        VALUES ($from_user_id, $to_user_id, '$title', '$text', $section_id)";
    return mysqli_query(connect(), $query);
}

function get_users_to()
{
    $users = [];
    $query = "
        SELECT u.id, u.user_name
        FROM group_user as gu LEFT JOIN users as u ON (gu.user_id = u.id)
        WHERE gu.group_id = 2 AND u.active = 1";
    $result = mysqli_query(connect(), $query);
    if (!$result) {
        die(mysqli_error(connect()));
    }
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $users;
}

function get_sections()
{
    $sections = [];
    $query = "SELECT * FROM sections ORDER BY id DESC";
    // сортируем в обратном направлении, тогда мы точно уверены что родительская категория у нас уже создана
    $result = mysqli_query(connect(), $query);
    if (!$result) {
        die(mysqli_error(connect()));
    }
    while ($section = mysqli_fetch_assoc($result)) {
        $sections[$section['id']] = $section; // перепишем массив с id ввиде ключа
    }
    foreach ($sections as $k => &$section) { // используем ссылку, этим мы сможем вложенные категории присваивать
        if ($section['parent_id'] != 0) {
            $sections[$section['parent_id']]['children'][$section['id']] = $section;
        }
    }
    foreach ($sections as $key => $val) {
        if ($val['parent_id'] != 0) { // оставим только категории, в которых родителя нет
            unset($sections[$key]);
        }
    }
    return $sections;
}
