<?php
defined('SYSPATH') or die('No direct script access.'); ?>

<?= Message::render(); ?>
<p><?= HTML::anchor('admin/item/carbrand/add', 'Добавить марку авто', array('class' => 'btn btn-primary')); ?></p>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Название (eng)</th>
            <th>Название (rus)</th>
            <th>Кол-во сервисов <acronym title="количество автосервисов предоставляющих ремонт данной марки автомобиля">что это</acronym></th>
            <th>Дата редактирования <acronym title="Дата редактирования страницы поиска по данной марке авто">что это</acronym></th>
            <th style="width: 280px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($car->find_all() as $c): ?>
        <tr>
            <td><?= $c->name; ?></td>
            <td><?= $c->name_ru; ?></td>
            <td>
                <?php
                $services = $c->services->find_all();
                if (count($services) > 0)
                {
                    $content = '<ul class="item_services_list">';
                    foreach ($services as $service)
                    {
                        $content .= '<li>'.$service->name."</li>";
                    }
                    $content .= '</ul>';
                    echo '<div class="services_car_'.$c->id.'" style="display: none">'.$content.'</div>';
                    echo View::factory('backend/blocks/modal')
                             ->set('modal_id', 'services_modal_'.$c->id)
                             ->set('content', $content);
                    echo '<a href="#services_modal_'.$c->id.'" data-toggle="modal" rel="modal">'.count($services).'</a>';
                }
                else
                {
                    echo 0;
                }
                ?>

            </td>
            <td>
                <div class="btn-group">
                    <a href="#" class="btn dropdown-toggle" data-toggle="dropdown">Даты редактирования <i class="caret"></i></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($c->contents->find_all() as $page): ?>
                            <li><?= HTML::anchor('#'.$page->id, ($page->date_edited != '0000-00-00 00:00:00') ? Date::full_date($page->date_edited, TRUE) : 'ни разу', array('title' => $page->city->name)); ?></li>
                        <?php endforeach;?>
                    </ul>

                </div>
            </td>
            <td>
                <div class="btn-group">
                    <?= HTML::anchor('admin/item/carbrand/edit/'.$c->id, '<i class="icon-pencil"></i>', array('class' => 'btn', 'style' => 'z-index: 1;')).HTML::anchor('admin/item/carbrand/delete/'.$c->id, '<i class="icon-remove"></i>', array('class' => 'btn', 'style' => 'z-index: 1;')) ?>
                    <div class="btn-group">

                        <a href="#" class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-pencil"></i>Редактировать страницу <i class="caret"></i></a>
                        <ul class="dropdown-menu" style="left: 70px;">
                            <?php foreach ($c->contents->find_all() as $page): ?>
                                <li><?= HTML::anchor('admin/content/cars/edit/'.$page->id, $page->city->name); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

