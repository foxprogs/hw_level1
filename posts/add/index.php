<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/template/header.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/auth.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/post.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/database.php";

?>

<h1>Написать сообщение</h1>
<?php if ($user = is_auth()) : ?>
    <?php if (!isset($user['groups']['writer'])) : ?>
        <p>Вы можете отправлять сообщения после прохождения модерации</p>
    <?php else : ?>
        <?php if (add_post($user['id'])) : ?>
            <p>Сообщение отправлено</p>
        <?php else :
            $users_to = get_users_to();
            $sections = get_sections();
            ?>
            <form action="" method="post">
                <label for="title">Заголовок</label><br>
                <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"> <br>
                <label for="text">Текст сообщения</label><br>
                <textarea name="text"><?= htmlspecialchars($_POST['text'] ?? '') ?></textarea> <br>
                <label for="user">Пользователь</label><br>
                <select name="user">
                    <?php if (count($users_to) > 0) : ?>
                        <?php foreach ($users_to as $user_to) :
                            if ($user['id'] == $user_to['id']) {
                                continue;
                            }?>
                            <option value="<?= $user_to['id'] ?>" <?= $user_to['id'] === ($_POST['user'] ?? 0) ? 'selected' : '' ?>>
                                <?= $user_to['user_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select><br>
                <label for="section">Раздел</label><br>
                <select name="section">
                    <?php foreach (format_sections($sections) as $id => $section) : ?>
                        <option value="<?= $id ?>" <?= $id == ($_POST['section'] ?? 0) ? 'selected' : '' ?>>
                            <?= $section['name'] . ', ' . $section['color'] ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>
                <input type="submit" value="Отправить" name="add_post">
            </form>
        <?php endif; ?>
    <?php endif; ?>
<?php else : ?>
    У вас нет доступа к этой странице. Требуется сначала <a href="/?login=yes">войти</a>
<?php endif; ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/template/footer.php";
