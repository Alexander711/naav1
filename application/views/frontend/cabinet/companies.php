<?php
defined('SYSPATH') or die('No direct script access.');
?>
<div class="add_item"><?= HTML::anchor('cabinet/company/add', 'Добавить фирму '.HTML::image('assets/img/icons/c_add_item.png')); ?></div>
<?php if (count($result) > 0): ?>
    <table class="cab_c_table">
        <tr class="title">
            <td>Название</td>
            <td>Тип компании</td>
            <td>Новостей</td>
            <td>Акций</td>
            <td>Вакансий</td>
            <td>Отзывов</td>
            <td></td>
        </tr>
        <?php foreach ($result as $value): ?>
            <tr>
                <td><?= $value['service_name']; ?></td>
                <td>
                    <?php if(isset($value['group_name'])){ ?>
                        <?= $value['group_name'] ?>
                    <?php } ?>
                    <?php if(isset($value['sub_group_name'])){ ?>
                        (<?= $value['sub_group_name'] ?>)
                    <?php } ?>
                </td>
                <td>
                    <?php
                    if ($value['count_news'] > 0)
                    {
                        echo $value['count_news'].' '.HTML::anchor('cabinet/news', HTML::image('assets/img/icons/c_more.png'));
                    }
                    else
                    {
                        echo 0;
                    }
                    ?>
                    <?= HTML::anchor('cabinet/news/add', HTML::image('assets/img/icons/c_add.png')); ?>
                </td>
                <td>
                    <?php
                    if ($value['count_stocks'] > 0)
                    {
                        echo $value['count_stocks'].' '.HTML::anchor('cabinet/stock', HTML::image('assets/img/icons/c_more.png'));
                    }
                    else
                    {
                        echo 0;
                    }
                    ?>
                    <?= HTML::anchor('cabinet/stock/add', HTML::image('assets/img/icons/c_add.png')); ?>
                </td>
                <td>
                    <?php
                    if ($value['count_vacancies'] > 0)
                    {
                        echo $value['count_vacancies'].' '.HTML::anchor('cabinet/vacancy', HTML::image('assets/img/icons/c_more.png'));
                    }
                    else
                    {
                        echo 0;
                    }
                    ?>
                    <?= HTML::anchor('cabinet/vacancy/add', HTML::image('assets/img/icons/c_add.png')); ?>
                </td>
                <td>
                    <?php
                    if ($value['count_reviews'] > 0)
                    {
                        echo $value['count_reviews'].' '.HTML::anchor('cabinet/review', HTML::image('assets/img/icons/c_more.png'));
                    }
                    else
                    {
                        echo 0;
                    }
                    ?>
        
                </td>
                <td><?= HTML::anchor('cabinet/company/edit/'.$value['id_service'], HTML::image('assets/img/icons/c_edit.png')); ?> <?= HTML::anchor('cabinet/company/delete/'.$value['id_service'], HTML::image('assets/img/icons/c_delete.png')); ?></td>
            </tr>

        <?php endforeach; ?>
        <tr class="title">
            <td>Название</td>
            <td>Тип компании</td>
            <td>Новостей</td>
            <td>Акций</td>
            <td>Вакансий</td>
            <td>Отзывов</td>
            <td></td>
        </tr>
    </table>
<?php endif; ?>
