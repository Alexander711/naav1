<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Строка таблицы загруженных изображений галереи
 */
?>
<tr class="template-download fade in">
    <td class="preview"><?= HTML::anchor($image->img_path, HTML::image($image->thumb_img_path)); ?></td>
    <td class="system"></td>
    <td class="title">
        <div class="value"><?= HTML::anchor($image->img_path, $image->title) ?></div>
        <div class="form"><?= FORM::textarea('title', $image->title) ?></div>
    </td>
    <!-- Operations -->
    <td class="operations">
        <div class="edit">
            <button class="btn btn-small edit-start">
                <i class="icon-pencil"></i>
                <span>Редактировать</span>
            </button>
            <div class="edit-actions">
                <?= FORM::button(NULL, 'Сохранить', array('style' => 'margin-right: 3px;', 'class' => 'btn btn-small btn-success edit-save', 'data-company-id' => $image->company->id, 'data-image-id' => $image->id))
                  . FORM::button(NULL, 'Отмена', array('class' => 'btn btn-small edit-cancel')); ?>
            </div>
        </div>
        <div class="delete">
            <button class="btn btn-small btn-danger" data-type="DELETE" data-url="/rest/companyimage/index/<?= $image->id.'?company_id='.$image->company->id; ?>">
                <i class="icon-trash icon-white"></i>
            </button>
        </div>
    </td>
</tr>