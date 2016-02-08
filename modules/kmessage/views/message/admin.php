<?php
defined('SYSPATH') or die('No direct script access.');
?>
<?php foreach ($messages as $m): ?>
    <?php
    $strong_text = '';
    switch ($m->type)
    {
        case 'error':
            $strong_text = 'Ошибка!';
            break;
        case 'success':
            $strong_text = 'Уведомление';
            break;
    }
    ?>
    <div class="alert alert-<?= $m->type; ?>"><strong><?= $strong_text; ?></strong> <?= $m->text; ?></div>
<?php endforeach; ?>