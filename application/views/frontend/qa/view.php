<?php
defined('SYSPATH') or die('No direct script access.');
?>

<div class="qa_info">
    <h1><?= 'Запрос на '.$qa->carbrand->name.' '.$qa->model->name.' '.$qa->volume.' '.$qa->gearbox->name.' '.$qa->year; ?></h1>
    <table>
        <tr>
            <td class="title">Дата запроса:</td>
            <td><?= MyDate::show($qa->date); ?></td>
        </tr>
        <tr>
            <td class="title">Конт. лицо:</td>
            <td><?= $qa->contact; ?></td>
        </tr>
        <?php if ($qa->vin != ''): ?>
            <tr>
                <td class="title">VIN:</td>
                <td><?= $qa->vin; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td class="title">Запрос для:</td>
            <td><?= ($qa->for_service_has_car == 1) ? 'Сервисов предоставляющих ремонт указанного авто' : 'Для любых сервисов'; ?></td>
        </tr>
        <tr>
            <td class="title">Услуги</td>
            <td>
                <?php if (!empty($works)): ?>
                    <?= implode(', ', $works); ?>
                <?php else: ?>
                    Не выбрано ни одной услуги
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<div class="qa_text">
    <p class="title">Текст сообщения</p>
    <?= $qa->text; ?>
</div>
<h1 style="font-size: 18px; color: #969696; margin: 10px 4px;">Ответы автосервисов на запрос (<?= count($qa->answers->find_all()); ?>)</h1>
<?php if (count($qa->answers->find_all()) > 0): ?>
    <div class="qa_answers">
        <ul>
            <?php foreach ($qa->answers->order_by('date', 'DESC')->find_all() as $answer): ?>
                <li>
                    <table>
                        <tr class="black">
                            <td class="title" style="width: 80px">Автосервис:</td>
                            <td style="width: 150px;"><?= HTML::anchor('services/'.$answer->service->id, $answer->service->name); ?></td>
                            <td class="title" style="width: 50px;">Телефон:</td>
                            <td><?= (trim($answer->service->code)) ? '+7('.$answer->service->code.')'.$answer->service->phone : $answer->service->phone; ?></td>
                        </tr>
                    </table>
                    <div class="text"><?= $answer->text; ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <p>На запрос пока никто не ответил...</p>
<?php endif; ?>