<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open($url);
?>
<fieldset>
    <legend><?= __('s_stock_params'); ?></legend>
    <p><?= __('s_firm'); ?></p>
    <?= FORM::select('service_id', $services, Arr::get($values, 'service_id')); ?>
</fieldset>
    <p>Заголовок</p>
    <?= FORM::input('title', Arr::get($values, 'title'), array('class' => 's_inp', 'style' => 'width: 350px;')); ?>
<div class="form_error"><?= Message::show_once_error($errors, 'text'); ?></div>
<fieldset>
    <legend>Текст</legend>
    <?= FORM::textarea('text', Arr::get($values, 'text')); ?>
</fieldset>
<?= FORM::submit(NULL, 'Опубликовать', array('class' => 's_button')); ?>
<?= FORM::close(); ?>
<script type="text/javascript">
CKEDITOR.replace('text',
{
filebrowserImageUploadUrl : '<?php echo URL::base() . 'ajax/upload_image'; ?>',
removePlugins : 'maximize,resize',
toolbar : 'add_comment_toolbar'
});
</script>