$(function () {
    var data_group_1 = '';

    $('#group_id_1').click(function () {
        data_group_1 = $(this).val();
    });

    $('.select_group').live("change", function (event) {
        var element = $(this);
        var number_group = element.data('number_group');

        if (number_group != 1) {
            $('#sub_group_id_' + number_group + ' option').remove();
            $('.div_sub_group_' + number_group).hide();
            $('.er_sub_group_' + number_group).html('');

            add_sub_group(element, number_group);
        } else {

            if ($('#group_id_1').hasClass('first')) {
                $('#sub_group_id_' + number_group + ' option').remove();
                $('.div_sub_group_' + number_group).hide();
                $('.er_sub_group_' + number_group).html('');

                add_sub_group(element, number_group);
            } else {
                if (confirm("Изменение данной группы повлечет сброс остальных групп. Изменить?")) {
                    if (element.val() == '0') {
                        element.removeData('type_group').removeAttr('data-type_group');
                    }

                    $('.add_group').removeAttr('next_group').data('next_group', 2);

                    for (var i = 1; i <= 5; i++) {
                        $('#sub_group_id_' + i + ' option').remove();
                        $('.div_sub_group_' + i).hide();
                        $('.er_sub_group_' + i).html('');
                    }

                    for (var i = 2; i <= 5; i++) {
                        $('#group_id_' + i + ' option').remove();
                        $('.div_group_' + i).hide();
                        $('.er_group_' + i).html('');
                    }

                    element.addClass('first')

                    add_sub_group(element, number_group);
                } else {
                    element.val(data_group_1);
                }
            }
        }
    });

    $('.add_group').click(function () {
        var number_group = $(this).data('next_group');

        if (number_group > 5) {
            alert('Нельзя создавать больше 5 категорий');
        } else {

            var type = $('#group_id_1').data('type_group');

            if (typeof (type) == "undefined") {
                alert('Выберите первую категорию');
            } else {
                $.post("/cabinet/company/get_groups_this_type", {type: type}, function (res) {
                    var groups = res;

                    var str = '<option value="0">Выберите категорию</option>';

                    for (group_id in groups) {
                        str = str + '<option value="' + group_id + '">' + groups[group_id] + '</option>';
                    }

                    $('#group_id_' + number_group).html('').append(str);
                    $('.div_group_' + number_group).removeClass('hide_field').show();

                    $('.add_group').removeAttr('next_group').data('next_group', (number_group + 1));
                    $('#group_id_1').removeClass('first');
                }, "json");
            }
        }
    });

    $('.del_group').live("click", function (event) {
        var number_group = $(this).data('number_group');

        del_group(number_group);

        var flag = 0;

        for (var i = 2; i <= 5; i++) {
            if (!$('.div_group_' + i).hasClass('hide_field')) {
                flag++;
            }
        }

        if (flag == 0) {
            $('#group_id_1').addClass('first');
        }
    });

    $('.st_form #city_name').change(
            function ()
            {
                var city_name = $(this).val();

                get_metro_and_district(city_name);
            }
    );

    $("#city_name").autocomplete({
        minLength: 2,
        source: function (request, response)
        {
            $.post("/cabinet/company/get_cities", {str: request.term}, function (res) {
                response(res);
            }, "json");
        },
        select: function (event, ui)
        {
            var city_name = ui.item.value;

            get_metro_and_district(city_name)
        }
    });
});

function length(array) {
    var size = 0;
    $.each(array, function (i, elem) {
        size++;
    });
    return size;
}

function add_sub_group(element, number_group) {
    if (element.val() != 0) {
        $.post("/cabinet/company/get_sub_groups", {group_id: element.val()}, function (res) {
            var sub_groups = res.sub_groups;
            var length_sub_groups = length(sub_groups);

            if (res.type_group == 1) {
                $('.auto_models').show();
                $('.works').hide();
            } else {
                $('.works').show();
                $('.auto_models').hide();
            }

            element.removeData('type_group').attr('data-type_group', res.type_group);

            if (length_sub_groups > 0) {
                var str = '<option value="0">Выберите подкатегорию</option>';

                for (sub_groups_id in sub_groups) {
                    str = str + '<option value="' + sub_groups_id + '">' + sub_groups[sub_groups_id] + '</option>';
                }

                $('#sub_group_id_' + number_group).append(str);
                $('.div_sub_group_' + number_group).show();
            }
        }, "json");
    }
}

function get_metro_and_district(city_name) {
    if (city_name != '')
    {
        $.ajax({
            type: "POST",
            url: host_name + 'ajax/get_metro_and_district',
            data: "city_name=" + city_name,
            dataType: 'json',
            success: function (result)
            {
                if (result['error'] != '')
                {
                    return false;
                }
                if (result['data']['metro_form'] != '')
                {
                    $('.st_form .metro_select').html(result['data']['metro_form']);
                    $('.st_form .metro').show();
                }
                else
                {
                    $('.st_form .metro_select').empty();
                    $('.st_form .metro').hide();
                }

                if (result['data']['district_form'] != '')
                {
                    $('.st_form .district_select').html(result['data']['district_form']);
                    $('.st_form .district').show();
                }
                else
                {
                    $('.st_form .district_select').empty();
                    $('.st_form .district').hide();
                }
            },
            error: function ()
            {
                alert('Ошибка отправки/принятия данных');
            }
        });
    }
}

function del_group(number_group) {
    $('.div_sub_group_' + number_group).remove();

    $('.div_group_' + number_group).remove();

    for (var i = number_group; i <= 4; i++) {
        $('.div_group_' + (i + 1)).addClass('div_group_' + i).removeClass('div_group_' + (i + 1));
        $('#group_id_' + (i + 1)).removeAttr("data").removeAttr("name");
        $('#group_id_' + (i + 1)).attr('data-number_group', i).attr('name', 'group_id_' + i);
        $('#group_id_' + (i + 1)).removeAttr("id").attr('id', 'group_id_' + i);

        $('#del_group_' + (i + 1)).removeAttr("data").attr('data-number_group', i);
        $('#del_group_' + (i + 1)).removeAttr("id").attr('id', 'del_group_' + i);

        $('#label_group_' + (i + 1)).removeAttr("id").attr('id', 'label_group_' + i);

        var copy_group = $('.div_group_' + i).clone().addClass('hide_field');

        var selected_group = $('#group_id_' + i).val();

        $('.div_group_' + i).remove();

        $('.div_sub_group_' + (i + 1)).addClass('div_sub_group_' + i).removeClass('div_sub_group_' + (i + 1));
        $('#sub_group_id_' + (i + 1)).removeAttr("name");
        $('#sub_group_id_' + (i + 1)).attr('name', 'sub_group_id_' + i);
        $('#sub_group_id_' + (i + 1)).removeAttr("id").attr('id', 'sub_group_id_' + i);

        $('#label_group_' + (i + 1)).removeAttr("id").attr('id', 'label_group_' + i);

        var copy_sub_group = $('.div_sub_group_' + i).clone();

        var selected_sub_group = $('#sub_group_id_' + i).val();

        $('.div_sub_group_' + i).remove();

        $('.div_sub_group_' + (i - 1)).after(copy_group);
        $('.div_group_' + i).after(copy_sub_group);

        $('#group_id_' + i).val(selected_group);
        $('#sub_group_id_' + i).val(selected_sub_group);
    }

    var str = '<div class="div_group_5 field_body hide_field"> \
                   <label class="lab" for="group_id_5">Тип компании</label> \
                   <select id="group_id_5" class="select_group" autocomplete="off" data-number_group="5" name="group_id_5"></select> \
                   <img id="del_group_5" class="del_group" data-number_group="5" src="/assets/img/icons/del.png"> \
                   <div class="form_error"></div> \
               </div> \
               <div class="div_sub_group_5 field_body hide_field">  \
                   <label class="lab" for="sub_group_id_5">Подкатегория</label> \
                   <select id="sub_group_id_5" autocomplete="off" name="sub_group_id_5"></select> \
                   <div class="form_error"></div> \
               </div>'

    $('.div_sub_group_4').after(str);

    var next_group = $('.add_group').data('next_group') - 1;

    $('.add_group').data('next_group', next_group);
}