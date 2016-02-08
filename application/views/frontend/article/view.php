<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="content-group">
    <div class="header with-date">
        <div class="title"><h1><?= $article->title; ?></h1></div>
        <div class="date"><?= MyDate::show($article->date_create); ?></div>
    </div>
    <div class="body">
        <div class="text">
            <?= $article->text ?>
        </div>
    </div>
</div>