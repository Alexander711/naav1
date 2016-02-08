<?php
defined('SYSPATH') or die('No direct script access.');
?>
<h1 style="margin-bottom: 20px">Заполнение заявки на размещение рекламы</h1>
<?= FORM::open('cabinet/adv'); ?>
<fieldset>
    <legend>Заголовок заявки</legend>
    <?= FORM::input('title', Arr::get($values, 'title'), array('class' => 's_inp', 'style' => 'width: 400px')); ?>
    <div class="form_error"><?= Message::show_once_error($errors, 'title'); ?></div>
</fieldset>
<fieldset>
    <legend>Компания</legend>
    <?= FORM::select('service_id', $services, Arr::get($values, 'service_id')); ?>
    <div class="form_error"><?= Message::show_once_error($errors, 'service_id'); ?></div>
</fieldset>
<div class="form_error"><?= Message::show_once_error($errors, 'text'); ?></div>
<fieldset>
    <legend>Текст</legend>
    <?= FORM::textarea('text', Arr::get($values, 'text')); ?>
</fieldset>
<?= FORM::submit(NULL, 'Отправить', array('class' => 's_button')); ?>
<?= FORM::close(); ?>
<script type="text/javascript">
CKEDITOR.replace('text',
{
filebrowserImageUploadUrl : '<?php echo URL::base() . 'ajax/upload_image'; ?>',
removePlugins : 'maximize,resize',
toolbar : 'add_comment_toolbar'
});
</script>