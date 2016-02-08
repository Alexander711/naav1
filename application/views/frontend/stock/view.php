<?php
defined('SYSPATH') or die('No direct script access.');?>
<div class="content-group">
    <div class="header with-date">
        <div class="title"><h1><?= HTML::anchor('services/'.$stock->service->id, $stock->service->name, array('style' => 'color: #BD2228;')); ?></h1></div>
        <div class="date"><?= Date::full_date($stock->date); ?></div>
    </div>
    <div class="body">
        <div class="text"><?= $stock->text ?></div>
    </div>
</div>


