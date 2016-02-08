<?php
defined('SYSPATH') or die('No direct script access.');
?>


<div class="search-result <?= $url; ?>">
    <h1 class="title">Найдено <?= ($url == 'services') ? 'автосервисов' : 'магазинов автозапчастей'; ?> <?= count($companies); ?></h1>
    <?php foreach ($companies as $c): ?>
        <div class="content-group">
            <div class="header">
                <div class="title"><?= HTML::anchor($url.'/'.$c->id, $c->name); ?></div>
                <?= $company->get_words($c); ?>
            </div>
            <div class="body">
                <div class="address"><?= $c->get_address(); ?></div>
                <noindex>
                    <div class="text">
                        <?= Text::limit_words(strip_tags($c->about), 40); ?>
                        <?= HTML::anchor($url.'/'.$c->id, 'Подробнее'); ?>
                    </div>
                </noindex>
            </div>
        </div>
    <?php endforeach; ?>
</div>

