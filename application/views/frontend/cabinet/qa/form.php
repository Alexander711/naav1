<?php
defined('SYSPATH') or die('No direct script access.');
?>
<div class="question">
    <div class="title"><?= $question->carbrand->name.' '.$question->model->name.' '.$question->volume.' '.$question->gearbox->name.' '.$question->year; ?></div>
    <div class="info">
        <table>
            <tr>
                <td>Дата запроса:</td>
                <td><?= $question->date; ?></td>
            </tr>
            <tr>
                <td>Конт. лицо:</td>
                <td><?= $question->contact; ?> / <?= $question->email; ?></td>
            </tr>
	        <tr>
                <td>Телефон:</td>
                <td><?= $question->phone; ?></td>
            </tr>
            <?php if (trim($question->vin) != ''): ?>
                <tr>
                    <td>VIN:</td>
                    <td><?= $question->vin; ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td>Услуги:</td>
                <td><?= implode(', ', $works); ?></td>
            </tr>
        </table>
    </div>
    <div class="text"><?= $question->text; ?></div>
</div>
<?= FORM::open($url); ?>
<fieldset>
    <legend><?= __('s_create_answer') ?></legend>
    <p><?= __('s_firm'); ?></p>
    <?= FORM::select('service_id', $services, Arr::get($values, 'service_id')); ?>
    <p>Сообщение</p>
    <div class="form_error"><?= Message::show_once_error($errors, 'text'); ?></div>
    <?= FORM::textarea('text', Arr::get($values, 'text')); ?>
</fieldset>
<?= FORM::submit(NULL, 'Ответить', array('class' => 's_button')); ?>
<?= FORM::close(); ?>
<script type="text/javascript">
CKEDITOR.replace('text',
{
filebrowserImageUploadUrl : '<?php echo URL::base() . 'ajax/upload_image'; ?>',
removePlugins : 'maximize,resize',
toolbar : 'add_comment_toolbar'
});
</script>