<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open(Request::current()->url());
?>
<script type="text/javascript">
    $(document).ready(function() {

        $('form').attr('autocomplete', 'off');
        $('.send-notice').change(function(){
           if ($(this).is(':checked')){
               $('.p-cause').removeClass('disabled');
               $('.delete-cause').attr('disabled', false);
           }
           else{
               $('.p-cause').addClass('disabled');
               $('.delete-cause').attr('disabled', true);
           }
        });
    });
</script>
<style type="text/css">
    .p-cause.disabled
    {
        color: #7f7f7f;
    }
</style>
<div class="alert alert-block alert-error fade in">
    <?= HTML::anchor('admin/services', '&times;', array('class' => 'close')); ?>
    <h4 class="alert-heading">Удаление компании <?= $full_name; ?></h4>
    <p>Вы действительно хотите удалить компанию <?= $full_name; ?> со всеми его параметрами (акции, новости, вакансии, отзывы)?</p>
    <p>
        <label>
            <?= FORM::checkbox('send_notice', 1, FALSE, array('class' => 'send-notice', 'style' => 'display: inline;')); ?> Отправить уведомление о удалении
        </label>
    </p>
    <p class="p-cause disabled">
        <strong>Причина удаления:</strong> <br />
        <span style="font-size: 12px;">если не вписать причину, отправится то что указано по умолчанию</span><br />
        <?= FORM::textarea('delete_cause', NULL, array('class' => 'delete-cause disabled span5', 'placeholder' => $company_deleting_cause, 'disabled' => 'disabled', 'cols' => 60)); ?>
    </p>
    <p>
        <?= FORM::submit('submit', 'Удалить', array('class' => 'btn btn-danger small')).' '.FORM::submit('cancel', 'Отмена', array('class' => 'btn small')); ?>
    </p>
</div>
<?= FORM::close(); ?>