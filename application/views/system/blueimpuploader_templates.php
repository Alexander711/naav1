<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td>
            <div class="size">{%=o.formatFileSize(file.size)%}</div>
            {% if (file.error) { %}
                <div class="error"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</div>
            {% } else if (o.files.valid && !i) { %}
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            {% } %}
        </td>
        <td class="title">
            <div class="form">
                <label>Заголовок: <textarea name="title" placeholder="Описание изображения"></textarea></label>
            </div>

        </td>
        <!-- Operations -->
        <td class="operations">
            {% if (o.files.valid && !i && !o.options.autoUpload) { %}
                <span class="start">
                    <button class="btn btn-small btn-primary">
                        <i class="icon-upload icon-white"></i>
                        <span>{%=locale.fileupload.start%}</span>
                    </button>
                </span>
            {% } %}
            {% if (!i) { %}
                <span class="cancel">
                    <button class="btn btn-small btn-warning">
                        <i class="icon-ban-circle icon-white"></i>
                        <span>{%=locale.fileupload.cancel%}</span>
                    </button>
                </span>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="system">
                <div class="size">{%=o.formatFileSize(file.size)%}</div>
                <div class="error"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</div>
            </td>
            <td class="title">{%=file.title%}</td>

            <!-- Operations -->
            <td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="system"></td>
            <td class="title">
                <div class="value"><a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.title%}</a></div>
                <div class="form"><textarea name="title">{%=file.title%}</textarea></div>
            </td>
            <!-- Operations -->
            <td>
                <div class="edit">
                    <?= FORM::button(NULL, '<i class="icon-pencil"></i> Редактировать', array('class' => 'btn btn-small edit-start')); ?>
                    <div class="edit-actions">
                        <button class="btn btn-small btn-success edit-save" style="margin-right: 3px;" data-company-id="{%=file.company_id%}" data-image-id="{%=file.image_id%}">Сохранить</button>
                        <button class="btn btn-small edit-cancel">Отмена</button>
                    </div>
                </div>
        {% } %}
            <div class="delete">
                <button class="btn btn-small btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                    <i class="icon-trash icon-white"></i>
                </button>
            </div>
        </td>
    </tr>
{% } %}
</script>