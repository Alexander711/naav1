<?php
defined('SYSPATH') or die('No direct script access.');
if (isset($notice_count['new']))
{
    //echo $notice_count['new'];
}
?>
<?php if ($expires) {?>

<div class="alert alert-info" style="display: inline-block;float:left;">
	Аккаунт действителен до: <strong><?=$expires?></strong>
</div>
<?php } ?>

<div class="cabinet_rss">
    <?= HTML::anchor('rss', HTML::image('assets/img/icons/rss.png').__('a_rss_cabinet')); ?>
</div>
<div style="clear: both;"><?= Message::render(); ?></div>
<div class="cabinet_menu">
    <ul>
        <?php foreach ($menu_items as $controller => $menu): ?>
            <?php $css = array(); ?>
            <?php if (Request::current()->controller() == $controller): ?>
                <?php $css = array('class' => 'active'); ?>
            <?php endif; ?>
            <li><?= HTML::image($menu['icon']); ?><?= HTML::anchor($menu['url'], $menu['title'], $css); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="cabinet_content">
    <?= $content; ?>
</div>
<div class="clear"></div>