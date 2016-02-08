/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 18.02.12
 * Time: 2:38
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function(){
    Test.init();

});
var Test = {
    params: {
        city_id: 0,
        district_id: 0,
        metro_id: 0,
        works: [],
        cars: [],
        discounts: [],
        page: 1
    },
    init: function(){
        //alert(this.varriable)
        if ($('input[name="metro_id"]'))
            this.params.metro_id = $('input[name="metro_id"]').val();
        if ($('input[name="city_id"]'))
            this.params.city_id = $('input[name="city_id"]').val();
        if ($('input[name="district_id"]'))
            this.params.district_id = $('input[name="district_id"]').val();


        this.adoptPagination();
        this.adoptFilter();
        this.expandList();
    },
    adoptFilter: function(){
        $('input[name="car[]"]').change(function(){
            //$('input[name="car[]"]:checked').each(function() {Test.params.cars.push($(this).val());});

            if ($(this).is(':checked'))
                $(this).parent().addClass('active');
            else
                $(this).parent().removeClass('active');

            Test.params.page = 1;
            Test.params.cars = $('input[name="car[]"]:checked').map(function(){return $(this).val();}).get();
            Test.loadData();
        });
        $('input[name="work[]"]').change(function(){
            if ($(this).is(':checked'))
                $(this).parent().addClass('active');
            else
                $(this).parent().removeClass('active');

            Test.params.page = 1;
            Test.params.works = $('input[name="work[]"]:checked').map(function(){return $(this).val();}).get();
            Test.loadData();
        });
    },
    adoptPagination: function(){
        $('.pagination a[rel]').click(function(e){
            e.preventDefault();
            Test.params.page = $(this).attr('rel');
            Test.loadData();
            //alert(1);
        });
    },
    expandList: function(){
        $('.item .category').toggle(
            function(){
                $(this).next().slideDown();
                $(this).parent().addClass('active');
                //alert(Test.params.cars);
            },
            function(){
                $(this).next().slideUp();
                $(this).parent().removeClass('active');
                $(this).next().find('label').removeClass('active');
                $(this).next().find('input[name="work[]"]').attr('checked', false);
                Test.params.works = $('input[name="work[]"]:checked').map(function(){return $(this).val();}).get();
                Test.loadData();
                //alert(Test.params.cars);
            }
        );

    },
    loadData: function(){
        $.ajax({
            url: '/ajax/get_services',
            dataType: 'json',
            type: 'post',
            data: this.params,
            success: function(data){
                $('.pagination').html(data.pagination);
                $('.search_result').html(data.services);
                $('.debug').html(data.debug_html);
                Test.adoptPagination();
            }

        });
    }

};