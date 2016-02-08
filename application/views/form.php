<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open('test/form'); ?>
<?= FORM::input('name'); ?>
<?= Form::submit('save', 'saving'); ?>
<?= Form::submit('skip', 'skip'); ?>