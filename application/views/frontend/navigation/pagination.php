<?php
defined('SYSPATH') or die('No direct script access.'); ?>

<?php for ($i = 1; $i <= $pages_count; $i++): ?>
    <?php if ($current == $i): ?>
        <?= HTML::anchor('#', $i, array('class' => 'current', 'rel' => $i)) ?>
    <?php else: ?>
        <?= HTML::anchor('#', $i, array('rel' => $i)) ?>
    <?php endif; ?>
<?php endfor; ?>
