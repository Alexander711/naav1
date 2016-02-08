<?php
defined('SYSPATH') or die('No direct script access.');
$urls = array(
    'admin/content/portal' => 'Страницы сайта',
    'admin/content/filter' => 'Страницы фильтров',
    'admin/content/cars' => 'Страницы поиска по марке авто',
    'admin/content/works' => 'Страницы поиска по услуге',
    'admin/content/metro' => 'Страница поиска по станции метро',
    'admin/content/district' => 'Страница поиска по округу',
    'admin/content/article'  => 'Статьи'
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