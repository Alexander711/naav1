<?php
defined('SYSPATH') or die('No direct script access.');?>
<div class="content">
    <div class="content_title">
        <div class="title"><h1><?= HTML::anchor('services/'.$news->service->id, $news->service->name); ?> | <?= $news->title; ?></h1></div>
        <div class="date"><?= MyDate::show($news->date_create); ?></div>
    </div>
    <div class="text">
        <?php
        if ($news->image AND file_exists($news->image))
        {
            echo HTML::image($news->image, array('align' => 'left', 'style' => 'margin-top: 4px; margin-right: 6px;'));
        }
        ?>
        <?= $news->text ?>
    </div>
</div>

