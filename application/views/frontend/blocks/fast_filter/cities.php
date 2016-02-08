<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<ul>
    <?php foreach ($cities as $id => $name): ?>
        <?php if ($city_id == $id): ?>
            <li><?= HTML::anchor('#', $name, array('class' => 'active', 'rel' => DB::select('ymap_name')->from('cities')->where('id', '=', $id)->execute()->get('ymap_name'), 'name' => $id)); ?></li>
        <?php else: ?>
            <li><?= HTML::anchor('#', $name, array('class' => 'city', 'rel' => DB::select('ymap_name')->from('cities')->where('id', '=', $id)->execute()->get('ymap_name'), 'name' => $id)) ; ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
    <li class="expand_other_city">
        <?= HTML::anchor('#', 'Другие города', array('class' => 'choose_region')); ?>
        <div class="other_cities">
            <ul>
            <?php foreach ($other_cities as $id => $name): ?>
                <li><?= HTML::anchor('#', $name, array('rel' => DB::select('ymap_name')->from('cities')->where('id', '=', $id)->execute()->get('ymap_name'), 'name' => $id, 'class' => 'city')) ; ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    </li>
</ul>