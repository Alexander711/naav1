<?php
defined('SYSPATH') or die('No direct script access.');
$urls = array(
    '/admin/users/edit_info/'.$user_id => 'Информация о пользователе',
    '/admin/users/edit_password/'.$user_id => 'Изменение пароля'
);
?>
<div class="clearfix">
    <ul class="nav nav-tabs">
    <?php foreach ($urls as $url => $text): ?>
        <?php
        $attr = ($url == $current_url) ? array('class' => 'active') : array();
        ?>
        <li <?= HTML::attributes($attr); ?>><?= HTML::anchor($url, $text); ?></li>
    <?php endforeach; ?>
    </ul>
</div>