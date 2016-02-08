<?php
defined('SYSPATH') or die('No direct script access.');
if ($view_results)
{
    $service->reset(FALSE);
}

/*
$service->reset(FALSE);
$news_portal->reset(FALSE);
$news_service->reset(FALSE);
$review->reset(FALSE);
$stock->reset(FALSE);
$vacancy->reset(FALSE);
*/
$search_options = array(
    'all' => __('f_option_all'),
    'services' => __('f_option_services'),
    'news' => __('f_option_news'),
    'stotks' => __('f_option_stocks'),
    'vacancies' => __('f_option_vacancies'),
);

echo FORM::open('search');
?>
<div class="search_form">
    <?= FORM::input('str', Arr::get($values, 'str')); ?>
    <?= FORM::select('search_type', $search_options, Arr::get($values, 'search_type')); ?>
    <?= FORM::submit(NULL, 'Искать', array('class' => 'submit')); ?>
    <div class="tip">Запросы можно вводить через запятую, например Москва, Ауди, Тюнинг</div>
    <div><?= Message::show_once_error($errors, 'str'); ?></div>
</div>
<?= FORM::close(); ?>
<?php if ($view_results): ?>
<p style="font-size: 20px; color: #8d8d8d; margin: 10px 10px 0;">Найдено автосервисов <?= count($service->find_all()); ?></p>
<div class="search_services_all">
    <ul>
    <?php foreach ($service->find_all() as $s): ?>
        <li>
            <div class="name"><?= $s->name; ?></div>
            <div class="about"><?= Text::limit_words($s->about, 40); ?></div>
            <div class="more"><?= HTML::anchor('services/'.$s->id, 'Подробнее'); ?></div>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
<?= View::factory('profiler/stats'); ?>