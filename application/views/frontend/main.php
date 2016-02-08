<?php
defined('SYSPATH') or die('No direct script access.');
?>
<?= Message::render(); ?>


<div id="qfilter">
	<div class="fast-filter-ribbon">Быстрый поиск по</div>

</div>

<!--**********************************************
***** APPLICATION TEMPLATE
***********************************************-->

<script type="text/x-handlebars" data-template-name="application">


  {{outlet filters}}

  <div class="qfilter-body">
    {{outlet cities}}
    {{outlet tags}}
    {{outlet map}}
  </div>


</script>

<!--**********************************************
***** FILTERS TEMPLATES
***********************************************-->
<script type="text/x-handlebars" data-template-name="qfilter-filters">
  {{# if controller.isLoaded}}
    {{view view.FiltersContainerView}}
  {{else}}
    загрузка
  {{/if}}
</script>

<script type="text/x-handlebars" data-template-name="qfilter-filters-item">

  <a href="#" {{action "change" target="this"}} {{bindAttr class="view.content.selected:selected view.content.type"}}>
    {{view.content.text}}
    <span class="divider"></span>
  </a>
</script>



<!--**********************************************
***** CITIES TEMPLATES
***********************************************-->

<script type="text/x-handlebars" data-template-name="qfilter-main-cities">
  {{# if controller.isLoaded}}
    <div class="loader"></div>  
    {{view view.MainCitiesContainerView}}
    {{view view.OtherCitiesView}}
    
  {{else}}
    <div class="loader active"></div>  
    <p align="center">загрузка</p>
  {{/if}}
</script>

<script type="text/x-handlebars" data-template-name="qfilter-other-cities">
    Другие города
    {{view view.OtherCitiesBodyView}}
</script>

<script type="text/x-handlebars" data-template-name="qfilter-other-cities-body">
    <div class="triangle"></div>
    {{view view.OtherCitiesContainerView}}
</script>

<script type="text/x-handlebars" data-template-name="qfilter-main-cities-item">
  <a href="#" {{action "change" target="this"}} {{bindAttr class="view.content.selected:selected"}}>{{view.content.name}}</a>
</script>

<script type="text/x-handlebars" data-template-name="qfilter-other-cities-item">
    <a href="#" {{action "change" target="this"}} {{bindAttr class="view.content.selected:selected"}}>{{view.content.name}}</a>
</script>

<!--**********************************************
***** TAGS TEMPLATES
***********************************************-->
<script type="text/x-handlebars" data-template-name="qfilter-tags-main">
  {{view view.TagsBodyView}}
  <!--<div class="toggle-tags"><p align="center"><a href="#" {{action "toggle" target="this"}}>Просмотреть города на карте</a></p></div>-->
</script>
<script type="text/x-handlebars" data-template-name="qfilter-tags-body">
    {{view view.TagsContainerView}}
    <p><a {{bindAttr href="view.moreUrl"}}>{{view.moreText}}</a></p>
    
</script>
<script type="text/x-handlebars" data-template-name="qfilter-tags-item">
  <a {{bindAttr href="view.content.url"}}>{{view.content.text}}</a>
</script>

<!--**********************************************
***** MAP TEMPLATE
***********************************************-->
<script type="text/x-handlebars" data-template-name="qfilter-ymap">

</script>


<div class="h_middle_container">
    <div class="left">
        <div class="h_section stocks">
            <div class="ribbon ribbon-stocks"></div>
            <div class="title">
                <?= HTML::anchor('stocks', 'Акции автосервисов'); ?>
            </div>
            <div class="body">
                <?php foreach ($stocks->where('active', '=', 1)->limit(3)->order_by('date', 'DESC')->find_all() as $stock): ?>
                    <div class="content">
                        <div class="title">
                            <div class="company"><?= HTML::anchor(Model_Service::$type_urls[$stock->service->type].'/'.$stock->service->id,$stock->service->name); ?></div>
                            <div class="date"><?= Date::full_date($stock->date); ?></div>
                        </div>

                        <div class="text"><?= HTML::anchor(Model_Service::$type_urls[$stock->service->type].'/'.$stock->service->id.'/stocks/'.$stock->id, Text::limit_words(strip_tags($stock->text), 15)); ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="buttons">
                    <?= HTML::anchor('stocks', '<span>Все акции автосервисов</span>', array('class' => 'open-other-contents', 'rel' => 'nofollow')); ?>
                </div>
            </div>
        </div>

        <div class="h_section reviews">
            <div class="ribbon ribbon-reviews"></div>
            <div class="title">
                <?= HTML::anchor('reviews', 'Отзывы об автосервисах'); ?>
            </div>
            <div class="body">
                <?php foreach ($service_reviews->where('active', '=', 1)->limit(3)->order_by('date', 'DESC')->find_all() as $review): ?>
                    <div class="content">
                        <div class="title">
                            <div class="company"><?= HTML::anchor(Model_Service::$type_urls[$review->service->type].'/'.$review->service->id,$review->service->name); ?></div>
                            <div class="date"><?= Date::full_date($review->date); ?></div>
                        </div>
                        <div class="text"><?= Text::limit_words(strip_tags($review->text), 15).' '.HTML::anchor(Model_Service::$type_urls[$review->service->type].'/'.$review->service->id.'/reviews/'.$review->id, 'Подробнее'); ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="buttons">
                    <?= HTML::anchor('reviews', '<span>Все отзывы</span>', array('class' => 'open-other-contents', 'rel' => 'nofollow')); ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="right home_buttons">
        <ul>
            <li>
                <?= HTML::anchor('reviews/add_review', '<span>Добавить отзыв</span>', array('class' => 'button add-review')); ?>
                <span class="or"></span>
                <?= HTML::anchor('messages/add', '<span>Отправить запрос <br/> автосервисам</span>', array('class' => 'button add-question')); ?>
            </li>
            <li class="divider"></li>
        </ul>
    </div>
</div>


<div class="h_bottom_container">
    <div class="left_content">
        <div class="middle_block">


            <div class="h_section best-services">
                <div class="ribbon ribbon-best-services"></div>
                <div class="title">Лучшие автосервисы</div>
                <div class="body">
                    <?php
                    $counter = 1;
                    ?>
                    <?php foreach (ORM::factory('service')->order_by('rate', 'DESC')->limit(4)->find_all() as $service): ?>
                        <div class="content">
                            <div class="title">
                                <div class="company">
                                    <?=HTML::anchor(Model_Service::$type_urls[$service->type].'/'.$service->id,  $counter.'. '.$service->name); ?>
                                    <?php
                                    switch ($counter)
                                    {
                                        case 1:
                                            echo HTML::image('assets/img/gold.png');
                                            break;
                                        case 2:
                                            echo HTML::image('assets/img/silver.png');
                                            break;
                                        case 3:
                                            echo HTML::image('assets/img/bronze.png');
                                            break;
                                    }
                                    $attrs = ($counter == 1 OR $counter == 2 OR $counter == 3) ? array('style' => 'padding-top: 5px;') : NULL;
                                    $counter ++;
                                    ?>
                                </div>
                                <div class="date" <?= HTML::attributes($attrs); ?>><acronym title="Рейтинг"><?= $service->rate; ?></acronym></div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                    <div class="buttons">
                        <?= HTML::anchor('#', '<span>Рейтинг автосервисов</span>', array('class' => 'open-other-contents', 'rel' => 'nofollow')); ?>
                    </div>
                </div>
            </div>


            <div class="h_section last_questions">
                <div class="ribbon ribbon-questions"></div>
                <div class="title"><?= HTML::anchor('messages', 'Запросы пользователей'); ?></div>
                <div class="body">
                    <?php foreach ($questions->where('active', '=', 1)->order_by('date', 'DESC')->limit(2)->find_all() as $q): ?>
                        <div class="content">
                            <div class="title">
                                <div class="company"><?= HTML::anchor('messages/'.$q->id, $q->carbrand->name.' '.$q->model->name); ?></div>
                                <div class="date"><?= Date::full_date($q->date, FALSE); ?></div>
                            </div>
                            <div class="text">
                                <?= Text::limit_words(strip_tags($q->text), 10).'  '.HTML::anchor('messages/'.$q->id, 'Подробнее'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="buttons">
                        <?= HTML::anchor('messages', '<span>Все запросы пользователей</span>', array('class' => 'open-other-contents', 'rel' => 'nofollow')); ?>
                    </div>
                </div>
            </div>

            <div class="clear"></div>
        </div>
        <div class="h_content_block">
            <?= DB::select('text')->from('content_site')->where('url', '=', '')->execute()->get('text'); ?>
        </div>
    </div>
    <div class="right_content">

        <div class="h_section">
            <div class="ribbon ribbon-service-news"></div>
            <div class="title"><?= HTML::anchor('news', 'Новости автосервисов'); ?></div>
            <div class="body">
                <?php foreach ($service_news->where('active', '=', 1)->order_by('date_create', 'DESC')->limit(5)->find_all() as $news): ?>
                    <div class="content">
                        <div class="title">
                            <div class="company"><?= HTML::anchor(Model_Service::$type_urls[$news->service->type].'/'.$news->service->id, $news->service->name); ?></div>
                            <div class="date"><?= Date::full_date($news->date_create, FALSE); ?></div>
                        </div>
                        <div class="text">
                            <?= Text::limit_words(strip_tags($news->text), 10).'  '.HTML::anchor(Model_Service::$type_urls[$news->service->type].'/'.$news->service->id.'/news/'.$news->id, 'Подробнее'); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="buttons">
                    <?= HTML::anchor('news', '<span>Все новости автосервисов</span>', array('class' => 'open-other-contents', 'rel' => 'nofollow')); ?>
                </div>
            </div>
        </div>

        <?php if (count($world_news->find_all()) > 0): ?>
            <div class="h_section world-news">
                <div class="ribbon ribbon-world-news"></div>
                <div class="title"><?= HTML::anchor('news/world', 'Новости автомира'); ?></div>
                <div class="body">
                    <?php foreach ($world_news->where('active', '=', 1)->order_by('date', 'DESC')->limit(5)->find_all() as $news): ?>
                        <div class="content">
                            <div class="title">
                                <div class="company"><?= HTML::anchor('news/world/'.$news->id, Text::limit_chars(strip_tags($news->title), 28)); ?></div>
                                <div class="date"><?= Date::full_date($news->date, FALSE); ?></div>
                            </div>
                            <div class="text">
                                <?= Text::limit_words(strip_tags($news->text), 10).'  '.HTML::anchor('news/world/'.$news->id, 'Подробнее'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="buttons">
                        <?= HTML::anchor('news/world', '<span>Все новости автомира</span>', array('class' => 'open-other-contents', 'rel' => 'nofollow')); ?>
                    </div
                </div>
            </div>
        <?php endif; ?>

        <?php if (count($articles->find_all()) > 0): ?>
            <div class="h_section articles">
                <div class="ribbon ribbon-articles"></div>
                <div class="title"><?= HTML::anchor('articles', 'Статьи'); ?></div>
                <div class="body">
                    <?php foreach ($articles->where('active', '=', 1)->order_by('date_create', 'DESC')->limit(5)->find_all() as $article): ?>
                        <div class="content">
                            <div class="title">
                                <div class="company"><?= HTML::anchor('articles/'.$article->id, Text::limit_chars(strip_tags($article->title), 28)); ?></div>
                                <div class="date"><?= Date::full_date($article->date_create, FALSE); ?></div>
                            </div>
                            <div class="text">
                                <?= Text::limit_words(strip_tags($article->text), 10).'  '.HTML::anchor('articles/'.$article->id, 'Подробнее'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="buttons">
                        <?= HTML::anchor('articles', '<span>Все статьи</span>', array('class' => 'open-other-contents', 'rel' => 'nofollow')); ?>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>


