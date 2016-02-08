<?php
defined('SYSPATH') or die('No direct script access.');
?>

<div class="cities_navigation" style="clear: both;">
    <ul>
        <?php
        foreach ($cities as $id => $city)
        {
            $attr['class'] = ($id == $city_id) ? 'active' : '';
            if ($id == 0)
                echo '<li>'.HTML::anchor($url, $city->name, $attr).'</li>';
            else
                echo '<li>'.HTML::anchor($url.'/city_'.$id, $city->name, $attr).'</li>';
        }
        ?>
    </ul>
</div>