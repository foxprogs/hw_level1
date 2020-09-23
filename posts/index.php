<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/template/header.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/auth.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/post.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/database.php";

?>

<h1>Сообщения</h1>
<?php if ($user = is_auth()) : ?>
    <?php if (isset($user['groups']['writer'])) :
        $posts = get_posts($user['id']); ?>
        <a href="/posts/add/">Написать сообщение</a>
        <h3>Непрочитанные сообщения</h3>
        <?php if (count($posts['unreaded']) == 0) : ?>
            <p>Нет сообщений</p>
        <?php else : ?>
            <ul>
                <?php foreach ($posts['unreaded'] as $post) : ?>
                    <li><a href="/posts/read.php?post_id=<?= $post['id'] ?>">
                        <?= $post['title'] ?> /<?= $post['section_name']?>/
                    </a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <h3>Прочитанные сообщения</h3>
        <?php if (count($posts['readed']) == 0) : ?>
            <p>Нет сообщений</p>
        <?php else : ?>
            <ul>
                <?php foreach ($posts['readed'] as $post) : ?>
                    <li><a href="/posts/read.php?post_id=<?= $post['id'] ?>">
                        <?= $post['title'] ?> /<?= $post['section_name']?>/
                    </a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php else : ?>
        <p>Вы можете отправлять сообщения после прохождения модерации</p>
    <?php endif; ?>
<?php else : ?>
    <p>У вас нет доступа к этой странице. Требуется сначала <a href="/?login=yes">войти</a></p>
<?php endif; ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/template/footer.php";
