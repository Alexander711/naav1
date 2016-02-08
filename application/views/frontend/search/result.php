<?php
defined('SYSPATH') or die('No direct script access.');
?>
<div class="navigation">
    <?php if (!empty($types)): ?>
        Показать:
        <?php foreach ($types as $target => $text): ?>
            <?= HTML::anchor('#', $text, array('data-target' => $target)); ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="search-result services">
    <?php if (count($services) > 0): ?>
         <h1 class="title">Найдено автосервисов <?= count($services); ?></h1>
    <?php endif; ?>
    <?php foreach ($services as $c): ?>
        <div class="content-group">
            <div class="header">
                <div class="title"><?= HTML::anchor('services/'.$c->id, $c->name); ?></div>
                <?= $company->get_words($c); ?>
            </div>
            <div class="body">
                <div class="address"><?= $c->get_address(); ?></div>
                <noindex>
                    <div class="text">
                        <?= Text::limit_words(strip_tags($c->about), 40); ?>
                        <?= HTML::anchor('services/'.$c->id, 'Подробнее'); ?>
                    </div>
                </noindex>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="search-result shops">
    <?php if (count($shops) > 0): ?>
        <h1 class="title">Найдено магазинов автозапчастей <?= count($shops); ?></h1>
    <?php endif; ?>
    <?php foreach ($shops as $c): ?>
        <div class="content-group">
            <div class="header">
                <div class="title"><?= HTML::anchor('shops/'.$c->id, $c->name); ?></div>
                <?= $company->get_words($c); ?>
            </div>
            <div class="body">
                <div class="address"><?= $c->get_address(); ?></div>
                <noindex>
                    <div class="text">
                        <?= Text::limit_words(strip_tags($c->about), 40); ?>
                        <?= HTML::anchor('shops/'.$c->id, 'Подробнее'); ?>
                    </div>
                </noindex>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="search-result companies-news">
    <?php if (count($news->companies) > 0): ?>
        <h1 class="title">Найдено Новостей компаний <?= count($news->companies); ?></h1>
    <?php endif; ?>
    <?php foreach ($news->companies as $n): ?>
        <div class="content-group">
            <div class="header with-date">
                <div class="title"><?= HTML::anchor(Model_Service::$type_urls[$n->service->type].'/'.$n->service->id, $n->service->name, array('class' => 'red')).' | '.HTML::anchor(Model_Service::$type_urls[$n->service->type].'/'.$n->service->id.'/news/'.$n->id, $n->title); ?></div>
                <div class="date"><?= Date::full_date($n->date_create); ?></div>
            </div>

            <div class="body">
                <div class="address"><?= $n->service->get_address(); ?></div>
                <noindex>
                    <div class="text">
                        <?= Text::limit_words(strip_tags($n->text), 300); ?>
                        <?= HTML::anchor(Model_Service::$type_urls[$n->service->type].'/'.$n->service->id.'/news/'.$n->id, 'Подробнее'); ?>
                    </div>
                </noindex>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="search-result news-association">
    <?php if (count($news->portal) > 0): ?>
        <h1 class="title">Найдено новостей ассоциации <?= count($news->portal); ?></h1>
    <?php endif; ?>
    <?php foreach ($news->portal as $n): ?>
        <div class="content-group">
            <div class="header with-date">
                <div class="title"><?= HTML::anchor('news/association'.$n->id, $n->title); ?></div>
                <div class="date"><?= Date::full_date($n->date); ?></div>
            </div>

            <div class="body">
                <noindex>
                    <div class="text">
                        <?= Text::limit_words(strip_tags($n->text), 30); ?>
                        <?= HTML::anchor('news/association'.$n->id, 'Подробнее'); ?>
                    </div>
                </noindex>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="search-result news-world">
    <?php if (count($news->world) > 0): ?>
        <h1 class="title">Найдено новостей автомира <?= count($news->world); ?></h1>
    <?php endif; ?>
    <?php foreach ($news->world as $n): ?>
        <div class="content-group">
            <div class="header with-date">
                <div class="title"><?= HTML::anchor('news/world'.$n->id, $n->title); ?></div>
                <div class="date"><?= Date::full_date($n->date); ?></div>
            </div>

            <div class="body">
                <noindex>
                    <div class="text">
                        <?= Text::limit_words(strip_tags($n->text), 30); ?>
                        <?= HTML::anchor('news/world'.$n->id, 'Подробнее'); ?>
                    </div>
                </noindex>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="search-result stocks">
    <?php if (count($stocks) > 0): ?>
        <h1 class="title">Найдено Акций <?= count($stocks); ?></h1>
    <?php endif; ?>
    <?php foreach ($stocks as $s): ?>
        <div class="content-group">
            <div class="header with-date">
                <div class="title"><?= HTML::anchor(Model_Service::$type_urls[$s->service->type].'/stocks/'.$s->id, 'Акция '.mb_strtolower(__('company_type_'.$s->service->type.'_genitive')).' '.$s->service->name); ?></div>
                <div class="date"><?= Date::full_date($s->date); ?></div>
            </div>
            <div class="body">
                <noindex>
                    <div class="text">
                        <?= Text::limit_words(strip_tags($s->text), 100); ?>
                        <?= HTML::anchor(Model_Service::$type_urls[$s->service->type].'/stocks/'.$s->id, 'Подробнее'); ?>
                    </div>
                </noindex>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="search-result vacancies">
    <?php if (count($vacancies) > 0): ?>
        <h1 class="title">Найдено Вакансий <?= count($vacancies); ?></h1>
    <?php endif; ?>
    <?php foreach ($vacancies as $v): ?>
        <div class="content-group">
            <div class="header with-date">
                <div class="title"><?= HTML::anchor(Model_Service::$type_urls[$v->service->type].'/'.$v->service->id.'/vacancies/'.$v->id, $v->title); ?></div>
                <div class="date"><?= Date::full_date($v->date); ?></div>
            </div>

            <div class="body">
                <div class="text">
                    <?= Text::limit_words(strip_tags($v->text), 100); ?>
                    <?= HTML::anchor(Model_Service::$type_urls[$v->service->type].'/'.$v->service->id.'/vacancies/'.$v->id, 'Подробнее'); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

