<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<table class="cab_c_table">
    <tr class="title">
        <td>От</td>
        <td>Марка автомобиля</td>
        <td>Vin</td>
        <td>Дата</td>
        <td></td>
    </tr>

<?php foreach ($question->where('active', '=', 1)->find_all() as $q): ?>
    <tr>
        <td><?= $q->contact; ?></td>
        <td><?= $q->carbrand->name.' '.$q->model->name; ?></td>
        <td><?= $q->vin ?></td>
        <td><?= $q->date ?></td>
        <td><?= HTML::anchor('cabinet/qa/view/'.$q->id, HTML::image('assets/img/icons/qa_create_answer.png')); ?></td>
    </tr>
        
<?php endforeach; ?>
    <tr class="title">
        <td>От</td>
        <td>Марка автомобиля</td>
        <td>Vin</td>
        <td>Дата</td>
        <td></td>
    </tr>
</table>