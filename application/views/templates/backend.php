<!doctype html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <?php foreach ($styles as $style): ?>
        <?= HTML::style($style['file'], array('type' => $style['type'], 'media' => $style['media'])); ?>
    <?php endforeach; ?>

    <?php foreach ($scripts as $script): ?>
        <?= HTML::script($script['file'], array('type' => $script['type'])); ?>
    <?php endforeach; ?>
   
    <title>АдминПанель.:.<?= $title; ?></title>
</head>
<body>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <?= HTML::anchor('/', 'Ассоциация автосервисов', array('class' => 'brand')); ?>
            <div class="nav-collapse">
                <ul class="nav">
                    <li><?= HTML::anchor('admin', 'Главная'); ?></li>
                    <li><?= HTML::anchor('admin/services', 'Автосервисы'); ?></li>
                    <li><?= HTML::anchor('admin/services/type_2', 'Магазины автозапчастей'); ?></li>
                    <li><?= HTML::anchor('admin/users', 'Пользователи'); ?></li>
                    <li><?= HTML::anchor('admin/notice', 'Уведомления'); ?></li>
                    <li><?= HTML::anchor('admin/feedback', 'Feedback'); ?></li>
                    <li><?= HTML::anchor('admin/email', 'Email'); ?></li>
	                <li class="dropdown">
	                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Платежи <b class="caret"></b></a>
	                    <ul class="dropdown-menu">
	                      <li><?= HTML::anchor('admin/payment/invoice', 'Платежи'); ?></li>
	                      <li><?= HTML::anchor('admin/payment', 'Способы оплаты'); ?></li>
	                      <li><?= HTML::anchor('admin/payment/invoicelog', 'История проводок'); ?></li>
	                      <li><?= HTML::anchor('admin/payment/paymentlog', 'История начислений'); ?></li>
		                  <li><?= HTML::anchor('admin/payment/settings', 'Настройка'); ?></li>
	                    </ul>
	                  </li>
                    <li><?= HTML::anchor('admin/development', 'План разработки'); ?></li>
                </ul>
                <p class="navbar-text pull-right">
                    <?= HTML::anchor('logout', 'Выход') ?>
                </p>
            </div>

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <div class="well sidebar-nav">
                <ul class="nav nav-list">
                    <li class="nav-header">Страницы</li>
                    <li><?= HTML::anchor('admin/content/portal', 'Сайта'); ?></li>
                    <li><?= HTML::anchor('admin/content/filter', 'Фильтров'); ?></li>
                    <li><?= HTML::anchor('admin/content/cars', 'Поиска по марке авто'); ?></li>
                    <li><?= HTML::anchor('admin/content/works', 'Поиска по услуге'); ?></li>
                    <li><?= HTML::anchor('admin/content/metro', 'Поиска по станции метро'); ?></li>
                    <li><?= HTML::anchor('admin/content/district', 'Поиска по округу'); ?></li>
                    <li><?= HTML::anchor('admin/content/article', 'Статьи'); ?></li>
                    <li class="nav-header">Новости</li>
                    <li><?= HTML::anchor('admin/news/portal', 'Ассоциации'); ?></li>
                    <li><?= HTML::anchor('admin/news/world', 'Автомира'); ?></li>
                    <li><?= HTML::anchor('admin/news/service', 'Автосервисов'); ?></li>
                    <li class="nav-header">Параметры сервисов</li>
                    <li><?= HTML::anchor('admin/item/city', 'Города'); ?></li>
                    <li><?= HTML::anchor('admin/item/district', 'Округи'); ?></li>
                    <li><?= HTML::anchor('admin/item/metro', 'Станции метро'); ?></li>
                    <li><?= HTML::anchor('admin/item/carbrand', 'Марки автомобилей'); ?></li>
                    <li><?= HTML::anchor('admin/item/carmodel', 'Модели автомобилей'); ?></li>
                    <li><?= HTML::anchor('admin/item/workcategory', 'Категории услуг'); ?></li>
                    <li><?= HTML::anchor('admin/item/work', 'Услуги'); ?></li>
                    <li class="nav-header"><?= HTML::anchor('admin/services', 'Сервисы'); ?></li>
                    <li><?= HTML::anchor('admin/notice', 'Уведомления'); ?></li>
                    <li><?= HTML::anchor('admin/service/stock', 'Акции') ?></li>
                    <li><?= HTML::anchor('admin/service/vacancy', 'Вакансии') ?></li>
                    <li><?= HTML::anchor('admin/service/review', 'Отзывы') ?></li>
                    <li><?= HTML::anchor('admin/service/qa', 'Q&A'); ?></li>
                    <li class="nav-header"><?= HTML::anchor('admin/feedback', 'Feedback'); ?></li>
                    <li class="nav-header"><?= HTML::anchor('admin/email', 'Email'); ?></li>
                    <li class="nav-header"><?= HTML::anchor('admin/users', 'Пользователи'); ?></li>
                    <li class="nav-header"><?= HTML::anchor('admin/development', 'План разработки'); ?></li>
                </ul>
            </div>
           
        </div>
        <div class="span9">
            <div class="content">
                <div>
                    <ul class="breadcrumb">
                    <?php
                    $bc_count = count($bc);
                    $iteration = 1;
                    ?>
                    <?php foreach ($bc as $url => $title): ?>
                        <?php if ($bc_count == $iteration): ?>
                            <li class="active"><?= HTML::anchor('#', $title, array('class' => 'active')); ?></li>
                        <?php else: ?>
                            <li><?= HTML::anchor($url, $title); ?><span class="divider"> / </span></li>
                        <?php endif; ?>

                        <?php $iteration++; ?>
                    <?php endforeach; ?>
                    </ul>
                </div>
                <?= Message::render(View::factory('message/admin')); ?>
                <?= $content; ?>
            </div>
        </div>
    </div>





</div>

</body>
</html>