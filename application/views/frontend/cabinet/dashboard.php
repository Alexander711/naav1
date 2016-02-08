<?php
defined('SYSPATH') or die('No direct script access.');

$stock->reset(FALSE);
$vacancy->reset(FALSE);
$news->reset(FALSE);

$service->reset(FALSE);
?>
<script type="text/javascript">
    stat = <?= $statistics; ?>;
</script>
<div class="cabinet_dashboard">
    <div class="statistics">
        <div id="chart-all" style="width: 100%; height: 300px;"></div>
    </div>
    <div class="left">
        <div class="dashboard_block">
            <div class="block_title">
                <?= HTML::image('assets/img/icons/services.png'); ?> <?= HTML::anchor('cabinet/company', __('a_your_companies')); ?>
            </div>
            <div class="text">
                <?php if (count($service->find_all()) > 0): ?>
                    <ul>
                    <?php foreach ($service->find_all() as $s): ?>
                        <li class="item">
                            <div>
                                <div class="left">
                                    <?= ($s->type == 2) ? 'Магазин автозапчастей' : 'Автосервис'; ?>
                                    <?= HTML::anchor('services/'.$s->id, $s->name); ?>
                                </div>
                                <div class="right">
                                    <div class="right"><?= HTML::anchor('cabinet/company/edit/'.$s->id, HTML::image('assets/img/icons/pencil.png')).HTML::anchor('cabinet/company/delete/'.$s->id, HTML::image('assets/img/icons/del.png')); ?></div>
                                </div>
                                <div style="clear: both;"><?= $s->about; ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <?= __('s_havent_companies'); ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard_block">
            <div class="block_title">
                <?= HTML::image('assets/img/icons/news.png'); ?> <?= HTML::anchor('cabinet/news', __('a_your_news')); ?>
            </div>
            <div class="text">
                <?php if (count($news->find_all()) > 0): ?>
                    <ul>
                    <?php foreach ($news->find_all() as $n): ?>
                        <li class="item">
                            <ul>
                                <li>
                                    <div class="left"><?= MyDate::show($n->date_create); ?> | <?= HTML::anchor('services/'.$n->service->id, $n->service->name); ?></div>
                                    <div class="right"><?= HTML::anchor('cabinet/news/edit/'.$n->id, HTML::image('assets/img/icons/pencil.png')).HTML::anchor('cabinet/news/delete/'.$n->id, HTML::image('assets/img/icons/del.png')); ?></div>
                                </li>
                                <li>
                                    <?= HTML::anchor('news/'.$n->id, $n->title); ?>
                                </li>
                                <li>
                                    <?= Text::limit_words($n->text, 50); ?>
                                </li>
                            </ul>
                   
                        </li>


                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <?= __('s_havent_news'); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard_block">
            <div class="block_title">
                <?= HTML::image('assets/img/icons/review.png'); ?> <?= HTML::anchor('cabinet/reviews', __('a_your_reviews')); ?>
            </div>
            <div class="text">

                <?php if (count($reviews) > 0): ?>
                    <ul>
                    <?php foreach ($reviews as $review): ?>
                        <li class="item">
                            <div>
                                <div class="left"><?= $review['service_name']; ?></div>
                                <div class="right"><?= MyDate::show($review['date']); ?></div>
                            </div>
                            <div style="clear: both;"><?= Text::limit_words($review['text'], 50); ?></div>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <?= __('s_havent_reviews'); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard_block">
            <div class="block_title">
                <?= HTML::image('assets/img/icons/messages.png'); ?> <?= HTML::anchor('cabinet/qa', __('cb_questions')); ?>
            </div>
            <div class="text">
                <ul>
                <?php foreach ($question->where('active', '=', 1)->find_all() as $q): ?>
                    <li class="item">
                        
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="right">
        <div class="dashboard_block">
            <div class="block_title">
                <?= HTML::image('assets/img/icons/vacancies.png'); ?> <?= HTML::anchor('cabinet/vacancy', __('cb_vacancies')); ?>
            </div>
            <div class="text">
                <?php if (count($vacancy->find_all()) > 0): ?>
                    <ul>
                        <?php foreach ($vacancy->find_all() as $v): ?>
                            <li class="item">
                                <div>
                                    <div class="left">
                                        <?= ($v->service->type == 2) ? 'Магазин автозапчастей' : 'Автосервис'; ?>
                                        <?= $v->service->name; ?>
                                    </div>
                                    <div class="right">
                                        <?= HTML::anchor('cabinet/vacancy/edit/'.$v->id, HTML::image('assets/img/icons/pencil.png')).HTML::anchor('cabinet/vacancy/delete/'.$v->id, HTML::image('assets/img/icons/del.png')); ?>
                                    </div>

                                </div>
                                <div style="clear: both;">
                                    <?= HTML::anchor('vacancies/'.$v->id, $v->title); ?>
                                </div>
                                <div style="clear: both;">
                                    <?= Text::limit_words($v->text, 50); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <?= __('s_havent_vacancies') ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="dashboard_block">
            <div class="block_title">
                <?= HTML::image('assets/img/icons/stocks.png'); ?> <?= HTML::anchor('cabinet/stock', __('cb_stocks')); ?>
            </div>
            <div class="text">
                <?php if (count($stock->find_all()) > 0): ?>
                    <ul>
                    <?php foreach ($stock->find_all() as $s): ?>
                        <li class="item">
                            <div>
                                <div class="left">
                                    <?= MyDate::show($s->date); ?>
                                </div>
                                <div class="right">
                                    <?= HTML::anchor('cabinet/stock/edit/'.$s->id, HTML::image('assets/img/icons/pencil.png')).HTML::anchor('cabinet/stock/delete/'.$s->id, HTML::image('assets/img/icons/del.png')); ?>
                                </div>
                            </div>
                            <div style="clear: both;">
                                <?= HTML::anchor('services/'.$s->service->id, $s->service->name) ?>
                            </div>
                            <div>
                                <?= $s->text; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <?= __('s_havent_stocks') ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="dashboard_block feedback">
            <div class="block_title">
                <?= HTML::image('assets/img/icons/communicate.png'); ?> <?= HTML::anchor('cabinet/feedback', __('a_feedback_administration')); ?>
            </div>
            <div class="text">
                <ul>
                    <li class="item"><?= HTML::anchor('cabinet/notice', 'Уведомлений ('.$notices_unread_count.'/'.$notices_all_count.')'); ?></li>
                    <li class="item"><?= HTML::anchor('cabinet/feedback', __('a_feedback')) ?></li>
                </ul>
            </div>
        </div>

        <div class="dashboard_block profile">
            <div class="block_title">
                <?= HTML::image('assets/img/icons/profile.png'); ?> <?= HTML::anchor('cabinet/profile', __('cb_profile')); ?>
            </div>
            <div class="text">
                <ul>
                    <li class="item"><?= HTML::anchor('cabinet/profile', __('a_dashboard_edit_profile')) ?></li>
                </ul>
            </div>
        </div>

        <div class="dashboard_block adv">
            <div class="block_title">
                <?= HTML::image('assets/img/icons/adv.png'); ?> <?= HTML::anchor('cabinet', __('cb_adv')); ?>
            </div>
            <div class="text">
                <ul>
                    <li class="item"><?= HTML::anchor('cabinet/adv', __('a_dashboard_create_adv')); ?></li>
                </ul>
            </div>
        </div>


    </div>
</div>