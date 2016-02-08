<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<h1 style="margin-bottom: 20px;"><?= $h1_title ?></h1>
<?php if (count($vacancy) > 0): ?>

    <?php foreach ($vacancy as $n): ?>
        <div class="content-group">
            <div class="header with-date">
                <div class="title">
                    <?php
                    if ($show_company_name === TRUE)
                        echo HTML::anchor('services/'.$n->service->id, __('vacancies_news_reviews_title_company_name_'.$n->service->type, array(':genitive_city_name' => $n->service->city->genitive_name, ':company_name' => $n->service->name)), array('class' => 'red')).' | ';
                    ?>
                    <?= HTML::anchor('services/'.$n->service->id.'/vacancies/'.$n->id, $n->title); ?>
                </div>
                <div class="date"><?= Date::full_date($n->date); ?></div>
            </div>
            <div class="body">
                <div class="address"><?= $n->service->get_address(); ?></div>
                <noindex>
                    <div class="text">
                        <?= Text::limit_words(strip_tags($n->text), 30); ?>
                        <?= HTML::anchor('services/'.$n->service->id.'/vacancies/'.$n->id, 'Подробнее')?>
                    </div>
                </noindex>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    Вакансий нет. Пока что.
<?php endif; ?>