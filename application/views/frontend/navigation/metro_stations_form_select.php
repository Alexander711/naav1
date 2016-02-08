<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<option value="0">Выбрать станцию метро</option>
<?php foreach ($metro_stations as $id => $metro): ?>
    <?php $attr = ($metro_id == $id) ? array('selected' => 'selected') : NULL; ?>
    <option <?= HTML::attributes($attr); ?> value="<?= $id; ?>"><?= $metro->name; ?></option>
<?php endforeach; ?>