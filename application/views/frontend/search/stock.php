<?php
defined('SYSPATH') or die('No direct script access.');
$stocks_count = count($stock->find_all());
?>
<p style="font-size: 20px; color: #8d8d8d; margin: 10px 0 0;">Найдено Акций <?= $stocks_count; ?></p>

<?php foreach ($stock->find_all() as $s): ?>
    <div class="content-group">
        <div class="header with-date">
            <div class="title"><?= HTML::anchor('stocks/'.$s->id, 'Акция автосервиса '.$s->service->name); ?></div>
            <div class="date"><?= Date::full_date($s->date); ?></div>
        </div>
        <div class="body">
            <div class="text">
                <?= Text::limit_words(strip_tags($s->text), 100); ?>
                <?= HTML::anchor('stocks/'.$s->id, 'Подробнее'); ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
