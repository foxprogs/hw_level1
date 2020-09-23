<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/template/header.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/auth.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/post.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/database.php";

?>

<?php if ($user = is_auth()) : ?>
    <?php if (isset($user['groups']['writer'])) :
        if ($post = get_post((int) $_GET['post_id'], $user['id'])) : ?>
            <h2><?= $post['title'] ?></h2>
            <small><?= $post['created_at'] ?></small><br>
            <p><?= $post['user_name'] ?> (<?= $post['email'] ?>)</p>
            <p><?= $post['text'] ?></p>
        <?php else : ?>
            <p>Нет такого сообщения</p>
        <?php endif; ?>
    <?php else : ?>
        <p>Вы можете читать сообщения после прохождения модерации</p>
    <?php endif; ?>
<?php else : ?>
    <p>У вас нет доступа к этой странице. Требуется сначала <a href="/?login=yes">войти</a></p>
<?php endif; ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/template/footer.php";
