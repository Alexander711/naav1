<?php
defined('SYSPATH') or die('No direct script access.');
$urls = array(
    'admin/feedback' => 'Обычные запросы',
    'admin/feedback/index/2' => 'Запросы на рекламу'
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