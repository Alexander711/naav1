<?php
defined('SYSPATH') or die('No direct script access.');
?>
<?php foreach ($districts->find_all() as $d): ?>
    <option value="<?= $d->id; ?>"><?= $d->name; ?></option>
<?php endforeach; ?>
