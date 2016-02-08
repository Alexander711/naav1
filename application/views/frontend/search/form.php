<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open('search', array('method' => 'get', 'class' => 'search-form'));
?>

    <?= FORM::input('str', Arr::get($values, 'str')); ?>
    <?= FORM::hidden('search_type', Arr::get($values, 'search_type')); ?>
    <button name="submit" class="submit">Искать  <span class="search-preloader"><img src="/assets/img/search_button_preloader.gif" alt="Искать" /></span></button>

    <div class="tip">Запросы можно вводить через запятую, например Москва, Ауди, Тюнинг</div>
    <div class="form-errors">
        <?php
        foreach ($errors as $error)
            echo '<div class="error">'.$error.'</div>';
        ?>
    </div>

    <div class="result">
        <?= $result; ?>
    </div>
<?= FORM::close(); ?>