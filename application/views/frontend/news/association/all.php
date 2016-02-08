<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<h1 style="margin-bottom: 20px;">Новости ассоциации</h1>
<?php if (count($news) > 0): ?>

	<div class="grid-row">
		<?php $i=1; foreach ($news as $n): ?>
			<div class="content-group grid-3">
				<div class="grid-content-block">
					<div class="header with-date">
						<div class="title"><?= HTML::anchor('news/association/'.$n->id, $n->title); ?></div>
						<div class="date"><?= Date::full_date($n->date); ?></div>
					</div>
					<div class="body">
						<noindex>
							<div class="text" style="overflow: hidden;">
								<?//= Text::limit_words(strip_tags($n->text, '<img><a>'), 35); ?>
								<?= Text::short_story($n->text, 200); ?>
							</div>
							<?//= HTML::anchor('news/association/'.$n->id, 'Подробнее'); ?>
						<noindex>
					</div>
				</div>
				<noindex>
					<?= HTML::anchor('news/association/'.$n->id, 'Подробнее', array('rel' => 'nofollow', 'class' => 'read-more')); ?>
				<noindex>
			</div>
			
			<?php if( ! ($i%3)): ?>
				</div>
				<div class="grid-row">
			<?php endif ?>
			
		<?php $i++; endforeach; ?>
	</div>

<?php else: ?>
    Новостей пока нет.
<?php endif; ?>