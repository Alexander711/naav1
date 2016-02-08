$(function () {
    $('.select_group').change(function () {
        var element = $(this);
        var number_group = element.data('number_group');

        $.post("/cabinet/company/get_sub_groups", {group_id: element.val()}, function (res) {
            var sub_groups = res.sub_groups;
            var length_group = length(sub_groups);

            if (res.type_group == 1) {
                $('.auto_models').show();
                $('.works').hide();
            } else {
                $('.works').show();
                $('.auto_models').hide();
            }

            element.attr('data-type_group', res.type_group);

            if (length_group > 0) {
                var str = '<div class="li_sub_group_' + number_group + ' field_body"> \
                                <label class="lab" for="sub_group_id_' + number_group + '">Подкатегория</label>\
                                <select id="sub_group_id_' + number_group + '" name="sub_group_id[]">';

                for (sub_groups_id in sub_groups) {
                    str = str + '<option value="' + sub_groups_id + '">' + sub_groups[sub_groups_id] + '</option>';
                }

                str = str + '   </select> \
                             </div>';

                $('.li_group_' + number_group).append(str);
            } else {
                $('.li_sub_group_' + number_group).remove();
            }
        }, "json");
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
                    var element = $('.li_sub_group_' + (number_group - 1));

                    var str = '<div class="li_group_' + number_group + ' field_body"> \
                                <label class="lab" for="group_id_' + number_group + '">Тип компании</label>\
                                <select id="group_id_' + number_group + '" name="group_id[]">';

                    for (group_id in groups) {
                        str = str + '<option value="' + group_id + '">' + groups[group_id] + '</option>';
                    }

                    str = str + '   </select> \
                             </div>';

                    if (element.length > 0) {
                        $('.li_sub_group_' + (number_group - 1)).append(str);
                    } else {
                        $('.li_group_' + (number_group - 1)).append(str);
                    }
                    
                    $('.add_group').data('next_group',number_group+1);
                }, "json");
            }
        }
    });

    $("#city_name").autocomplete({
        minLength: 2,
        source: function (request, response)
        {
            $.post("/cabinet/company/get_cities", {str: request.term}, function (res) {
                response(res);
            }, "json");
        },
    });
});

function length(array) {
    var size = 0;
    $.each(array, function (i, elem) {
        size++;
    });
    return size;
}