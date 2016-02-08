<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="clearfix" style="margin-bottom: 10px;">
    <?= HTML::anchor('admin/development/task/add', 'Добавить задачу', array('class' => 'btn btn-primary')); ?>
</div>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Заголовок</th>
        <th>Дата создания</th>
        <th>Дополнений</th>
        <th>Приоритет</th>
        <th>Статус</th>
        <th style="width: 165px;"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($task->order_by('status', 'ASC')->order_by('priority', 'DESC')->find_all() as $t): ?>
        <?php
        $disputes_count = count($t->disputes->find_all());
        $choose_statuses = array_diff_key($statuses, array($t->status => ''));
        ?>
        <tr>
            <td><?= $t->title; ?></td>
            <td><?= MyDate::show($t->date_create, TRUE); ?></td>
            <td><?= ($disputes_count == 0) ? 'нет' :  $disputes_count; ?></td>
            <td><?= __('task_priority_'.$t->priority); ?></td>
            <td><span class="<?= $statuses[$t->status]['css']; ?>"><?= __($statuses[$t->status]['i18n']); ?></span></td>
            <td>
                
                <div class="btn-group">
                    <?= HTML::view('admin/development/task/view/'.$t->id).HTML::edit_button($t->id, 'admin/development/task').' '.HTML::delete_button($t->id, 'admin/development/task'); ?>
                    <a href="#" class="btn btn-small dropdown-toggle" data-toggle="dropdown">Статус<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($choose_statuses as $key => $value): ?>
                            <li>
                                <?= HTML::anchor('admin/development/task/choose_status/'.$t->id.'?status='.$key, __($value['i18n'])); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>