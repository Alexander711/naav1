<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<option value="0">Выбрать округ</option>
<?php foreach ($districts as $id => $district): ?>
    <?php $attr = ($district_id == $id) ? array('selected' => 'selected') : NULL; ?>
    <option <?= HTML::attributes($attr); ?> value="<?= $id; ?>"><?= $district->name; ?></option>
<?php endforeach; ?>