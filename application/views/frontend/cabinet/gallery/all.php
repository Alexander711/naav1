<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<?php if (count($user->services->find_all()) < 1 ): ?>
    <div class="alert alert-error">
        У вас нет компаний. Сперва <?= HTML::anchor('cabinet/company/add', 'добавьте компанию') ?>
    </div>
<?php else: ?>
    
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th style="width: 250px;">Автосервис/Магазин автозапчастей</th>
            <th>Изображения</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($user->services->find_all() as $service): ?>
            <tr>
                <td style="vertical-align: middle; font-weight: bold;"><?= HTML::anchor(Model_Service::$type_urls[$service->type].'/'.$service->id, $service->name); ?></td>
                <td>
                    <?php if (count($service->images->find_all()) < 1): ?>
                        <?= HTML::image('assets/img/icons/gallery_64.png'); ?> <span style="position: relative; bottom:25px; font-weight: bold;">Галерея <?= $service->name; ?> пуста, <?= HTML::anchor('cabinet/company/gallery/'.$service->id, 'загрузить изображения') ?></span>
                    <?php else: ?>
                        <?php foreach ($service->images->find_all() as $i): ?>
                            <a class="gallery" title="<?= (trim($i->title)) ? $i->title : $i->name; ?>" href="/<?= $i->img_path; ?>"><?= HTML::image($i->thumb_img_path); ?></a>
                        <?php endforeach; ?>
                        <div><?= HTML::anchor('cabinet/company/gallery/'.$service->id, HTML::image('assets/img/icons/gallery_add.png').' <span style="font-weight: bold; position: relative; bottom: 14px;">Загрузить изображения</span>', array('style' => 'text-decoration: none;')) ?></div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>