<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="search-words">
    Найден по:
    <?php foreach($words as $word): ?>
        <span><?= $word; ?></span>
    <?php endforeach; ?>
</div>