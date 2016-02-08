<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<p>Здравствуйте, <?= $username; ?>!</p>
<p>Для восстановления пароля пройдите по ссылке или скопируйте в адресную строку <?= HTML::anchor('http://www.as-avtoservice.ru/activate_password?key='.$key, 'http://www.as-avtoservice.ru/activate_password?key='.$key) ?></p>