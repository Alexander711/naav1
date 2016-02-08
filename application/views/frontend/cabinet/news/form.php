<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open($url, array('enctype' => 'multipart/form-data'));
?>
<fieldset>
    <legend><?= __('s_news_params') ?></legend>
    <p>Заголовок</p>
    <?= FORM::input('title', Arr::get($values, 'title'), array('class' => 's_inp', 'style' => 'width: 350px;')); ?>
    <div class="form_error"><?= Message::show_once_error($errors, 'title'); ?></div>
    <p><?= __('s_firm'); ?></p>
    <?= FORM::select('service_id', $services, Arr::get($values, 'service_id')); ?>
    <div style="width: 400px;">
        <div style="float: left;">
            <p>Изображение</p>
            <?= FORM::file('news_image') ?><br />
            <div class="form_error"><?= Message::show_once_error(Arr::get($errors, '_external', array()), 'news_image'); ?></div>
            <strong style="font-weight: normal; font-size: 10px;">Изображение в формате jpg, jpeg, png, gif</strong>
        </div>
        <div style="float: right;">
        <?php
        $image = Arr::get($values, 'image', NULL);
        if ($image AND file_exists($image))
        {
            $pict = explode('.', $image);
            echo HTML::image($pict[0].'_pict.'.$pict[1], array('width' => 100));
        }
        ?>

         
        </div>
    </div>

</fieldset>
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