<?php
defined('SYSPATH') or die('No direct script access.');
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
    <title>Авторизация</title>
</head>
<body>
    <div style="width: 300px; margin: 300px auto 0;">
    <?= FORM::open('admin/login'); ?>
    <table>
        <tr>
            <td><?= __('f_username'); ?></td>
            <td><?= FORM::input('username'); ?></td>
        </tr>
        <tr>
            <td><?= __('f_password'); ?></td>
            <td><?= FORM::password('password'); ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?= FORM::submit(NULL, 'Войти'); ?></td>
        </tr>
    </table>
    <?= FORM::close(); ?>
    </div>
</body>
</html>