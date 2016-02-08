<?php
defined('SYSPATH') or die('No direct script access.');
?>
<p style="font-size: 20px; color: #8d8d8d; margin: 10px 0 0;">Найдено Вакансий <?= count($vacancy->find_all()); ?></p>

<?php foreach ($vacancy->find_all() as $v): ?>
    <div class="content-group">
        <div class="header with-date">
            <div class="title"><?= HTML::anchor('vacancies/'.$v->id, $v->title); ?></div>
            <div class="date"><?= Date::full_date($v->date); ?></div>
        </div>

        <div class="body">
            <div class="text">
                <?= Text::limit_words(strip_tags($v->text), 100); ?>
                <?= HTML::anchor('vacancies/'.$v->id, 'Подробнее'); ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
