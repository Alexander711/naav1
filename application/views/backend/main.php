<?php
defined('SYSPATH') or die('No direct script access.');
?>

<div class="row">
    <div class="span8">
        <h5>Последние созданные компании</h5>
        <table>
            <tbody>
            <?php foreach ($service->find_all() as $s): ?>
                <?php
                    $type = ($s->type == 1) ? 'Автосервис' : 'Магазин автозапчастей';
                ?>
            <tr>
                <td><?= $s->orgtype->name.' &laquo;'.$s->name.'&raquo;'; ?></td>
                <td style="width: 140px;"><?= MyDate::show($s->date_create, TRUE); ?></td>
                <td style="width: 85px;"><?= HTML::edit_button($s->id, 'admin/services').' '.HTML::add_notice($s->id, 'service'); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php unset($s); ?>
            </tbody>
        </table>
    </div>
    <div class="span8">
        <div class="span8">
            <h5>Последние акции компаний</h5>
            <table>
                <tbody>
                <?php foreach ($stock->find_all() as $s): ?>
                    <tr>
                        <td><?= $s->service->orgtype->name.' &laquo;'.$s->service->name.'&raquo;'; ?></td>
                        <td><?= MyDate::show($s->date, TRUE); ?></td>
                        <td style="width: 85px;"><?= HTML::view('stocks/'.$s->id).' '.HTML::edit_button($s->id, 'admin/service/stock'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php unset($s); ?>
                </tbody>
            </table>
        </div>
        <div class="span8">
            <h5>Последние новости компаний</h5>
            <table>
                <tbody>
                <?php foreach ($news->find_all() as $n): ?>
                    <tr>
                        <td><?= $n->service->orgtype->name.' &laquo;'.$n->service->name.'&raquo;'; ?></td>
                        <td><?= MyDate::show($n->date_create, TRUE); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>