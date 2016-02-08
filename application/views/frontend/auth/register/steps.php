<?php
defined('SYSPATH') or die('No direct script access.');
?>
<div id="reg_steps">
    <ul>
        <li class="first<?php if ($step == 1) echo ' active'; ?>"><?= __('s_reg_1_step'); ?></li>
        <li class="second<?php if ($step == 2) echo ' active'; ?>"><?= __('s_reg_2_step'); ?></li>
        <li class="third<?php if ($step == 3) echo ' active'; ?>"><?= __('s_reg_3_step'); ?></li>
    </ul>
</div>