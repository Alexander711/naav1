<?php
defined('SYSPATH') or die('No direct script access.');
?>
<ul>
<?php foreach ($services->find_all() as $service): ?>
    <li><?= $service->name; ?></li>
<?php endforeach; ?>
</ul>
