<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="services_list">


<ul>
    <?php foreach ($services->find_all() as $s): ?>
        <li>
            <div class="name">
                <div class="left">
                    <?= HTML::anchor('services/'.$s->id, $s->name); ?>
					<?php if ($s->user->user_type != "disabled") { ?>
	                <div class="recommend-small">
                        <?= HTML::image('assets/img/recommend-small.png',array('alt'=>"Ассоциация рекомендует",'title'=>"Ассоциация рекомендует")) ?>
                    </div>
	                <?php } ?>
                </div>
                <div class="right_coupon">
                <?php
                if ($s->discount->percent != 0)
                {
                    echo HTML::anchor('services/'.$s->id.'?print_coupon=1', 'Получить скидку', array('class' => 'coupon_small_'.$s->discount->percent));
                }
                ?>
                </div>


                
            </div>
            <div class="info"><?= Text::limit_words($s->about, 30); ?></div>
            <div class="address"><strong>Адрес: </strong>
                <?= $s->city->name; ?> 
                <?= $s->address; ?>
            </div>
            <div class="phone">
                <strong>Телефон: </strong>
                <?= $s->phone; ?>
            </div>
            <?php if (mb_strlen($s->fax) > 0): ?>
                <div class="fax">
                    <strong>Факс: </strong>
                    <?= $s->fax; ?>
                </div>
            <?php endif; ?>
            <div class="options">
                <ul>
                    <li>Новости <?= HTML::anchor('news/?service='.$s->id, count($s->news->find_all())); ?> </li>
                    <li>Акции <?= HTML::anchor('stocks/?service='.$s->id, count($s->stocks->find_all())); ?> </li>
                    <li>Вакансии <?= HTML::anchor('vacancies/?service='.$s->id, count($s->vacancies->find_all())); ?> </li>
                    <li>Отзывы <?= HTML::anchor('reviews/?service='.$s->id, count($s->reviews->find_all())); ?> </li>
                </ul>
                <div style="float: right;"><?= HTML::anchor('services/'.$s->id, 'Подробнее'); ?></div>

            </div>
        </li>
    <?php endforeach; ?>
</ul>
</div>