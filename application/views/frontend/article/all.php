<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<h1 style="margin-bottom: 20px;">Статьи</h1>

<div class="grid-row">
	<?php $i=1; foreach ($article->find_all() as $a): ?>
		<div class="content-group grid-3">
			<div class="grid-content-block">
				<div class="header with-date">
					<div class="title"><?= HTML::anchor('articles/'.$a->id, $a->title); ?></div>
					<div class="date"><?= Date::full_date($a->date_create); ?></div>
				</div>
				<div class="body">
					<noindex>
						<div class="text">
							<?//= Text::limit_words(strip_tags($a->text, '<img>'), 70); ?>
							<?//= HTML::anchor('articles/'.$a->id, 'Подробнее')?>
							<?= Text::short_story($a->text, 170); ?>
						</div>
					</noindex>
				</div>
			</div>
			<noindex>
				<?= HTML::anchor('articles/'.$a->id, 'Подробнее', array('rel' => 'nofollow', 'class' => 'read-more')); ?>
			<noindex>
		</div>
		
		<?php if( ! ($i%3)): ?>
			</div>
			<div class="grid-row">
		<?php endif ?>
		
	<?php $i++; endforeach; ?>
</div>