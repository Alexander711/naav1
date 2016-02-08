<?php
defined('SYSPATH') or die('No direct script access.');

echo FORM::hidden('service', $service->id, array('id' => 'service'))
?>
<script type="text/javascript">
    visit_data = <?= $visitor_data; ?>;
    company_id = <?= $service->id; ?>;
</script>
<div class="service-title">
    <div class="title-left">
        <h1><?= Text::mb_ucfirst(__('company_type_'.$service->type)).' '.$service->orgtype->name.' &laquo;'.$service->name.'&raquo;'; ?></h1>
        <div class="address"><?= $service->get_address(); ?></div>
    </div>
	<?php if ($service->user->user_type != "disabled") { ?>
	<div class="title-right">
		<?= HTML::image('assets/img/recommend.png') ?>
    </div>
	<?php } ?>
	<div class="title-right">
        Рейтинг: <span class="rate"><?= $service->rate; ?></span>
    </div>


</div>
<div class="service-body">
    <div class="left">
        <div class="map">
            <div id="YMapsID"></div>
            
        </div>
        <script type="text/javascript">share42('<?= URL::base() ?>assets/share42/')</script>
        <?php if (count($images) > 0): ?>
            <div class="company-gallery">
                <?php foreach ($images as $i): ?>
                    <a class="gallery" title="<?= (trim($i->title)) ? $i->title : $i->name; ?>" href="/<?= $i->img_path; ?>"><?= HTML::image($i->thumb_img_path); ?></a>
                <?php endforeach; ?>
            </div>


        <?php endif; ?>
    </div>
    <div class="right">
        <div class="info">
            <div class="contacts">
                <ul>
                    <li><span><?= HTML::image('assets/img/icons/phone.png') ?> Телефон:</span> <?= (trim($service->code)) ? '+7('.$service->code.')'.$service->phone : $service->phone; ?></li>
                    <?php if (trim($service->fax)): ?>
                        <li><span><?= HTML::image('assets/img/icons/fax.png') ?> Факс:</span> <?= $service->fax; ?></li>
                    <?php endif; ?>
                    <?php if (trim($service->work_times)): ?>
                        <li><span><?= HTML::image('assets/img/icons/time.png') ?> Время работы:</span> <?= $service->work_times; ?></li>
                    <?php endif; ?>
                    <?php if (trim($service->site)): ?>
                        <?php
                        if (!strpos($service->site, '://'))
                            $service->site = 'http://'.$service->site;
                        ?>
                        <li><span><?= HTML::image('assets/img/icons/internet_explorer.png') ?> Сайт:</span> <?= HTML::anchor($service->site, $service->site, array('target' => '_blank')); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="buttons">
                <?= HTML::anchor('messages/add?service='.$service->id, 'Отправить запрос <span class="btn-right"><span class="icon">'.HTML::image('assets/img/icons/send_question.png').'</span></span>', array('class' => 'btn-send-question')) ?>
                <?php if ($service->discount->loaded()): ?>
                    <?= HTML::anchor('#coupon_dialog', 'Получить скидку на <span class="btn-right"><span class="percent">'.$service->discount->percent.'%</span></span>', array('class' => 'btn-get-coupon', 'title' => $service->get_stock_text(), 'rel' => $open_coupon)); ?>
                <?php endif; ?>

            </div>
        </div>
        <div class="navigation">
            <ul>
                <?php if (count($reviews) > 0): ?>
                    <li><?= HTML::anchor('#', 'Отзывы ('.count($reviews).')', array('rel' => 'nofollow', 'class' => 'scrollto', 'data-scroll' => 'reviews')); ?></li>
                <?php endif; ?>
                <?php if (count($cars) > 0): ?>
                    <li><?= HTML::anchor('#', 'Марки автомобилей ('.count($cars).')', array('rel' => 'nofollow', 'class' => 'scrollto', 'data-scroll' => 'cars')) ?></li>
                <?php endif; ?>
                <?php if ($service->type == 1 AND count($works) > 0): ?>
                    <li><?= HTML::anchor('#', 'Предоставляемые услуги ('.count($works).')', array('rel' => 'nofollow', 'class' => 'scrollto', 'data-scroll' => 'works')) ?></li>
                <?php endif; ?>
                <?php if (count($stocks) > 0): ?>
                    <li><?= HTML::anchor('#', 'Акций ('.count($stocks).')', array('rel' => 'nofollow', 'class' => 'scrollto', 'data-scroll' => 'stocks')) ?></li>
                <?php endif; ?>
                <?php if (count($news) > 0): ?>
                    <li><?= HTML::anchor('#', 'Новостей ('.count($news).')', array('rel' => 'nofollow', 'class' => 'scrollto', 'data-scroll' => 'news')); ?></li>
                <?php endif; ?>

                <?php if (count($vacancies) > 0): ?>
                    <li><?= HTML::anchor('#', 'Вакансий ('.count($vacancies).')', array('rel' => 'nofollow', 'class' => 'scrollto', 'data-scroll' => 'vacancies')); ?></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="about"><?= $service->about; ?></div>
        <div class="reviews">
            <div class="reviews-title">
                <h3>Отзывы</h3>
                <ul class="comment-nav">
                    <li class="active"><a href="#" rel="reviews-site"><?= HTML::image('assets/img/icons/site_logo.png'); ?> <span>На сайте (<?= count($reviews); ?>)</span></a></li>
                    <li><a href="#" rel="reviews-vk"><?= HTML::image('assets/img/icons/vk_logo.png'); ?> <span>Вконтакте</span></a></li>
                </ul>
                <div class="reviews-site">
                    <?php if (count($reviews) < 1): ?>
                        <p>Отзывов пока нет, будьте первым.</p>
                    <?php endif; ?>
                    <?php foreach ($reviews as $r): ?>
                        <div class="content-group">
                            <div class="header with-date">
                                <?php if (trim($r->name)): ?>
                                    <div class="title"><?= $r->name; ?></div>
                                <?php endif; ?>
                                <div class="date"><?= Date::full_date($r->date, TRUE); ?></div>
                            </div>
                            <noindex>
                                <div class="body">
                                    <div class="text">
                                        <?php
                                        $short_text = Text::limit_words(strip_tags($r->text), 30)
                                        ?>
                                        <span class="short-text"><?= $short_text; ?></span>
                                        <?php
                                        if (strcmp($short_text, strip_tags($r->text)) != 0)
                                        {
                                            echo HTML::anchor('#', 'Подробнее', array('class' => 'expand-review'));
                                            echo '<span class="full-text">'.strip_tags($r->text).'</span>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </noindex>
                        </div>
                    <?php endforeach; ?>
                    <a name="reviews"></a>
                    <?= Message::render(); ?>
                    <h3 style="font-size: 15px; font-weight: bold; margin: 0;">Написать отзыв</h3>
                    <?= FORM::open(Request::current()->uri().'#reviews'); ?>
                        <ul class="form" style="padding: 1px 0 0;">
                            <li>
                                <label>Имя</label>
                                <?= FORM::input('name'); ?>
                                <?= Message::show_error($errors, 'name'); ?>
                            </li>
                            <li>
                                <label>Email</label>
                                <?= FORM::input('email'); ?>
                                <?= Message::show_error($errors, 'email'); ?>
                            </li>
                            <li>
                                <label>Текст отзыва</label>
                                <?= FORM::textarea('text', Arr::get($values, 'text'), array('style' => 'width: 300px;')); ?>
                                <?= Message::show_error($errors, 'text'); ?>
                            </li>
                            <li>
                                <label></label>
                                <?= FORM::checkbox('antibot', '7', FALSE).'Подверждаю отправку отзыва'; ?>
                                <?= Message::show_error($errors, '_external.antibot'); ?>
                            </li>
                            <li>
                                <label></label>
                                <?= FORM::submit(NULL, 'Отправить', array('class' => 'submit')); ?>
                            </li>
                        </ul>
                    <?= FORM::close(); ?>
                </div>
                <div class="reviews-vk" id="vk_comments"></div>
                <script type="text/javascript">
                     VK.init({apiId: 2857930, onlyWidgets: true});
                     VK.Widgets.Comments("vk_comments", {limit: 20, width: "950", attach: "*"});
                </script>
            </div>



        </div>

        <?php if (count($cars) > 0): ?>
            <div class="cars">
                <h3>Марки автомобилей (<?= count($cars); ?>)</h3>
                <ul>
                    <?php foreach (array_slice($cars, 0, Kohana::$config->load('settings.company_page.cars_show_count')) as $c): ?>
                        <li><?= HTML::anchor('services/search/car_'.$c->id.'/city_'.$service->city->id, Text::mb_ucfirst(__('work_type_'.$service->type)).' '.$c->get_car_name()); ?></li>
                    <?php endforeach; ?>
                    <?php if (count($cars) > Kohana::$config->load('settings.company_page.cars_show_count')): ?>
                        <li><?= HTML::anchor('#', 'Еще ('.count(array_slice($cars, Kohana::$config->load('settings.company_page.cars_show_count'))).')', array('class' => 'expand-cars')); ?></li>
                        <?php foreach (array_slice($cars, Kohana::$config->load('settings.company_page.cars_show_count')) as $c): ?>
                            <li class="other-cars"><?= HTML::anchor('services/search/car_'.$c->id.'/city_'.$service->city->id, Text::mb_ucfirst(__('work_type_'.$service->type)).' '.$c->get_car_name()); ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($service->type == 1 AND count($works) > 0): ?>
            <div class="works">
                <h3>Предоставляемые услуги (<?= count($works); ?>)</h3>
                <ul>
                    <?php foreach (array_slice($works, 0, Kohana::$config->load('settings.company_page.works_show_count')) as $w): ?>
                        <li><?= HTML::anchor('services/search/work_'.$w->id.'/city_'.$service->city->id, $w->name); ?></li>
                    <?php endforeach; ?>
                    <?php if (count($works) > Kohana::$config->load('settings.company_page.works_show_count')): ?>
                        <li><?= HTML::anchor('#', 'Еще ('.count(array_slice($works, Kohana::$config->load('settings.company_page.works_show_count'))).')', array('class' => 'expand-works')); ?></li>

                        <?php foreach (array_slice($works, Kohana::$config->load('settings.company_page.works_show_count')) as $w): ?>
                            <li class="other-works"><?= HTML::anchor('services/search/work_'.$w->id.'/city_'.$service->city->id, $w->name); ?></li>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (count($news) > 0): ?>
            <div class="news">
                <h3>Новости (<?= count($news); ?>)</h3>
                <?php foreach ($news as $n): ?>
                    <div class="content-group">
                        <div class="header with-date">
                            <div class="title"><?= HTML::anchor(Kohana::$config->load('settings.company_type_url.'.$service->type).'/'.$service->id.'/news/'.$n->id, $n->title); ?></div>
                            <div class="date"><?= Date::full_date($n->date_create); ?></div>
                        </div>
                        <noindex>
                            <div class="body">
                                <div class="text">
                                    <?= Text::limit_words(strip_tags($n->text), 20); ?>
                                    <?= HTML::anchor(Kohana::$config->load('settings.company_type_url.'.$service->type).'/'.$service->id.'/news/'.$n->id, 'Подробнее')?>
                                </div>
                            </div>
                        </noindex>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (count($stocks) > 0): ?>
            <div class="stocks">
                <h3>Акции (<?= count($stocks); ?>)</h3>
                <?php foreach ($stocks as $s): ?>
                    <div class="content-group">
                        <div class="header with-date">
                            <?php if (trim($s->title)): ?>
                                <div class="title"><?= HTML::anchor(Kohana::$config->load('settings.company_type_url.'.$service->type).'/'.$service->id.'/stocks/'.$s->id, $s->title); ?></div>
                            <?php endif; ?>

                            <div class="date"><?= Date::full_date($s->date) ?></div>
                        </div>
                        <noindex>
                            <div class="body">
                                <div class="text">
                                    <?= Text::limit_words(strip_tags($s->text), 20); ?>
                                    <?= HTML::anchor(Kohana::$config->load('settings.company_type_url.'.$service->type).'/'.$service->id.'/stocks/'.$s->id, 'Подробнее')?>
                                </div>
                            </div>
                        </noindex>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (count($vacancies) > 0): ?>
            <div class="vacancies">
                <h3>Вакансии (<?= count($vacancies); ?>)</h3>
                <?php foreach ($vacancies as $v): ?>
                    <div class="content-group">
                        <div class="header with-date">
                            <div class="title"><?= HTML::anchor(Kohana::$config->load('settings.company_type_url.'.$service->type).'/'.$service->id.'/vacancies/'.$v->id, $v->title); ?></div>
                            <div class="date"><?= Date::full_date($v->date); ?></div>
                        </div>
                        <noindex>
                            <div class="body">
                                <div class="text">
                                    <?= Text::limit_words(strip_tags($v->text), 20); ?>
                                    <?= HTML::anchor(Kohana::$config->load('settings.company_type_url.'.$service->type).'/'.$service->id.'/vacancies/'.$v->id, 'Подробнее')?>
                                </div>
                            </div>
                        </noindex>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

</div>

<div class="coupon_text" style="display: none;">
    <?php
    $type = ($service->type == 1) ? 'автосервиса' : 'магазина автозапчастей';
    $service_name = $service->orgtype->name.' &laquo;'.$service->name.'&raquo;';
    $text  = (mb_strlen(trim($service->coupon_text)) == 0)
                     ? __('coupon_standart_text', array(':percent' => $service->discount->percent, ':service' => $service->name))
                     : $service->coupon_text;
    echo $coupon_view = View::factory('frontend/services/coupon')
                                   ->set('text', $text)
                                   ->set('type', $type)
                                   ->set('service_name', $service_name);
    ?>
</div>



