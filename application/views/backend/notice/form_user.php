<?php
defined('SYSPATH') or die('No direct script access.');
$selected_users = Arr::get($values, 'user', array());
echo FORM::open($url, array('class' => 'form-horizontal'));
?>
<?php if (Request::current()->action() == 'edit'): ?>
        <p style="font-size: 14px; font-weight: bold;">Внимание, при редактировании уведомление будет иметь статус непрочитанного у тех кто прочел</p>
<?php endif ?>
<fieldset style="width: 450px">
    <legend>Параметры уведомления</legend>
    <div class="control-group">
        <label class="control-label">Кому</label>
        <div class="controls">
            <select id="users" multiple="multiple" name="user[]" style="width: 400px;">
                <?php foreach ($user->find_all() as $u): ?>
                    <?php
                    $selected = '';
                    if (in_array($u->id, $selected_users))
                    {
                        $selected = 'selected = "selected"';
                    }
                    ?>
                    <option value="<?= $u->id ?>" <?= $selected ?>><?= $u->username; ?></option>
                <?php endforeach; ?>
            </select>
            <p class="help-block">Кому адресовано уведомление  (Не выбрав никого отправиться всем)</p>
            <div id="selected_users"></div>
        </div>
    </div>
    <div class="clearfix">
        <label>&nbsp;</label>
        <div class="input"></div>
    </div>
    <div class="control-group">
        <label class="control-label" for="title">Заголовок</label>
        <div class="controls">
            <?= FORM::input('title', Arr::get($values, 'title'), array('id' => 'title', 'class' => 'span7')); ?>
        </div>
    </div>
    <?php if (isset($mode) AND $mode == 'edit'): ?>
        <div class="control-group">
            <label class="control-label">Прочее</label>
            <div class="controls">
                <label>
                    <?= FORM::checkbox('save_status', 1, (bool) Arr::get($values, 'save_status', TRUE)) ?>
                    Сохранить статусы прочитан/непрочитан для уже отправленных
                </label>
                <p class="help-block">Если убрать галочку уведомление будет иметь статус непрочитан у всех<br />Всем будут по новой отправленна рассылка на почту</p>
            </div>
        </div>
    <?php endif; ?>
</fieldset>
<h3>Текст уведомления</h3>
<div class="clearfix">
    <?= FORM::textarea('text', Arr::get($values, 'text'), array('id' => 'text')); ?>
    <?php if (Arr::get($errors, 'text', NULL)): ?>
        <p class="help-block"><?= $errors['text']; ?></p>
    <?php endif; ?>
</div>
<?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn btn-large btn-success')); ?>
<?= FORM::close(); ?>