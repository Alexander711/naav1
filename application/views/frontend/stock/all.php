<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<h1 style="margin-bottom: 20px;"><?= $h1_title; ?></h1>
<?php if (count($stock) != 0): ?>

<div class="grid-row">
    <?php $i=1; foreach ($stock as $s): ?>
		<div class="content-group grid-3">
			<div class="grid-content-block">
				<div class="header with-date">
					<div class="title">
						<?php
						if ($company_page === FALSE)
						{
							echo HTML::anchor('services/'.$s->service->id.'/stocks/'.$s->id, 'Акция от '.Date::full_date($s->date)).' '.
								 HTML::anchor('services/'.$s->service->id, '<br>от '.mb_strtolower(__('company_type_'.$s->service->type.'_genitive')).' '.$s->service->name, array('class' => 'red'));
						}
						else
						{
							echo HTML::anchor('services/'.$s->service->id.'/stocks/'.$s->id, 'Акция от '.Date::full_date($s->date));
						}
						?>

					</div>
					<div class="date"><?= Date::full_date($s->date) ?></div>
				</div>
				<div class="body">
					<div class="address"><i class="icon-map-marker"></i> <?= $s->service->get_address(); ?></div>
					<noindex>
						<div class="text">
							<?//= Text::limit_words(strip_tags($s->text), 30); ?>
							<?//= HTML::anchor('services/'.$s->service->id.'/stocks/'.$s->id, 'Подробнее')?>
							<?= Text::short_story($s->text); ?>
						</div>
					</noindex>
				</div>
			</div>
			<noindex>
				<?= HTML::anchor('services/'.$s->service->id.'/stocks/'.$s->id, 'Подробнее', array('class' => 'read-more')); ?>
			<noindex>
		</div>
			
		<?php if( ! ($i%3)): ?>
			</div>
			<div class="grid-row">
		<?php endif ?>
		
	<?php $i++; endforeach; ?>
</div>

<?php else: ?>

    Акций нет. Пока что.

<?php endif; ?>