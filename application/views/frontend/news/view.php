<?php
defined('SYSPATH') or die('No direct script access.');?>
<div class="content-group">
    <div class="header with-date">
        <div class="title"><h1><?= $news->title; ?></h1></div>
        <div class="date"><?= Date::full_date($news->date); ?></div>
    </div>
    <div class="body">
        <div class="text">
            <?= $news->text ?>
        </div>
    </div>
</div>