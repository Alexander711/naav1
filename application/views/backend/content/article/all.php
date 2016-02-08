<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/content')
         ->set('current_url', 'admin/content/article');

?>
<p><?= HTML::anchor('admin/content/article/add', 'Добавить статью', array('class' => 'btn btn-primary')); ?></p>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Заголовок</th>
        <th>Дата создания/редактирования</th>
        <th>Статус</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($article->find_all() as $a): ?>
        <?php
        if ($a->date_edited == '0000-00-00 00:00:00')
            $a->date_edited = 'ни разу';
        else
            $a->date_edited = MyDate::show($a->date_edited, TRUE);
        ?>
        <tr>
            <td><?= $a->title; ?></td>
            <td><?= MyDate::show($a->date_create, TRUE).' / '.$a->date_edited; ?></td>
            <td><?= HTML::activate_checker($a->id, $a->active, 'admin/content/article'); ?></td>
            <td>
                <div class="btn-group">
                    <?= HTML::edit_button($a->id, 'admin/content/article'); ?>
                    <?= HTML::delete_button($a->id, 'admin/content/article'); ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>