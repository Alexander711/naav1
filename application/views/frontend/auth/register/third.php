<?php
defined('SYSPATH') or die('No direct script access.');
$selected_works = Arr::get($values, 'work', array());
$selected_cars = Arr::get($values, 'model', array());
//$values['work'][] = 24;
//$values['work'][] = 25;
echo $reg_steps;
echo Message::render();
echo FORM::open('registration/third');
$discounts = array('0' => 'нет') + $discounts;
?>
<div class="st_form">
    <ul>
        <?php
        $css_auto_works = '';
        if ($type == 2)
        {
            $css_auto_works = 'style="display: none;"';
        }
        ?>
        <li class="auto_models">
            <label for="auto_models" class="lab"><?= __('f_auto_models'); ?></label>
            <select id="auto_models" multiple="multiple" name="model[]" style="width: 400px;">
            <?php foreach ($auto_models as $id => $name): ?>
            <?php
            $selected = '';
            if (in_array($id, $selected_cars))
            {
                $selected = 'selected = "selected"';
            }
            ?>
                <option value="<?= $id ?>" <?= $selected ?>><?= $name; ?></option>
            <?php endforeach; ?>
            </select>

            <div id="selected_models"></div>
        </li>
        <li class="works" <?= $css_auto_works ?>>
            <label for="works" class="lab"><?= __('f_works'); ?></label>
            <select id="works" multiple="multiple" name="work[]" style="width: 400px;">
                <?php foreach ($work_category->find_all() as $category): ?>
                    <optgroup label="<?= $category->name; ?>">
                        <?php foreach ($category->works->find_all() as $work): ?>
                        <?php
                        $selected = '';
                        if (in_array($work->id, $selected_works))
                        {
                            $selected = 'selected = "selected"';
                        }
                        ?>
                            <option value="<?= $work->id ?>" <?= $selected ?>><?= $work->name; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
            <div id="selected_works"></div>
        </li>
        <li>
            <label for="about" class="lab"><?= __('f_about_service') ?></label>
            <?= FORM::textarea('about', Arr::get($values, 'about'), array('class' => 's_tarea', 'id' => 'about', 'style' => 'width: 400px;')) ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'about'); ?></div>
        </li>
        <li>
            <label for="work_times" class="lab"><?= __('f_work_time') ?></label>
            <?= FORM::input('work_times', Arr::get($values, 'work_times'), array('class' => 's_inp', 'id' => 'work_times')) ?>
        </li>
        <li>
            <label class="lab"><?= __('f_discount') ?></label>
            <?= FORM::select('discount_id', $discounts, Arr::get($values, 'discount_id'), array('id' => 'discount')); ?>
        </li>
        <?php
        $discount =Arr::get($values, 'discount_id', 0);
        $coupon_form_style = 'display: none';
        if ($discount != 0)
        {
            $coupon_form_style = 'display: block';
        }

        ?>
        <li class="coupon_text" style="<?= $coupon_form_style; ?>">
            <label class="lab"><?= __('f_coupon_text'); ?></label>
            <?= FORM::textarea('coupon_text', Arr::get($values, 'coupon_text'), array('class' => 's_tarea', 'style' => 'width: 400px; height: 100px;')); ?>
        </li>

        <li>
            <label class="lab">&nbsp;</label>
            <?= FORM::submit('submit', __('f_complete'), array('class' => 's_button')); ?> <?= FORM::submit('skip', __('f_skip'), array('class' => 's_skip')); ?>
        </li>
    </ul>
</div>
<?= FORM::close(); ?>

