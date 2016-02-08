<?php
defined('SYSPATH') or die('No direct script access.');
$urls = array(
    'admin/news/portal' => 'Новости Ассоциации',
    'admin/news/service' => 'Новости компаний',
    'admin/news/world' => 'Новости автомира'
);
?>
<div class="clearfix">
    <ul class="nav nav-tabs">
    <?php
    foreach ($urls as $url => $text)
    {
        echo '<li';
        if ($current_url == $url)
            echo ' class="active">';
        else
            echo '>';
        echo HTML::anchor($url, $text).'</li>';
    }
    ?>
    </ul>
</div>