<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="fast-filter-cities">
    <ul class="primary" >
        <?php foreach ($cities as $id => $city): ?>
        <?php
        $attr= ($id == $city_id) ? array('class' => 'fast-filter-city active') : array('class' => 'fast-filter-city', 'data-action' => 'choose-city');
        ?>
            <li><a href="#" rel="<?= $id; ?>" <?= HTML::attributes($attr); ?>><?= $city->name; ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php if ($other_cities AND is_array($other_cities) AND count($other_cities) > 0): ?>
    <?php unset ($city); ?>
        <ul class="other">
            <li>
                <a href="#" class="expand-link">Другие города</a>
                <div class="cities-list">
                    <ul>
                        <?php foreach ($other_cities as $id => $city): ?>
                        <li><a href="#" data-action="choose-city" rel="<?= $id; ?>"><?= $city->name; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
        </ul>
    <?php endif; ?>
</div>