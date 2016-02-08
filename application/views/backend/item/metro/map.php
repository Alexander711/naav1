<?php
defined('SYSPATH') or die('No direct script access.');
$default_pos_top = 0;
$default_pos_left = 0;
$metro_stations_names = array();
$metro_stations = array();
$metro->reset(FALSE);
foreach ($metro->order_by('name', 'ASC')->find_all() as $m)
{
    $metro_stations_names[] = $m->name;
    $metro_stations[] = $m;
}
$metro_stations_typeahead_source = '["'.implode('", "', $metro_stations_names).'"]';

?>
<script type="text/javascript">
$(document).ready(function() {
    metro_widget.init();
});
var metro_widget = {
    init: function(){
        $('#metro-stations-gui input, #metro-map-main form').attr('autocomplete', 'off');

        // Once metro station
        $('.metro-station').draggable(
            {
                stop: function(){
                    if ($('#metro-map-controls').is(':hidden'))
                        $('#metro-map-controls').fadeIn();
                    $(this).attr('data-top', $(this).css('top').replace(/[^-\d\.]/g, '')).attr('data-left', $(this).css('left').replace(/[^-\d\.]/g, ''));

                }
            }
        ).resizable(
            {
                stop: function(){
                    if ($('#metro-map-controls').is(':hidden'))
                        $('#metro-map-controls').fadeIn();
                    $(this).attr('data-width', $(this).css('width').replace(/[^-\d\.]/g, '')).attr('data-height', $(this).css('height').replace(/[^-\d\.]/g, ''));
                }
            }
        );
        // GUI
        $('#metro-stations-gui').draggable(
                {
                    handle: '.form',
                    start: function(){
                        $(this).addClass('drag-start');
                    },
                    stop: function(){
                        $(this).removeClass('drag-start');
                    }
                }
            );
        metro_widget.selectMapMode();
        metro_widget.checkUnckecAll();
        metro_widget.updatePositions();
        metro_widget.checkMetroStations();
        metro_widget.selectDragMode();
    },
    appendDraggable: function(metro_id){
        $('.metro-station[data-metro-id="' + metro_id + '"]').draggable(
            {
                stop: function(){
                    if ($('#metro-map-controls').is(':hidden'))
                        $('#metro-map-controls').fadeIn();
                    $(this).attr('data-top', $(this).css('top').replace(/[^-\d\.]/g, '')).attr('data-left', $(this).css('left').replace(/[^-\d\.]/g, ''));
                }
            }
        )
    },
    updatePositions: function(){
        $('#metro-map-controls a').click(function(){

            var metro = [];
            $('.metro-station').each(function(){

                metro.push(
                    {
                        id: $(this).attr('data-metro-id'),
                        main_top: $(this).attr('data-top').replace(/[^-\d\.]/g, ''),
                        main_left: $(this).attr('data-left').replace(/[^-\d\.]/g, ''),
                        main_width: $(this).attr('data-width').replace(/[^-\d\.]/g, ''),
                        main_height: $(this).attr('data-height').replace(/[^-\d\.]/g, ''),
                        marker_top: $(this).find('.marker-icon').attr('data-top').replace(/[^-\d\.]/g, ''),
                        marker_left: $(this).find('.marker-icon').attr('data-left').replace(/[^-\d\.]/g, ''),
                        name_top: $(this).find('.name').attr('data-top').replace(/[^-\d\.]/g, ''),
                        name_left: $(this).find('.name').attr('data-left').replace(/[^-\d\.]/g, '')
                    });
            });
            $.ajax({
                type: 'post',
                url: '/admin/item/metro/ajax_map_update',
                data: {
                    metro_stations: metro
                }
            });
            //console.log(metro);
        });
    },
    checkMetroStations: function(){
        $('#metro-stations-gui .form form').submit(function(e){

            e.preventDefault();
            var name = $('input[name="metro_name"]').val();
            var count = 0;
            $('input[name="metro[]"]').attr('checked', false);
            $('.metro-station').hide();
            $('#metro-stations-gui .alert').empty().hide();
            $('input[name="metro[]"][data-metro-name*="' + name + '"]').each(function(){
                count ++;
                $(this).attr('checked', true);
                $('.metro-station[data-metro-id='+ $(this).val() +']').show();

            });

            //$('.metro-station[data-metro-id=145]').scrollTo();
            if (count > 0)
                $('#metro-stations-gui .alert').html('Найдено станций: ' + count).show();
        });

        $('input[name="metro[]"]').change(function(){
            var id = $(this).val();

            if ($(this).is(':checked')){
                console.log('checked');
                $('.metro-station[data-metro-id='+ id +']').show();
            }
            else{
                console.log('unchecked');
                $('.metro-station[data-metro-id='+ id +']').hide();
            }
        });
    },
    selectDragMode: function(){
        $('button[name="drag_mode[]"]').click(function(){
            var mode = $(this).val();
            var metro_id = $(this).data('metro-id');
            if (mode == 'inline')
            {
                $('.metro-station[data-metro-id="' + metro_id + '"]').draggable('destroy');
                $('.metro-station[data-metro-id="' + metro_id + '"] .marker-icon, .metro-station[data-metro-id="' + metro_id + '"] .name').draggable(
                    {
                        containment: 'parent',
                        stop: function(){
                            $(this).attr('data-top', $(this).css('top').replace(/[^-\d\.]/g, '')).attr('data-left', $(this).css('left').replace(/[^-\d\.]/g, ''));
                        }
                    }
                );
            }
            else if(mode == 'outline')
            {
                $('.metro-station[data-metro-id="' + metro_id + '"] .marker-icon, .metro-station[data-metro-id="' + metro_id + '"] .name').draggable('destroy');
                metro_widget.appendDraggable(metro_id);
            }
        });
    },
    selectMapMode: function(){
        $('button[name="map_mode[]"]').click(function(e){
            e.preventDefault();
            var img_path = $(this).val();
            console.log('url(\'' + img_path + '\')');

            $('#metro-map-main').css('background-image', 'url(\'' + img_path + '\')');
        });
    },
    checkUnckecAll: function(){
        $('.check-all').click(function(e){
            e.preventDefault();
            $('input[name="metro[]"]').attr('checked', true);
            $('.metro-station').show();
        });
        $('.uncheck-all').click(function(e){
            e.preventDefault();
            $('input[name="metro[]"]').attr('checked', false);
            $('.metro-station').hide();
        });
    }
}
</script>

<div id="metro-map-controls">
    <a href="#" class="btn btn-large btn-success">Сохранить карту</a>
</div>
<div id="metro-stations-gui">

        <div class="form well">
            <form action="#">
                <input type="text" name="metro_name" data-source='<?= $metro_stations_typeahead_source ?>' data-provide="typeahead" placeholder="Название станции"/>
                <a href="#" class="check-all">Показать все</a> | <a href="#" class="uncheck-all">Скрыть все</a>
            </form>
            <div class="btn-group" data-toggle="buttons-radio">
                <button name="map_mode[]" value="<?= $city->img_metro_map_clear; ?>" class="btn btn-small active">Чистовая версия</button>
                <button name="map_mode[]" value="<?= $city->img_metro_map; ?>" class="btn btn-small">Оригинал (С надписями)</button>
            </div>
            <div class="alert alert-success" style="display: none;"></div>
        </div>
        <div class="list">
            <div class="controls">
                <?php foreach ($metro_stations as $m): ?>
                    <div class="item" style="position: relative; height: 30px;">
                        <label class="checkbox" style="width: 150px; position: absolute;">
                            <?= FORM::checkbox('metro[]', $m->id, FALSE, array('data-metro-name' => $m->name)); ?>
                            <?= $m->name; ?>
                        </label>
                        <div class="btn-group" data-toggle="buttons-radio" style="position: absolute; right: 0px;">
                            <button name="drag_mode[]" data-metro-id="<?= $m->id ?>" class="btn btn-small active" value="outline">F</button>
                            <button name="drag_mode[]" data-metro-id="<?= $m->id ?>" class="btn btn-small" value="inline">I</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

</div>


<div id="metro-map-main" style="background: url('<?= $city->img_metro_map_clear; ?>') no-repeat">
    <form action="#">
    <?php foreach ($metro->find_all() as $m): ?>
        <?php
        $attr = array(
            'data-metro-id' => $m->id,
            'data-top'  => $m->main_top,
            'data-left' => $m->main_left,
            'data-width'    => $m->main_width,
            'data-height'   => $m->main_height,
            'class'         => 'metro-station'
        );
        if ($m->main_top == 0 AND $m->main_left == 0)
        {
            $default_pos_top += 20;
            if ($default_pos_top > 750)
            {
                $default_pos_top = 0;
                $default_pos_left += 220;
            }
            $m->main_top = $default_pos_top;
            $m->main_left = $default_pos_left;
        }

        $attr['style'] = 'left: '.$m->main_left.'px; top: '.$m->main_top.'px; ';
        if ($m->main_width != 0 AND $m->main_height != 0)
            $attr['style'] .= 'width: '.$m->main_width.'px; height: '.$m->main_height.'px';

        $marker_attrs = array(
            'class' => 'marker-icon',
            'data-top' => $m->marker_top,
            'data-left' => $m->marker_left
        );
        if ($m->marker_top != 0 OR $m->marker_left != 0)
            $marker_attrs['style'] = 'top: '.$m->marker_top.'px; left: '.$m->marker_left.'px';
        $name_attrs = array(
            'class' => 'name',
            'data-top' => $m->name_top,
            'data-left' => $m->name_left
        );
        if ($m->name_top != 0 OR $m->name_left != 0)
            $name_attrs['style'] = 'top: '.$m->name_top.'px; left: '.$m->name_left.'px';

        ?>
        <div <?= HTML::attributes($attr); ?>>
            <div <?= HTML::attributes($marker_attrs); ?>></div>
            <div <?= HTML::attributes($name_attrs); ?> ><a href="#"><?= $m->name; ?></a></div>
        </div>
    <?php endforeach; ?>
    </form>
</div>
