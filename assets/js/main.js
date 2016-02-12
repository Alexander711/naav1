$(document).ready(function () {
    $('.expand-review').click(function (e) {
        e.preventDefault();
        $(this).hide().prev().html($(this).next().html());

    });
    $('.expand-cars, .expand-works').click(function (e) {
        e.preventDefault();
        $(this).hide().parent().siblings('li').show();
    });
    $('.scrollto').click(function (e) {
        e.preventDefault();
        $.scrollTo('.' + $(this).data('scroll'), 2000);
    });
    // View Service
    if ($('.comment-nav').length)
    {
        $('.comment-nav a').click(function (e) {
            e.preventDefault();
            if (!$(this).parent().hasClass('active'))
            {
                $(this).parents('.reviews-title').find('.reviews-site, .reviews-vk').hide();
                $(this).parents('.reviews-title').find('.' + $(this).attr('rel')).show();
                $(this).parent().addClass('active').siblings('li').removeClass('active');
            }

        });
    }
    //$('.coupon_frame').iframeAutoHeight();
    host_name = 'http://' + location.host + '/';
    $('#form_password').showPassword();
    $('a.gallery').click(function (e) {
        e.preventDefault();
    });
    $("a.gallery").lightBox();



    $('.button_print').live('click', function () {

        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=650, height=600, left=100, top=25";

        var docprint = window.open("", "", disp_setting);
        docprint.document.open();
        docprint.document.write('<html><head><title>' + document.title + '</title>');
        // set some styles
        docprint.document.write('<style media="all" type="text/css">');
        // hide excessive blocks
        docprint.document.write('#footer,#header,.breadcrumbs,.service-info,.comment-type-nav, #vk_comments,.print-link{display:none;}');


        // auto print page on load
        docprint.document.write('</style></head><body onLoad="self.print()">');
        // echo article inner html
        docprint.document.write($('#coupon_container').html());
        docprint.document.write('</body></html>');
        docprint.document.close();
        docprint.focus();

        return false;
    });

    $('<div id="qtip-blanket">')
            .css({
                position: 'absolute',
                top: $(document).scrollTop(), // Use document scrollTop so it's on-screen even if the window is scrolled
                left: 0,
                height: $(document).height(), // Span the full document height...
                width: '100%', // ...and full width

                opacity: 0.7, // Make it slightly transparent
                backgroundColor: 'black',
                zIndex: 10  // Make sure the zIndex is below 6000 to keep it below tooltips!
            })
            .appendTo(document.body) // Append to the document body
            .hide(); // Hide it initially

    var open_coupon_flag = ($('.btn-get-coupon').attr('rel') == 1) ? true : false;
    $('.btn-get-coupon').qtip(
            {
                content:
                        {
                            title:
                                    {
                                        text: 'Купон на получение скидки',
                                        button: 'Закрыть'
                                    },
                            text: $('.coupon_text').html(),
                            prerender: true
                        },
                position:
                        {
                            target: $(document.body),
                            corner: 'center'

                        },
                show:
                        {
                            when: 'click',
                            ready: open_coupon_flag,
                            solo: true
                        },
                hide: false,
                style:
                        {
                            width: {min: 900, max: 950},
                            padding: '15px',
                            border:
                                    {
                                        width: 9,
                                        radius: 9,
                                        color: '#666666'
                                    },
                            name: 'light'
                        },
                api:
                        {
                            beforeShow: function ()
                            {
                                // Fade in the modal "blanket" using the defined show speed
                                $('#qtip-blanket').fadeIn(this.options.show.effect.length);
                            },
                            beforeHide: function ()
                            {
                                // Fade out the modal "blanket" using the defined hide speed
                                $('#qtip-blanket').fadeOut(this.options.hide.effect.length);
                            }
                        }
            }
    );



    $('#qtip-blanket').click(function (e) {
        e.preventDefault();

        $('#qtip-blanket, .qtip').fadeOut();
    });


    // Search sort
    if ($('.search_result'))
    {
        // Search params
        searchFilter.init();
        $('.show_cars_list').click(function () {
            $(this).hide();
            $('.search_item_info').hide();
            $('.cars_list').fadeIn();

        });
        $('.show_works_list').click(function () {
            $(this).hide();
            $('.search_item_info').hide();
            $('.works_list').fadeIn();

        });
        $('.alert_params').click(function () {
            alert(params);
        });
    }
    if ($('.filter_tags').length)
    {
        $('.filter_tags.columnize').columnize({columns: 3, lastNeverTallest: true});

        $('select[name="metro"][data-type^="filter_"]').attr('autocomplete', 'off');
        $('select[name="district"][data-type^="filter_"]').attr('autocomplete', 'off');

        // Страницы тегов
        // Подбор тегов авто по округу
        $('select[name="district"][data-type^="filter_"]').change(function () {
            var select = $(this);
            $('.filter_tags').html('<p align="center" style="font-size: 20px; font-weight: bold;">Загрузка...</p><div class="preloader"></div>');
            $('select[name="metro"][data-type^="filter_"]').attr('disabled', true);
            select.attr('disabled', true);
            $('select[name="metro"][data-type^="filter_"]').html('<option value="0">Загрузка</option>');
            $.ajax({
                url: host_name + 'ajax/filter_district_sort',
                type: 'post',
                data:
                        {
                            type: $(this).data('type'),
                            city_id: $(this).data('city'),
                            district_id: $(this).val()
                        },
                dataType: 'json',
                success: function (result)
                {
                    $('select[name="metro"][data-type^="filter_"]').html(result.metro_select_options);
                    select.attr('disabled', false);
                    $('select[name="metro"][data-type^="filter_"]').attr('disabled', false);
                    $('.filter_tags').replaceWith(result.items_tags);
                    $('.filter_tags.columnize').columnize({columns: 3, lastNeverTallest: true});

                }
            });
        });
        // Сортировка на странцие тегов по станции метро
        $('select[name="metro"][data-type^="filter_"]').change(function () {
            var district_select = $('select[name="district"][data-type^="filter_"]');
            var metro_select = $(this);
            district_select.attr('disabled', true);
            metro_select.attr('disabled', true);

            $('.filter_tags').html('<p align="center" style="font-size: 20px; font-weight: bold;">Загрузка...</p><div class="preloader"></div>');
            $.ajax({
                url: host_name + 'ajax/filter_metro_sort',
                type: 'post',
                dataType: 'json',
                data:
                        {
                            type: $(this).data('type'),
                            city_id: $(this).data('city'),
                            district_id: $('select[name="district"][data-type^="filter_"] option:selected').val(),
                            metro_id: $(this).val()
                        },
                success: function (result)
                {
                    district_select.attr('disabled', false);
                    metro_select.attr('disabled', false);
                    $('.filter_tags').replaceWith(result.items_tags);
                    $('.filter_tags.columnize').columnize({columns: 3, lastNeverTallest: true});
                }
            });

        });
    }
    /**
     * Fast filter
     */
    if ($('.filter-type-nav').length)
    {
        // Раскрытие элемента  "другие города"
        $('.fast-filter-cities .other > li').live(
                {
                    mouseenter: function ()
                    {
                        $(this).children('.expand-link').addClass('active');
                        $(this).find('div[class="cities-list"]').stop(true, true); // останавливаем всю текущую анимацию
                        $(this).find('div[class="cities-list"]').slideDown();
                    },
                    mouseleave: function ()
                    {
                        $(this).children('.expand-link').removeClass('active');
                        $(this).find('div[class="cities-list"]').stop(true, true); // останавливаем всю текущую анимацию
                        $(this).find('div[class="cities-list"]').slideUp();
                    }
                }
        );
        // Генерация карты
        map = new YMaps.Map(YMaps.jQuery('#all_companies_map')[0]);

        geo_coder = new YMaps.Geocoder($('#all_companies_map').attr('param'));
        YMaps.Events.observe(geo_coder, geo_coder.Events.Load, function ()
        {
            if (this.length())
            {
                map.setCenter(this.get(0).getGeoPoint(), 9);
            }
            else
            {
                $('#error').html('Ошибка: введите корректный адрес');
            }
        });
        style = new YMaps.Style();
        style.iconStyle = new YMaps.IconStyle();
        style.iconStyle.href = "/assets/img/service.png";
        style.iconStyle.size = new YMaps.Point(32, 40);
        placemarks = [];
        $.ajax(
                {
                    url: host_name + 'ajax/get_ymaps_companies',
                    dataType: 'json',
                    success: function (result)
                    {
                        for (var i in result)
                        {
                            placemarks[i] = new YMaps.Placemark(new YMaps.GeoPoint(result[i]['lng'], result[i]['lat']), {style: style});
                            placemarks[i].name = result[i]['name'];
                            placemarks[i].description = result[i]['description'];
                            map.addOverlay(placemarks[i]);
                        }
                    }
                }
        )
        map.addControl(new YMaps.Zoom());
        map.addControl(new YMaps.TypeControl());
    }

    $('a[data-action=choose-city]').live('click', function (e) {
        e.preventDefault();

        var butt = $(this);

        var data = {
            city: $(this).attr('rel'),
            mode: 'fast_filter'
        };


        if (!$('.filter-type-nav').length)
            data.mode = 'without_filter';
        else
            $('.fast-filter-tags').html('<div class="preloader"></div>');

        $.ajax(
                {
                    url: host_name + 'ajax/fast_Filter',
                    type: 'GET',
                    data: data,
                    dataType: 'json',
                    cache: false,
                    success: function (result)
                    {
                        if ($('.filter-type-nav').length)
                        {
                            $('.fast-filter-tags').replaceWith(result.items);
                            $('.fast-filter-cities').replaceWith(result.cities);
                            $('.map_block .block_title strong').html(result.current_city.genitive_name);
                            $('#all_companies_map').attr('param', result.current_city.name);
                            geo_coder = new YMaps.Geocoder(butt.html());
                            YMaps.Events.observe(geo_coder, geo_coder.Events.Load, function ()
                            {
                                if (this.length())
                                {
                                    map.setCenter(this.get(0).getGeoPoint(), 9);
                                }
                                else
                                {
                                    $('#error').html('Ошибка: введите корректный адрес');
                                }
                            });

                        }
                        $('.region_expand .choose_region').html(butt.html());
                        $('.region_expand .select_region').html(result.header_cities);
                        $('.menu-auto-filter').attr('href', result.auto_filter_url)
                    }
                }
        )
    });
    $('[data-action="choose-type"]').live('click', function (e) {
        e.preventDefault();
        if (!$(this).hasClass('active'))
        {
            var current_butt = $(this);
            $('.fast-filter-cities').html('<div class="preloader-horizontal"></div>');
            $('.fast-filter-tags').html('<div class="preloader"></div>');
            $.ajax(
                    {
                        url: host_name + 'ajax/fast_Filter',
                        type: 'GET',
                        data: {type: $(this).attr('rel')},
                        dataType: 'json',
                        cache: false,
                        success: function (result)
                        {

                            $('[data-action="choose-type"]').removeClass('active');
                            current_butt.addClass('active');
                            $('.fast-filter-tags').replaceWith(result.items);
                            $('.fast-filter-cities').replaceWith(result.cities);
                            if ($('#all_companies_map').attr('param') != result.current_city.name)
                            {
                                $('.map_block .block_title strong').html(result.current_city.genitive_name);
                                $('#all_companies_map').attr('param', result.current_city.name);
                                geo_coder = new YMaps.Geocoder(result.current_city.name);
                                YMaps.Events.observe(geo_coder, geo_coder.Events.Load, function ()
                                {
                                    if (this.length())
                                    {
                                        map.setCenter(this.get(0).getGeoPoint(), 9);
                                    }
                                    else
                                    {
                                        $('#error').html('Ошибка: введите корректный адрес');
                                    }
                                });
                            }
                        }
                    }
            )
        }

    });
    /**
     * Редактирование компании, карта
     */
    /*if ($('#edit-map').length)
     {
     var placemark, geo_coder;
     var addr_input = $('input[name="address"]');
     
     map = new YMaps.Map(YMaps.jQuery('#edit-map')[0]);
     map.setCenter(new YMaps.GeoPoint(37.64, 55.76), 2);
     map.addControl(new YMaps.Zoom());
     map.addControl(new YMaps.TypeControl());
     style = new YMaps.Style();
     style.iconStyle = new YMaps.IconStyle();
     style.iconStyle.href = "/assets/img/service.png";
     style.iconStyle.size = new YMaps.Point(32, 40);
     var default_cords = ($('input[name="ymap_lat"]').val() != 0) ? {lng: $('input[name="ymap_lng"]').val(), lat: $('input[name="ymap_lat"]').val()} : {lng: 37.64, lat: 55.76};
     var default_height = ($('input[name="ymap_lat"]').val() != 0) ? 20 : 9;
     placemark = new YMaps.Placemark(new YMaps.GeoPoint(default_cords.lng, default_cords.lat), {draggable: true, style: style});
     map.addOverlay(placemark);
     map.setCenter(new YMaps.GeoPoint(default_cords.lng, default_cords.lat), default_height);
     
     YMaps.Events.observe(placemark, placemark.Events.DragEnd, function (obj) {
     var coords = obj.getGeoPoint();
     $('input[name=ymap_lat]').val(coords['__lat']);
     $('input[name=ymap_lng]').val(coords['__lng']);
     });
     
     $('select[name="city_id"]').change(function () {
     var addr = ($('input[name="address"]').val() == '') ? $('select[name=city_id] option:selected').html() : $('select[name=city_id] option:selected').html() + ',' + $('input[name="address"]').val();
     geo_coder = new YMaps.Geocoder(addr);
     YMaps.Events.observe(geo_coder, geo_coder.Events.Load, function ()
     {
     if (this.length())
     {
     
     var coords = this.get(0).getGeoPoint();
     if ($('input[name="address"]').val() == '')
     {
     map.setCenter(coords, 9);
     }
     else
     {
     map.setCenter(coords, 20);
     }
     
     placemark.setGeoPoint(coords);
     
     $('input[name=ymap_lat]').val(coords['__lat']);
     $('input[name=ymap_lng]').val(coords['__lng']);
     }
     else
     {
     $('.map-error').html('Ошибка: введите корректный адрес');
     }
     
     });
     YMaps.Events.observe(geo_coder, geo_coder.Events.Fault, function (geocoder, errorMessage) {
     $('.map-error').html('Ошибка ' + errorMessage);
     });
     
     });
     $('input[name="address"]').change(function () {
     $('.map-error').empty();
     if ($('select[name="city_id"]').val() == 0)
     {
     $('.map-error').html('Для начала выберите город');
     return;
     }
     var input = $(this);
     geo_coder = new YMaps.Geocoder($('select[name=city_id] option:selected').html() + ', ' + $(this).val());
     YMaps.Events.observe(geo_coder, geo_coder.Events.Load, function ()
     {
     var height;
     if (this.length())
     {
     var coords = this.get(0).getGeoPoint();
     placemark.setGeoPoint(coords);
     if (input.val() != '')
     {
     height = 20;
     }
     else
     {
     height = 9;
     $('.map-error').html('Ошибка: введите адрес');
     }
     map.setCenter(coords, height);
     $('input[name=ymap_lat]').val(coords['__lat']);
     $('input[name=ymap_lng]').val(coords['__lng']);
     
     }
     else
     {
     $('.map-error').html('Ошибка: введите корректный адрес');
     }
     
     });
     YMaps.Events.observe(geo_coder, geo_coder.Events.Fault, function (geocoder, errorMessage) {
     $('.map-error').html('Ошибка ' + errorMessage);
     });
     });
     
     }*/
    $('.expand_other_city').live(
            {
                mouseenter: function ()
                {
                    $('.choose_region').addClass('active');
                    $(this).find('div[class="other_cities"]').stop(true, true); // останавливаем всю текущую анимацию
                    $(this).find('div[class="other_cities"]').slideDown();
                },
                mouseleave: function ()
                {
                    $(this).find('div[class="other_cities"]').slideUp('fast');
                    $('.choose_region').removeClass('active');
                }
            }
    );
    // Expand regions for Ymaps

    $('.region_expand').hover(
            function ()
            {
                $(this).find('div[class="select_region"]').stop(true, true); // останавливаем всю текущую анимацию
                $(this).find('div[class="select_region"]').slideDown();
            },
            function ()
            {
                $(this).find('div[class="select_region"]').stop(true, true); // останавливаем всю текущую анимацию
                $(this).find('div[class="select_region"]').slideUp();
            }
    );




    var works_target = $("#selected_works");
    var auto_models_target = $("#selected_models");

    $("#works")
            .multiselect({
                noneSelectedText: "Выбор предоставляемых услуг",
                selectedText: "Выбор предоставляемых услуг",
                checkAllText: "Выбрать все",
                uncheckAllText: "Снять выделение со всех",
                classes: 'av_select',
                height: 400
            })
            .bind("multiselectclick multiselectoptgrouptoggle multiselectcheckall multiselectuncheckall", function (event, ui) {

                // the getChecked method returns an array of DOM elements.
                // map over them to create a new array of just the values.
                // you could also do this by maintaining your own array of
                // checked/unchecked values, but this is just as easy.
                var checkedValues = $.map($(this).multiselect("getChecked"), function (input) {
                    return input.title;
                });

                // update the target based on how many are checked
                if (checkedValues.length)
                {
                    works_target.show();
                    works_target.html('<b>Выбрано:</b> ' + checkedValues.join(', '));
                }
                else
                {
                    works_target.hide();
                }
            })
            .triggerHandler("multiselectclick");

    $("#auto_models")
            .multiselect({
                noneSelectedText: "Выбор марок автомобилей",
                selectedText: "Выбор марок автомобилей",
                checkAllText: "Выбрать все",
                uncheckAllText: "Снять выделение со всех",
                classes: 'av_select',
                height: 400
            })
            .bind("multiselectclick multiselectoptgrouptoggle multiselectcheckall multiselectuncheckall", function (event, ui) {

                // the getChecked method returns an array of DOM elements.
                // map over them to create a new array of just the values.
                // you could also do this by maintaining your own array of
                // checked/unchecked values, but this is just as easy.
                var checkedValues = $.map($(this).multiselect("getChecked"), function (input) {
                    return input.title;
                });

                // update the target based on how many are checked
                if (checkedValues.length)
                {
                    auto_models_target.show();
                    auto_models_target.html('<b>Выбрано:</b> ' + checkedValues.join(', '));
                }
                else
                {
                    auto_models_target.hide();
                }
            })
            .triggerHandler("multiselectclick");

    // Company type in adding company
    $('.st_form #company_type').change(
            function ()
            {
                var type = $(this).val();
                if (type == 2)
                {
                    $('.st_form .works').fadeOut();
                }
                else
                {
                    $('.st_form .works').fadeIn();
                }
            });
    // Discount's coupon text expand or hide
    $('.st_form #discount').change(function () {
        var id = $(this).val();
        if (id != 0)
        {
            $('.st_form .coupon_text').fadeIn();
        }
        else
        {
            $('.st_form .coupon_text').fadeOut();
        }
    });
    // Выбор города на странице добавления отзыва
    $('.st_form .add_review_city').change(
            function ()
            {
                var id = $(this).val();
                $.ajax(
                        {
                            url: host_name + 'ajax/add_review_services/' + id,
                            dataType: 'json',
                            success: function (result)
                            {
                                $('.add_review_services').html(result['services_select_html']);
                            },
                            error: function ()
                            {
                                alert('Ошибка отправки/принятия данных');
                            }
                        }
                );
            }
    );
    // Выбор марки авто на странице добавления запроса
    $('.form #add_qa_car_brand').change(
            function ()
            {
                var id = $(this).val();
                $.ajax(
                        {
                            url: host_name + 'ajax/add_qa_car_models/' + id,
                            dataType: 'json',
                            success: function (result)
                            {
                                $('#add_qa_models').html(result['models_select_html']);
                            },
                            error: function ()
                            {
                                alert('Ошибка отправки/принятия данных');
                            }
                        }
                );
            }
    );

    // Текстовой поиск
    $('.search-form').submit(function (e) {
        e.preventDefault();
        $(this).find('.submit').addClass('processing');
        var form = $(this);
        $.ajax({
            url: '/search/ajax',
            type: 'post',
            dataType: 'json',
            data: {str: form.find('input[name="str"]').val()},
            success: function (result) {
                form.find('.form-errors').empty();
                var errors_body = '';
                for (var name in result.errors)
                {
                    errors_body += '<div class="error">' + result.errors[name] + '</div>';


                }
                if (errors_body.length > 0)
                {
                    form.find('.result').empty();
                    form.find('.form-errors').html(errors_body);
                    form.find('.submit').removeClass('processing');
                    return false;
                }


                form.find('.result').html(result.body);
                form.find('.submit').removeClass('processing');

            },
            error: function () {
                alert('Ошибка отправки/принятия данных');
            }
        });
    });

    $('.search-form .navigation a').live('click', function (e) {
        e.preventDefault();
        var target = $(this).data('target');
        if ($(this).hasClass('disable'))
        {
            $('.' + target).fadeIn();
            $(this).removeClass('disable');
        }
        else
        {
            $('.' + target).fadeOut();
            $(this).addClass('disable');
        }
    });

});
var searchFilter = {
    params: {
        city_id: 0,
        metro_id: 0,
        district_id: 0,
        cars: [],
        works: [],
        discounts: [],
        page: 1
    },
    init: function () {
        $('input[name="car[]"]').attr('autocomplete', 'off');
        $('input[name="work[]"]').attr('autocomplete', 'off');
        $('select[name="district"][data-type^="search_by_"]').attr('autocomplete', 'off');
        $('select[name="metro"][data-type^="search_by_"]').attr('autocomplete', 'off');

        if ($('input[name="metro_id"]').length)
            this.params.metro_id = $('input[name="metro_id"]').val();
        if ($('input[name="city_id"]').length)
            this.params.city_id = $('input[name="city_id"]').val();
        if ($('input[name="district_id"]').length)
            this.params.district_id = $('input[name="district_id"]').val();

        this.params.cars = $('input[name="car[]"]:checked').map(function () {
            return $(this).val();
        }).get();
        this.params.works = $('input[name="work[]"]:checked').map(function () {
            return $(this).val();
        }).get();

        this.chooseDistrictsMetro();
        this.expandCategory();
        this.adoptFilters();
        this.adoptDiscounts();
        this.adoptPagination();
    },
    updateWorks: function () {
        this.params.page = 1;
        this.params.discounts = [];
        this.params.works = $('input[name="work[]"]:checked').map(function () {
            return $(this).val();
        }).get();
    },
    updateCars: function () {
        this.params.page = 1;
        this.params.discounts = [];
        this.params.cars = $('input[name="car[]"]:checked').map(function () {
            return $(this).val();
        }).get();
    },
    updateDiscounts: function () {
        this.params.page = 1;
        this.params.discounts = [];
        this.params.discounts = $('input[name="discount[]"]:checked').map(function () {
            return $(this).val();
        }).get();
    },
    expandCategory: function () {

        $('.search_sort_list .category .title').click(function () {
            if ($(this).parent().hasClass('active'))
            {
                $(this).children('.icon').removeClass('icon-close');
                $(this).children('.icon').addClass('icon-expand');
                $(this).next().slideUp();
                $(this).parent().removeClass('active');
                $(this).next().find('label').removeClass('active');
                $(this).next().find('input[name="work[]"]').attr('checked', false);
                searchFilter.updateWorks();
                searchFilter.loadServices();
            }
            else
            {
                $(this).next().slideDown();
                $(this).parent().addClass('active');
                $(this).children('.icon').removeClass('icon-expand');
                $(this).children('.icon').addClass('icon-close');
            }
        });
    },
    chooseDistrictsMetro: function () {
        // Сортировка сервисов по округу
        $('select[name="district"][data-type^="search_by_"]').change(function () {
            var select = $(this);
            $('select[name="metro"][data-type^="search_by_"]').attr('disabled', true);
            select.attr('disabled', true);
            $('.search_result').html('<p align="center" style="font-size: 20px; font-weight: bold;">Загрузка...</p><div class="preloader"></div>');
            $('select[name="metro"][data-type^="search_by_"]').html('<option value="0">Загрузка</option>');
            $.ajax({
                url: host_name + 'ajax/search_district_sort',
                type: 'post',
                data:
                        {
                            type: $(this).data('type'),
                            item_id: $(this).data('item'),
                            city_id: $(this).data('city'),
                            district_id: $(this).val()
                        },
                dataType: 'json',
                success: function (result)
                {
                    $('select[name="metro"][data-type^="search_by_"]').html(result.metro_select_options);
                    select.attr('disabled', false);
                    $('select[name="metro"][data-type^="search_by_"]').attr('disabled', false);
                    $('.search_result').html(result.services);

                    $('.search_info .district').empty();
                    $('.search_info .metro').empty();

                    if (select.val() != 0)
                        $('.search_info .district').html('округ <strong>' + $('select[name="district"][data-type^="search_by_"] option:selected').html() + '</strong>');

                    searchFilter.params.district_id = result.district_id;
                    searchFilter.params.metro_id = 0;
                    $('.search_sort_lists').empty();
                    if (result.services_count > 1)
                    {

                        $('.search_sort_lists').append(result.discounts);
                        searchFilter.adoptDiscounts();
                        if (select.data('type') == 'search_by_auto')
                        {
                            $('.search_sort_lists').append(result.works);
                            searchFilter.expandCategory();
                            searchFilter.adoptFilters();
                        }
                        else if (select.data('type') == 'search_by_work')
                        {
                            $('.search_sort_lists').append(result.cars);
                            searchFilter.adoptFilters();
                        }
                    }
                    else
                    {
                        $('.search_sort_lists').append('<p class="sort_disable_message">Найден только один автосервис. Сортировка отключена.</p>');
                    }
                }
            });
        });
        // Сортировка сервисов по станции метро
        $('select[name="metro"][data-type^="search_by_"]').change(function () {
            var metro_select = $(this);
            var district_select = $('select[name="district"][data-type^="search_by_"]');

            metro_select.attr('disabled', true);
            district_select.attr('disabled', true);

            $('.search_result').html('<p align="center" style="font-size: 20px; font-weight: bold;">Загрузка...</p><div class="preloader"></div>');

            $.ajax({
                url: host_name + 'ajax/search_metro_sort',
                type: 'post',
                data:
                        {
                            type: $(this).data('type'),
                            item_id: $(this).data('item'),
                            city_id: $(this).data('city'),
                            district_id: searchFilter.params.district_id,
                            metro_id: metro_select.val()
                        },
                dataType: 'json',
                success: function (result)
                {
                    district_select.attr('disabled', false);
                    metro_select.attr('disabled', false);
                    $('.search_result').html(result.services);
                    searchFilter.params.district_id = result.district_id;


                    $('.search_info .metro').empty();

                    if (metro_select.val() != 0)
                        $('.search_info .metro').html('станция метро <strong>' + $('select[name="metro"][data-type^="search_by_"] option:selected').html() + '</strong>');

                    $('.search_sort_lists').empty();
                    if (result.services_count > 1)
                    {

                        $('.search_sort_lists').append(result.discounts);
                        searchFilter.adoptDiscounts();
                        if (metro_select.data('type') == 'search_by_auto')
                        {
                            $('.search_sort_lists').append(result.works);
                            searchFilter.expandCategory();
                            searchFilter.adoptFilters();
                        }
                        else if (metro_select.data('type') == 'search_by_work')
                        {
                            $('.search_sort_lists').append(result.cars);
                            searchFilter.adoptFilters();
                        }
                    }
                    else
                    {
                        $('.search_sort_lists').append('<p class="sort_disable_message">Найден только один автосервис. Сортировка отключена.</p>');
                    }
                }
            });
        });
    },
    adoptFilters: function () {
        $('input[name="work[]"]').change(function () {
            if ($(this).is(':checked'))
                $(this).parent().addClass('active');
            else
                $(this).parent().removeClass('active');

            searchFilter.updateWorks();
            searchFilter.loadServices();
        });

        $('input[name="car[]"]').change(function () {
            if ($(this).is(':checked'))
                $(this).parent().addClass('active');
            else
                $(this).parent().removeClass('active');

            searchFilter.updateCars();
            searchFilter.loadServices();
        });

    },
    adoptDiscounts: function () {
        $('input[name="discount[]"]').change(function () {
            if ($(this).is(':checked'))
                $(this).parent().addClass('active');
            else
                $(this).parent().removeClass('active');

            searchFilter.updateDiscounts();
            searchFilter.loadServices('no_up_discount');
        });
    },
    adoptPagination: function () {
        $('.pagination a[class!="current"]').click(function () {
            searchFilter.params.page = $(this).attr('rel');
            searchFilter.loadServices('no_up_discount');



        });
    },
    loadServices: function (type) {
        $.ajax(
                {
                    url: host_name + 'ajax/get_services',
                    data: searchFilter.params,
                    dataType: 'json',
                    type: 'post',
                    success: function (result)
                    {
                        $('.debug').html(result['debug_html']);
                        $('.search_result').html(result['services']);
                        $('.pagination').html(result['pagination']);
                        if (type != 'no_up_discount')
                        {
                            $('.filters_column .discounts_list').replaceWith(result['discounts']);
                            searchFilter.adoptDiscounts();
                        }

                        searchFilter.adoptPagination();
                        $('.debug').html(result.debug_html);

                    },
                    error: function ()
                    {
                        alert('Ошибка отправки/принятия данных');
                    }
                }
        );
        console.log(this.params);
    }
};
function getBrowserInfo() {
    var t, v = undefined;
    if (window.opera)
        t = 'Opera';
    else if (document.all) {
        t = 'IE';
        var nv = navigator.appVersion;
        var s = nv.indexOf('MSIE') + 5;
        v = nv.substring(s, s + 1);
    }
    else if (navigator.appName)
        t = 'Netscape';
    return {type: t, version: v};
}

function bookmark(a) {
    var url = window.document.location;
    var title = window.document.title;
    var b = getBrowserInfo();
    if (b.type == 'IE' && 7 > b.version && b.version >= 4)
        window.external.AddFavorite(url, title);
    else if (b.type == 'Opera') {
        a.href = url;
        a.rel = "sidebar";
        a.title = url + ',' + title;
        return true;
    }
    else if (b.type == "Netscape")
        window.sidebar.addPanel(title, url, "");
    else
        alert("Нажмите CTRL-D, чтобы добавить страницу в закладки.");
    return false;
}
function print_div(div_block)
{
    var headstr = "<html><head><title></title></head><body>";
    var footstr = "</body>";
    var newstr = $('#' + div_block).html();
    var oldstr = $('body').html();
    document.body.innerHTML = headstr + newstr + footstr;
    window.print();
    $('body').html(oldstr);
    return false;

}


