<?php
defined('SYSPATH') or die('No direct script access.');
echo Message::render();
?>
<div style="float: left;">
    <h1 style="margin-bottom: 20px;"><?= $h1_title; ?></h1>
</div>
<div style="float: right;">
    <?= HTML::anchor('reviews/add_review', 'Добавить отзыв'); ?>
</div>

<div class="clear"></div>

<?php if (count($review) > 0): ?>

<div class="grid-row">
    <?php $i=1; foreach ($review as $r): ?>
		<div class="content-group grid-3">
			<div class="grid-content-block">
				<div class="header with-date">
					<div class="title"><?= HTML::anchor('services/'.$r->service->id, __('vacancies_news_reviews_title_company_name_'.$r->service->type, array(':genitive_city_name' => $r->service->city->genitive_name, ':company_name' => $r->service->name))); ?></div>
					<div class="date"><?= MyDate::show($r->date); ?></div>
				</div>
				<div class="body">
					<div class="address"><i class="icon-map-marker"></i> <?= $r->service->get_address(); ?></div>
					<noindex>
						<div class="text">
							<?= Text::short_story($r->text); ?>
							<?//= HTML::anchor('services/'.$r->service->id.'/reviews/'.$r->id, 'Подробнее'); ?>
						</div>
					</noindex>
				</div>
			</div>
			<noindex>
				<?= HTML::anchor('services/'.$r->service->id.'/reviews/'.$r->id, 'Подробнее', array('class' => 'read-more')); ?>
			<noindex>
		</div>
			
		<?php if( ! ($i%3)): ?>
			</div>
			<div class="grid-row">
		<?php endif ?>
		
	<?php $i++; endforeach; ?>
</div>

<?php else: ?>
    <div style="clear: both;">Отзывов нет. Пока что.</div>
<?php endif; ?>