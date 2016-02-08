<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<h1 style="margin-bottom: 20px;"><?= $h1_title; ?></h1>
<div class="clear"></div>
<?php if (count($news) > 0): ?>

<div class="grid-row">
    <?php $i=1; foreach ($news as $n): ?>
		<div class="content-group grid-3">
			<div class="grid-content-block">
				<div class="header with-date">
					<div class="title">
						<?php
						if ($show_company_name)
							echo HTML::anchor('services/'.$n->service->id, __('vacancies_news_reviews_title_company_name_'.$n->service->type, array(':genitive_city_name' => $n->service->city->genitive_name, ':company_name' => $n->service->name)), array('class' => 'red')).' | ';
						?>
						<?= HTML::anchor('services/'.$n->service->id.'/news/'.$n->id, $n->title); ?>
					</div>
					<div class="date"><?= Date::full_date($n->date_create); ?></div>
				</div>
				<div class="body">
					<div class="address"><i class="icon-map-marker"></i> <?= $n->service->get_address(); ?></div>
					<noindex>
						<div class="text">
							<?php
							//if ($n->image AND file_exists($n->image))
								//echo HTML::image($n->image, array('align' => 'left', 'style' => 'margin-top: 4px; margin-right: 6px;'));
							//echo Text::limit_words(strip_tags($n->text), 30);
							?>
							<?//= HTML::anchor('services/'.$n->service->id.'/news/'.$n->id, 'Подробнее')?>
							<?= Text::short_story($n->text, 200); ?>
						</div>
					</noindex>
				</div>
			</div>
			<noindex>
				<?= HTML::anchor('services/'.$n->service->id.'/news/'.$n->id, 'Подробнее', array('rel' => 'nofollow', 'class' => 'read-more')); ?>
			<noindex>
		</div>
		
		<?php if( ! ($i%3)): ?>
			</div>
			<div class="grid-row">
		<?php endif ?>
		
	<?php $i++; endforeach; ?>
</div>

<?php else: ?>
    Новостей нет. Пока что.
<?php endif; ?>