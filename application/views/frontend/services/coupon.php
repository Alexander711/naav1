<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div id="coupon_container">
    <div style="font-size: 20px;  overflow: hidden; font-family: Times New Roman">
        <?= HTML::image('assets/img/logo.png', array('width' => 130, 'align' => 'left', 'hspace' => 20)); ?>
        <div style="padding-top: 20px;">
            <p style="margin-bottom: 10px;">Ассоциация Автосервисов <strong style="font-weight: normal;">www.as-avtoservice.ru</strong> </p>
            <p>Купон на скидку от <?= $type.' '.$service_name ?></p>
        </div>
    </div>
    <br />
    <hr style="clear: both; margin-top: 5px; color: #e0e0e0;" />
    <p align="right" style="font-size: 14px; ">Дата распечатки: <?= MyDate::show(Date::formatted_time(), TRUE); ?></p>
    <p style="clear: both; font-size: 16px; font-family: Verdana; margin-top: 5px"><?= $text; ?></p>
    <p class="button" align="center" style="margin-top: 20px;">
    <a href="#" class="button_print">
    <img src="http://www.as-avtoservice.ru/assets/img/print_cupon.png">
    </a>
    </p>
</div>




