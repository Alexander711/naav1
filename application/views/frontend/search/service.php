<?php
defined('SYSPATH') or die('No direct script access.');
if (isset($service))
    $service_count = count($service->find_all());
?>

<?php if (isset($service) AND $service AND $service_count > 0): ?>
    <p style="font-size: 20px; color: #8d8d8d; margin: 10px 0 0;">Найдено автосервисов <?= $service_count; ?></p>

    <?php foreach ($service->find_all() as $s): ?>
        <div class="content-group">
            <div class="header">
                <div class="title"><?= HTML::anchor('services/'.$s->id, $s->name); ?></div>
                <?= $service->get_words($s); ?>
            </div>
            <div class="body">
                <div class="address"><?= $s->get_address(); ?></div>
                <div class="text">
                    <?= Text::limit_words(strip_tags($s->about), 40); ?>
                    <?= HTML::anchor('services/'.$s->id, 'Подробнее'); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php elseif ((isset($service) AND $service === FALSE) OR (isset($service_count) AND $service_count < 1)): ?>
<p style="font-size: 20px; color: #8d8d8d; margin: 10px 0 0;">Найдено автосервисов 0</p>
<?php endif; ?>
