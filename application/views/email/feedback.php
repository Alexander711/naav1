<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<p>Сообщение обратной связи от пользователя: <?= $username; ?></p>
<p style="margin-top: 10px;">Заголовок: <?= $title; ?></p>
<p align="center"><strong>Текст сообщения</strong></p>
<p><?= $text; ?></p>