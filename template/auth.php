<?php
$is_login = is_login();
?>
<td class="right-collum-index">
    <div class="project-folders-menu">
        <ul class="project-folders-v">
            <?php if (!$is_login) :?>
                <li class="<?= (isset($_GET['login']) && $_GET['login'] == 'yes') ? 'project-folders-v-active' : '' ?>">
                    <a href="/?login=yes">Авторизация</a>
                </li>
                <li><a href="#">Регистрация</a></li>
                <li><a href="#">Забыли пароль?</a></li>
            <?php else :?>
                <li class="<?= is_current_page('/profile.php') ? 'project-folders-v-active' : '' ?>">
                    <a href="/profile.php">Профиль</a>
                </li>
                <li class="<?= is_current_page('/posts/') ? 'project-folders-v-active' : '' ?>">
                    <a href="/posts/">Сообщения</a>
                </li>
                <li><a href="/?logout=yes">Выйти</a></li>
            <?php endif;?>
        </ul>
        <div class="clearfix"></div>
    </div>
    <?php
    // выводим сообщения только если отправлены какие-то данные с формы
    if (!empty($_POST['auth'])) {
        include $_SERVER['DOCUMENT_ROOT'] . "/template/" . ($is_login ? 'success' : 'error') . ".php";
    }
    ?>
    <?php if (show_auth_form($is_login)) :?>
        <div class="index-auth">
            <form method="POST">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="iat">
                            <label for="login_id">Ваш e-mail:</label>
                            <?php if (!empty($_COOKIE['login']) && empty($_GET['remove_login'])) :?>
                                <input id="login_id" type="hidden" name="login" value="<?= htmlspecialchars($_COOKIE['login']) ?>">
                                <p><?= htmlspecialchars($_COOKIE['login']) ?> <a href="?login=yes&remove_login=yes">Не вы?</a></p>
                            <?php else :?>
                                <input id="login_id" type="text" size="30" name="login" value="<?= htmlspecialchars($_POST['login'] ?? $_COOKIE['login'] ?? '') ?>">
                            <?php endif;?>
                        </td>
                    </tr>
                    <tr>
                        <td class="iat">
                            <label for="password_id">Ваш пароль:</label>
                            <input  id="password_id" size="30" name="password"
                                    value='<?= htmlspecialchars($_POST['password'] ?? '') ?>' type="password">
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="auth" value="Войти"></td>
                    </tr>
                </table>
            </form>
        </div>
    <?php endif;?>
</td>
