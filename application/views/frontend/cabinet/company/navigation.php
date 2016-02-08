<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<ul class="nav nav-tabs">
    <?php
    foreach ($urls as $url => $text)
    {
        $attrs = ($current_url == $url) ? array('class' => 'active') : NULL;
        echo '<li '.HTML::attributes($attrs).'>'.HTML::anchor($url, $text).'</li>';
    }
    ?>
</ul>