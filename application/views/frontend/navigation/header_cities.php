<?php
defined('SYSPATH') or die('No direct script access.');
unset($cities[$current_city]);
?>
<ul>
    <?php foreach ($cities as $id => $value): ?>
        <li><?= HTML::anchor('#', $value->name, array('rel' => $id, 'data-action' => 'choose-city')); ?></li>
    <?php endforeach; ?>
</ul>