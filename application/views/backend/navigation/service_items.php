<?php
defined('SYSPATH') or die('No direct script access.');
$urls = array(
    'admin/service/stock' => 'Акции',
    'admin/service/vacancy' => 'Вакансии',
    'admin/service/review' => 'Отзывы'
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