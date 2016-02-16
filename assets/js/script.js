$(function () {
    $('.select_group').live("change", function (event) {
        var element = $(this);

        $('#sub_group_id option').remove();
        $('.div_sub_group').hide();
        $('.er_sub_group').html('');

        if (element.val() != 0) {
            $.post("/cabinet/company/get_sub_groups", {group_id: element.val()}, function (res) {
                var sub_groups = res.sub_groups;
                var length_sub_groups = length(sub_groups);

                if (res.type_group == 1) {
                    $('.auto_models').show();
                    $('.works').hide();
                    $("#works").multiselect('uncheckAll');
                } else {
                    $('.works').show();
                    $('.auto_models').hide();
                    $("#auto_models").multiselect('uncheckAll');
                }

                if (length_sub_groups > 0) {
                    var str = '<option value="0">Выберите подкатегорию</option>';

                    for (sub_groups_id in sub_groups) {
                        str = str + '<option value="' + sub_groups_id + '">' + sub_groups[sub_groups_id] + '</option>';
                    }

                    $('#sub_group_id').append(str);
                    $('.div_sub_group').show();
                }
            }, "json");
        } else {
            $('.auto_models').hide();
            $('.works').hide();

            $("#auto_models").multiselect('uncheckAll');
            $("#works").multiselect('uncheckAll');
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
    } else {
        $('.st_form .metro_select').empty();
        $('.st_form .metro').hide();
        $('.st_form .district_select').empty();
        $('.st_form .district').hide();
    }
}