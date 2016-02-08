<?php
defined('SYSPATH') or die('No direct script access.');
?>
<div class="serv_list_column">
    <?php $mid_array = ceil(count($result)/2); ?>

    <?php $i=1; foreach ($result as $group): ?>
        <div>
            <h1><?= $group['name_group'] ?></h1>
        </div>
        <?php if (isset($group['services'])) { ?>
            <?php foreach ($group['services'] as $key=>$services): ?>
                <div>
                    <?= HTML::anchor('shops/'.$key, $services); ?>
                </div>
            <?php endforeach; ?>
        <?php } ?>
        <?php if (isset($group['sub_group'])) { ?>
            <?php foreach ($group['sub_group'] as $sub_group): ?>
                <div>
                    <strong><?= $sub_group['name'] ?></strong>
                </div>
                <?php if (isset($sub_group['services'])) { ?>
                    <?php foreach ($sub_group['services'] as $key=>$services): ?>
                        <div>
                            <?= HTML::anchor('shops/'.$key, $services); ?>
                        </div>
                    <?php endforeach; ?>
                <?php } ?>
            <?php endforeach; ?>
        <?php } ?>
        <?php if($i==$mid_array){ ?>
            </div>
            <div class="serv_list_column">
        <?php } ?>
    <?php $i++; endforeach; ?>
    <div style="clear:both"></div>
</div>
