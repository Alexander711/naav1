<?php
defined('SYSPATH') or die('No direct script access.');
?>
<?php foreach ($metro->find_all() as $m): ?>
    <option value="<?= $m->id; ?>"><?= $m->name; ?></option>
<?php endforeach; ?>
