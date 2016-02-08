<?php
defined('SYSPATH') or die('No direct script access.'); ?>

<div class="main_wrapper">
    <div class="cabinet_menu">
        <ul>
            <li><?= HTML::anchor('cabinet/profile', __('cb_profile')) ?></li>
            <li><?= HTML::anchor('cabinet/company', __('cb_companies')) ?></li>
            <li><?= HTML::anchor('cabinet/profile', __('cb_questions')) ?></li>
            <li><?= HTML::anchor('cabinet/profile', __('cb_vacancies')) ?></li>
            <li><?= HTML::anchor('cabinet/profile', __('cb_stocks')) ?></li>
            <li><?= HTML::anchor('cabinet/profile', __('cb_adv')) ?></li>
            <li><?= HTML::anchor('cabinet/profile', __('cb_feedback')) ?></li>
        </ul>
    </div>
    <?= $content; ?>
</div>