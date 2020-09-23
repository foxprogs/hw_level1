<ul class='main-menu <?= $ul_class ?>'>
    <?php foreach ($main_menu as $el) :?>
        <li class = '<?= is_current_page($el['path']) ? 'active' : '' ?>'>
            <a href='<?= $el['path'] ?>'><?= cut_title($el['title']) ?></a>
        </li>
    <?php endforeach; ?>
</ul>
