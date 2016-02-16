<?php
defined('SYSPATH') or die('No direct script access.');
?>
<div class="serv_list_column">
   <ul>
    <?php $i=1; foreach ($result as $group): ?>
        <li class="group">
            <?php if (isset($group['sub_group'])) { ?>
                <h2><?= $group['name_group'] ?></h2><ul>
            <?php } else {?>
                <?= HTML::anchor('#', $group['name_group']); ?>
            <?php } ?>
        </li>
        <?php if (isset($group['sub_group'])) { ?>
            <?php foreach ($group['sub_group'] as $sub_group): ?>
                <li>
                    <?= HTML::anchor('#', $sub_group['name']); ?>
                </li>
            <?php endforeach; ?></ul>
        <?php } ?>
        <?php if($i==7){ ?>
        </ul>
            </div>
            <div class="serv_list_column">
            <ul>
        <?php } ?>
    <?php $i++; endforeach; ?>
    <div style="clear:both"></div>
    </ul>
</div>
