<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div style="float: left;">
    <h1 style="margin-bottom: 20px;">Запросы автосервисам</h1>
</div>
<div style="float: right;">
    <?= HTML::anchor('messages/add', 'Отправить запрос'); ?>
</div>
<div class="clear"></div>
<?= Message::render(); ?>

<div class="clear"></div>

<div class="grid-row">
	<?php $i=1; foreach ($qa->where('active', '=', 1)->order_by('date', 'DESC')->find_all() as $q): ?>
		<div class="content-group grid-3">
			<div class="grid-content-block">
			<div class="header with-date">
				<div class="title">
					<?= HTML::anchor('messages/'.$q->id, $q->carbrand->name.' '.$q->model->name.' | '.$q->contact) ?>
				</div>
				<div class="date"><?= Date::full_date($q->date); ?></div>
			</div>
			<div class="body">
				<div class="text">
					<?//= Text::limit_words(strip_tags($q->text), 30); ?>
					<?//= HTML::anchor('messages/'.$q->id, 'Подробнее'); ?>
					<?= Text::short_story($q->text, 200); ?>
				</div>
			</div>
			</div>
			<noindex>
				<?= HTML::anchor('messages/'.$q->id, 'Подробнее', array('rel' => 'nofollow', 'class' => 'read-more')); ?>
			<noindex>
		</div>
		
		<?php if( ! ($i%3)): ?>
			</div>
			<div class="grid-row">
		<?php endif ?>
		
	<?php $i++; endforeach; ?>
</div>