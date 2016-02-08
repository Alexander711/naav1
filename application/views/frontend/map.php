<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div id="myMap" style="width: 300px; height: 300px; display: none;">

</div>
<div>
    Город:
    <select name="city_id">
        <option value="0">Выбрать город</option>
        <option value="1">Москва</option>
        <option value="2">Санкт-петербург</option>
    </select>
    Адрес:
    <input name="addr" size="40"/>
    <input type="hidden" name="ymap_lat"/>
    <input type="hidden" name="ymap_lng"/>
</div>
<hr />
    Адрес
<div id="address"></div>
    Координаты геолокации
<div id="geopoint"><strong></strong><strong></strong></div>
    Ошибки
<div id="error"></div>