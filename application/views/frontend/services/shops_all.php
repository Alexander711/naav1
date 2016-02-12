<?php
defined('SYSPATH') or die('No direct script access.');
?>
<div class="serv_list_column">
    <?php $mid_array = ceil(count($result)/2); ?>

    <?php $i=1; foreach ($result as $group): ?>
        <div>
            <?php if (isset($group['sub_group'])) { ?>
                <strong><?= $group['name_group'] ?></strong>
            <?php } else {?>
                <?= HTML::anchor('#', $group['name_group']); ?>
            <?php } ?>
        </div>
        <?php if (isset($group['sub_group'])) { ?>
            <?php foreach ($group['sub_group'] as $sub_group): ?>
                <div>
                    <?= HTML::anchor('#', $sub_group['name']); ?>
                </div>
            <?php endforeach; ?>
        <?php } ?>
        <?php if($i==$mid_array){ ?>
            </div>
            <div class="serv_list_column">
        <?php } ?>
    <?php $i++; endforeach; ?>
    <div style="clear:both"></div>
</div>
