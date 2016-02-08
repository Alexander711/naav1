<?php
defined('SYSPATH') or die('No direct script access.');
?>
<h1 style="margin-bottom: 20px">Уведомления от администраци</h1>
<?php foreach ($notice as $id => $value): ?>
    <div class="content-group">
        <div class="header with-date">
            <div class="title" style="margin-bottom: 10px;">Сообщение для: <?= (isset($value['for']) AND is_array($value['for'])) ? implode(', ', $value['for']) : 'пользователя'; ?></div>
            <div class="date" style="margin-bottom: 10px;">
                <?php
                if ($value['read'] == 'n')
                {
                    echo '<strong style="background: #e5635f; color: #FFF; padding: 5px; margin-right: 5px;">Новое уведомление</strong>';
                }
                echo MyDate::show($value['date']);
                ?>
            </div>
        </div>

        <div class="body">
            <div class="text">
                <p style="font-size: 14px; font-weight: bold;"><?= $value['title']; ?></p>
                <?= str_replace('Login', $username, $value['text']); ?>
            </div>
        </div>

    </div>
<?php endforeach; ?>
