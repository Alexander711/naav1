<?php
defined('SYSPATH') or die('No direct script access.');
?>
<div>
    <p style="color: #7B7B7B; font-family: Verdana; font-size: 35px; font-weight: bold; margin-bottom: 20px;">Ooops...</p>
    <p style="font-family: Verdana; margin: 2px 0; font-size: 20px; color: #494949;"><?= ($message) ? $message : 'К сожалению такая страница не найдена :/' ?></p>
    <p style="font-family: Verdana; margin: 2px 0; font-size: 12px; color: #494949; ">Перейдите на <?= HTML::anchor('/', 'главную'); ?>, <?= HTML::anchor('search', 'воспользуйтесь') ?> поиском или <a href="mailto:sekretar@as-avtoservice.ru">напишите</a> администратору о ненайденной странице</p>
</div>