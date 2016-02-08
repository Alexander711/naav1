<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<p>Уважаемый, <?= $username; ?>!</p>
<p>Вам пришло уведомление от <?= HTML::anchor('http://www.as-avtoservice.ru', 'Ассоциации автосервисов') ?></p>
<p><strong><?= $title ?></strong></p>
<p><?= $text; ?></p>
<p><?= HTML::anchor('http://www.as-avtoservice.ru/cabinet/notice', 'Просмотреть все уведомления') ?></p>