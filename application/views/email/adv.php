<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<p>Заявка на рекламу от пользователя: <?= $username; ?></p>
<p>Компания:
<?php if (isset($service)): ?>
    <?= $service->orgtype->name.' '.$service->name; ?>
<?php else: ?>
    Не выбрано пользователем
<?php endif; ?>
</p>
<p style="margin-top: 10px;">Заголовок: <?= $title; ?></p>
<p align="center"><strong>Текст сообщения</strong></p>
<p><?= $text; ?></p>