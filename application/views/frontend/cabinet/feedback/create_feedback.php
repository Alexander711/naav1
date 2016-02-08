<?php
defined('SYSPATH') or die('No direct script access.');
?>
<h1 style="margin-bottom: 20px">Обратная связь</h1>
<?= FORM::open('cabinet/feedback'); ?>
<fieldset>
    <legend>Заголовок запроса</legend>
    <?= FORM::input('title', Arr::get($values, 'title'), array('class' => 's_inp', 'style' => 'width: 400px')); ?>
    <div class="form_error"><?= Message::show_once_error($errors, 'title'); ?></div>
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