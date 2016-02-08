<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="title">Предоставляемые услуги</div>
<?php if (count($works) > 0): ?>
    <ul>
        <?php foreach ($works as $id => $name): ?>
            <li><?= HTML::anchor('services/search/work_'.$id.'/city_'.$city_id, $name); ?></li>
        <?php endforeach; ?>
    </ul>
    <div class="other"><?= HTML::anchor('filter/work/city_'.$city_id, '<span>'.__('s_fast_filter_other_works').'</span>', array('class' => 'work')); ?></div>
<?php else: ?>
    <p><?= __('s_fast_filter_empty_works'); ?></p>
<?php endif; ?>