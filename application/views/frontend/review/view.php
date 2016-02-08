<?php
defined('SYSPATH') or die('No direct script access.');?>
<div class="content-group">
    <div class="header with-date">
        <div class="title"><h1><?= __('review_title_company_'.$review->service->type, array(':company_name' => $review->service->name, ':date' => MyDate::show($review->date))); ?></h1></div>
        <div class="date"><?= MyDate::show($review->date); ?></div>
    </div>
    <div class="body">
        <div class="text">
            <?= $review->text ?>
        </div>
    </div>
</div>