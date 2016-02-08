<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<?php foreach ($metro_stations as $id => $metro): ?>
    <option value="<?= $id; ?>"><?= $metro->name; ?></option>
<?php endforeach; ?>