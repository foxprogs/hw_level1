<?php

function is_login()
{
    static $is_login = null;
    if ($is_login !== null) {
        return $is_login;
    }
    // запустим сессию с указанными параметрами по умолчанию
    session_start([
        'name' => SESSION_NAME,
        'gc_maxlifetime' => SESSION_LIFETIME,
        'cookie_lifetime' => SESSION_COOKIE_LIFETIME,
    ]);

    // если пользователь залогинился то поставить true, иначе взять значение с сессии
    $is_login = check_login() ? true : isset($_SESSION['is_login']);

    extend_cookie_session();
    
    // Сокращенная запись $is_login = check_logout() ? false : $is_login;
    // если пользователь вышел, то сделать флаг равным false
    if (check_logout()) {
        $is_login = false;
    }

    return $is_login;
}

function check_login()
{
    if (empty($_POST['auth'])) {
        return false;
    }
    require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/database.php";

    $user = get_user($_POST['login']);
    // если ключ не существует или не совпадает пароль то вернем false
    if ($user === null || $user['active'] == 0 || !password_verify($_POST['password'], $user['password'])) {
        return false;
    }
    // запишем в куку логин пользователя
    setcookie('login', $user['email'], time() + COOKIE_LIFETIME, '/');
    // в сессию поставим отметку, что пользователь авторизован
    $_SESSION['is_login'] = true;
    $_SESSION['email'] = $user['email'];
    // метка времени жизни сессии
    setcookie('exp', time() + SESSION_LIFETIME, time() + COOKIE_LIFETIME, '/');
    return true;
}

// продлим сессию и куку, если они существуют
function extend_cookie_session()
{
    if (!empty($_SESSION['is_login'])) {
        session_regenerate_id();
        setcookie('exp', time() + SESSION_LIFETIME, time() + COOKIE_LIFETIME, '/');
    }
    if (!empty($_COOKIE['login'])) {
        setcookie('login', $_POST['login'] ?? $_COOKIE['login'], time() + COOKIE_LIFETIME, '/');
    }
}

function check_logout()
{
    // если нажата кнопка ВЫЙТИ
    if (!empty($_GET['logout']) && $_GET['logout'] == 'yes') {
        // удалить сессию и куки
        unset($_SESSION['is_login'], $_SESSION['email']);
        setcookie('login', '', 1, '/');
        setcookie('exp', '', 2525075303, '/');
        setcookie(SESSION_NAME, '', 1, '/');
        return true;
    }
    return false;
}

/*
Показать форму авторизации надо только если:
 - мы еще не авторизованы И (пришел параметр $_GET['login'] ИЛИ вышло время жизни сессии)
*/
function show_auth_form($is_login)
{
    if (!$is_login && (isset($_GET['login']) || (!empty($_COOKIE['exp']) && time() > $_COOKIE['exp']))) {
        setcookie('exp', '', 1, '/');
        return true;
    }
    return false;
}

function is_auth()
{
    if (!is_login() || empty($_SESSION['email'])) {
        return false;
    }
    require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/database.php";

    return get_user($_SESSION['email']);
}
