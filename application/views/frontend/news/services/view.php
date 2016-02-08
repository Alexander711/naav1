<?php
defined('SYSPATH') or die('No direct script access.');?>
<div class="content-group">
    <div class="header with-date">
        <div class="title"><h1><?= HTML::anchor('services/'.$news->service->id, $news->service->name); ?> | <?= $news->title; ?></h1></div>
        <div class="date"><?= Date::full_date($news->date_create); ?></div>
    </div>
    <div class="body">
        <div class="text">
            <?php
            if ($news->image AND file_exists($news->image))
                echo HTML::image($news->image, array('align' => 'left', 'style' => 'margin-top: 4px; margin-right: 6px;'));
            echo $news->text;
            ?>
        </div>
    </div>
</div>