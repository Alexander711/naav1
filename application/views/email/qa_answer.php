<?php
defined('SYSPATH') or die('No direct script access.');

?>

Добрый день, <?=$username?>.<br>
Автосервис <a href="http://www.as-avtoservice.ru/services/<?=$service_id?>">"<?=$service_name?>"</a>
<br>
<br>
<?echo nl2br($text);?>
<br>
<br>
<a href="http://www.as-avtoservice.ru/messages/<?=$message_id?>">Перейти к запросу</a>


