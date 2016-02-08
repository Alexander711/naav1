<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<ul class="nav nav-tabs">
    <li><?= HTML::anchor('cabinet/company/edit/'.$company->id, 'Данные компании'); ?></li>
    <li class="active"><a href="#">Галерея</a></li>
</ul>
<br />
<?= View::factory('system/blueimpuploader_gallery')->set('service', $company); ?>