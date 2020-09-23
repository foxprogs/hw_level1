<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/template/header.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/helper/auth.php";

?>
<h1>Профиль</h1>
<?php if ($user = is_auth()) : ?>
    <p>Состояние: <?= $user['active'] ? 'Активный' : 'Отключен' ?></p>
    <p>ФИО: <?= $user['user_name'] ?></p>
    <p>Email: <?= $user['email'] ?></p>
    <p>Присылать уведомления: <?= $user['email_subscribe'] ? 'да' : 'нет' ?></p>
    <p>Телефон: <?= $user['tel'] ?></p>
    <p><b>Группы</b></p>
    <?php foreach ($user['groups'] as $name => $description) :?>
        <p><?= $name ?>: <?= $description ?></p>
    <?php endforeach; ?>
<?php else : ?>
    У вас нет доступа к этой странице. Требуется сначала <a href="?login=yes">войти</a>
<?php endif; ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/template/footer.php";
