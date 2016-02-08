<?php
defined('SYSPATH') or die('No direct script access.');
?>
<ul>
    <?php foreach ($cities as $ymap_name => $name): ?>
        <li><?= HTML::anchor('#', $name, array('rel' => $ymap_name, 'class' => 'region')); ?></li>
    <?php endforeach; ?>
</ul>
