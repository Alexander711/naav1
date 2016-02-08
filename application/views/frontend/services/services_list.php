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
                    echo '<noindex>'.HTML::anchor('services/'.$s->id.'?print_coupon=1', 'Получить скидку', array('rel' => 'nofollow', 'class' => 'coupon_small_'.$s->discount->percent)).'</noindex>';
                }
                ?>
                </div>
            </div>

            <noindex>
                <div class="info"><?= Text::limit_words($s->about, 30); ?></div>
            </noindex>
            <div class="address"><strong>Адрес: </strong>
                <?= $s->city->name; ?>
                <?= $s->address; ?>
            </div>
            <noindex>
                <div class="phone">
                    <strong>Телефон: </strong>
                    <?= (trim($s->code)) ? '+7('.$s->code.')'.$s->phone : $s->phone; ?>
                </div>
                <?php if (mb_strlen($s->fax) > 0): ?>
                    <div class="fax">
                        <strong>Факс: </strong>
                        <?= $s->fax; ?>
                    </div>
                <?php endif; ?>
            </noindex>
            <noindex>
                <div class="options">
                    <ul>
                        <li>
                            Новости
                            <?php
                            $count = count($s->news->find_all());
                            echo ($count > 0) ? HTML::anchor('services/'.$s->id.'/news', $count, array('rel' => 'nofollow')) : 0;
                            ?>
                        </li>
                        <li>
                            Акции
                            <?php
                            $count = count($s->stocks->find_all());
                            echo ($count > 0) ? HTML::anchor('services/'.$s->id.'/stocks', $count, array('rel' => 'nofollow')) : 0;
                            ?>
                        </li>
                        <li>
                            Вакансии
                            <?php
                            $count = count($s->vacancies->find_all());
                            echo ($count > 0) ? HTML::anchor('services/'.$s->id.'/vacancies', $count, array('rel' => 'nofollow')) : 0;
                            ?>
                        </li>
                        <li>
                            Отзывы
                            <?php
                            $count = count($s->reviews->find_all());
                            echo ($count > 0) ? HTML::anchor('services/'.$s->id.'/reviews', $count, array('rel' => 'nofollow')) : 0;
                            ?>
                        </li>
                    </ul>
                    <div style="float: right;"><?= HTML::anchor('services/'.$s->id, 'Подробнее', array('rel' => 'nofollow')); ?></div>
                </div>
            </noindex>
        </li>
    <?php endforeach; ?>
</ul>
</div>