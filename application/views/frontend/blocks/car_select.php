<?php
defined('SYSPATH') or die('No direct script access.');
?>
<select id="car" name="car_id">
    <?php foreach ($cars->find_all() as $c): ?>
        <option value="<?= $c->id; ?>"><?= $c->name; ?></option>
    <?php endforeach; ?>
</select>