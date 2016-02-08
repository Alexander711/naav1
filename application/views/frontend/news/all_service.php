<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<h1 style="margin-bottom: 20px;"><?= $h1_title; ?></h1>
<?php if (count($news->find_all()) != 0): ?>
<div class="content_all">
    <ul>
        <?php foreach ($news->order_by('date_create', 'DESC')->find_all() as $n): ?>
            <li>
                <div class="title"><?= HTML::anchor('news/'.$n->id, $n->service->name); ?></div>
                <div class="date"><?= MyDate::show($n->date_create); ?></div>
                <div class="text">
                    <?php
                    if ($n->image AND file_exists($n->image))
                    {
                        echo HTML::image(MyHelper::get_image_pict_name($n->image), array('align' => 'left', 'style' => 'margin-top: 4px; margin-right: 6px;'));

                    }
                    ?>
                    <?= Text::limit_words(strip_tags($n->text), 70); ?>
                </div>
                <div class="more"><?= HTML::anchor('news/'.$n->id, 'Подробнее')?></div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php else: ?>
    Новостей нет. Пока что.
<?php endif; ?>