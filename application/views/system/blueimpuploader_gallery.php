<?php
defined('SYSPATH') or die('No direct script access.'); ?>

<form id="fileupload" action="/rest/companyimage" method="POST" enctype="multipart/form-data">
    <?= FORM::hidden('company_id', $service->id);?>
    <div class="fileupload-buttonbar">
        <a class="btn btn-success fileinput-button">
            <i class="icon-plus icon-white"></i>
            <span>Добавить изображение</span>
            <input type="file" name="files[]" multiple>
        </a>
        <button type="submit" class="btn btn-primary start">
            <i class="icon-upload icon-white"></i>
            Загрузить
        </button>
        <button type="reset" class="btn btn-warning cancel">Отмена</button>
    </div>

    <table role="presentation" class="table table-striped">
        <thead>
        <tr>
            <th>Превью</th>
            <th></th>
            <th>Заголовок</th>
            <th>Операции</th>
        </tr>
        </thead>
        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
            <?php
            foreach ($service->images->order_by('date_created', 'DESC')->find_all() as $image)
                echo View::factory('system/blueimpuploader_image_tr')
                         ->set('image', $image);
            ?>
        </tbody>
    </table>
</form>
<?= View::factory('system/blueimpuploader_templates') ?>

