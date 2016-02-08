<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="clearfix">
    <?= FORM::open($url, array('class' => 'well form-search')); ?>
    <?= FORM::input('str', Arr::get($values, 'str'), array('class' => 'input-medium search-query', 'style' => 'width: 400px;')).FORM::submit(NULL, 'Искать', array('class' => 'btn')); ?>
    <?= FORM::close(); ?>
</div>