<?php
defined('SYSPATH') or die('No direct script access.');
?>
<ul>
    <?php
    foreach ($cities as $id => $city)
    {
        $attr['class'] = ($id == $city_id) ? 'active' : '';
        echo '<li>'.HTML::anchor($url.'/city_'.$id, $city->name, $attr).'</li>';
    }
    ?>
</ul>