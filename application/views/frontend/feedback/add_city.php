<?php
defined('SYSPATH') or die('No direct script access.');
echo Debug::vars($errors);
echo Form::open(Request::current()->uri());
?>
<p>Текст запроса</p>
<p><?= Form::textarea('text', Arr::get($values, 'text'), array('class' => 's_tarea', 'style' => 'clear: both;')); ?></p>
    <p><?= FORM::submit('submit', __('f_send'), array('class' => 's_button')); ?></p>
<?= Form::close(); ?>