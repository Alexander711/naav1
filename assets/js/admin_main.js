$(document).ready(function() {
    $('#text').tinymce({
    			// Location of TinyMCE script
    			script_url : '/assets/js/tiny_mce/tiny_mce.js',

    			// General options
    			theme : "advanced",
    			plugins : "autolink,lists,jbimages,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

    			// Theme options
    			theme_advanced_buttons1 : "jbimages,|,save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
    			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
    			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
    			theme_advanced_toolbar_location : "top",
    			theme_advanced_toolbar_align : "left",
    			theme_advanced_statusbar_location : "bottom",
    			theme_advanced_resizing : true,

    			// Example content CSS (should be your site CSS)
    			content_css : "css/content.css",

    			// Drop lists for link/image/media/template dialogs
    			template_external_list_url : "lists/template_list.js",
    			external_link_list_url : "lists/link_list.js",
    			external_image_list_url : "lists/image_list.js",
    			media_external_list_url : "lists/media_list.js",

    			// Replace values for the template plugin
    			template_replace_values : {
    				username : "Some User",
    				staffid : "991234"
    			}
    		});
    $('#menu ul li').hover(
            function() {
                $(this).find('ul').stop(true, true); // останавливаем всю текущую анимацию
                $(this).find('ul').slideDown();
            },
            function() {
                $(this).find('ul').slideUp('fast');
            }
    );
    $('#form_password').showPassword();

    var services_target = $("#selected_services");
    var users_target = $('#selected_users');
    $("#services")
       .multiselect({
          noneSelectedText: "Выбор сервисов для отправки",
          selectedText: "Выбор сервисов для отправки",
          checkAllText: "Выбрать всех",
          uncheckAllText: "Снять выделение со всех",
          classes: 'av_select',
          height: 400
       })
        .bind("multiselectclick multiselectoptgrouptoggle multiselectcheckall multiselectuncheckall", function( event, ui ){

            // the getChecked method returns an array of DOM elements.
            // map over them to create a new array of just the values.
            // you could also do this by maintaining your own array of
            // checked/unchecked values, but this is just as easy.
            var checkedValues = $.map($(this).multiselect("getChecked"), function( input ){
                return input.title;
            });

            // update the target based on how many are checked
            if (checkedValues.length)
            {
                services_target.show();
                services_target.html('<b>Выбрано:</b> ' + checkedValues.join(', '));
            }
            else
            {
                services_target.hide();
            }
        })
        .triggerHandler("multiselectclick");
    $("#services_city")
       .multiselect({
          noneSelectedText: "Выбор городов для отправки",
          selectedText: "Выбор городов для отправки",
          checkAllText: "Выбрать всех",
          uncheckAllText: "Снять выделение со всех",
          classes: 'av_select',
          height: 400
       }).bind("multiselectclick multiselectoptgrouptoggle multiselectcheckall multiselectuncheckall", function( event, ui ){

           // the getChecked method returns an array of DOM elements.
           // map over them to create a new array of just the values.
           // you could also do this by maintaining your own array of
           // checked/unchecked values, but this is just as easy.
                $("#services").multiselect("uncheckAll");

           var checkedValues = $.map($(this).multiselect("getChecked"), function( input ){

               var foo = input.value.split(',');

               for (var i in foo) {
                   console.log(foo[i]);
                   $("#services").multiselect("widget").find(":checkbox[value='"+foo[i]+"']").each(function(){
                       this.click();
                   });
               }
           });
       }).triggerHandler("multiselectclick");



    $("#users")
       .multiselect({
          noneSelectedText: "Выбор пользователей для отправки",
          selectedText: "Выбор пользователей для отправки",
          checkAllText: "Выбрать всех",
          uncheckAllText: "Снять выделение со всех",
          classes: 'av_select',
          height: 400
       })
        .bind("multiselectclick multiselectoptgrouptoggle multiselectcheckall multiselectuncheckall", function( event, ui ){

            // the getChecked method returns an array of DOM elements.
            // map over them to create a new array of just the values.
            // you could also do this by maintaining your own array of
            // checked/unchecked values, but this is just as easy.
            var checkedValues = $.map($(this).multiselect("getChecked"), function( input ){
                return input.title;
            });

            // update the target based on how many are checked
            if (checkedValues.length)
            {
                users_target.show();
                users_target.html('<b>Выбрано:</b> ' + checkedValues.join(', '));
            }
            else
            {
                users_target.hide();
            }
        })
        .triggerHandler("multiselectclick");


    $('#city select').change(
        function()
        {
            var id = $(this).val();
            if (id != 0)
            {
                $.ajax(
                    {
                        url: host_name + 'ajax/get_metro_and_district/' + id,
                        dataType: 'json',
                        success: function(result)
                        {
                            if (result['error'] != '')
                            {
                                alert(result['error']);
                                return false;
                            }
                            if (result['data']['metro_form'] != '')
                            {
                                $('.metro_select').html(result['data']['metro_form']);
                                $('.metro').show();
                            }
                            else
                            {
                                $('.metro_select').empty();
                                $('.metro').hide();
                            }

                            if (result['data']['district_form'] != '')
                            {
                                $('.district_select').html(result['data']['district_form']);
                                $('.district').show();
                            }
                            else
                            {
                                $('.district_select').empty();
                                $('.district').hide();
                            }
                        },
                        error: function()
                        {
                            alert('Ошибка отправки/принятия данных');
                        }
                    }
                );
                //alert(id);
            }
            else
            {
                $('.metro_select, .district_select').empty();
                $('.metro, .district').hide();
            }
        }
    );
    // Company type in adding company
    $('#company_type').change(
    function()
    {
        var type = $(this).val();
        if (type == 2)
        {
            $('.works').fadeOut();
        }
        else
        {
            $('.works').fadeIn();
        }
    });
    // Discount's coupon text expand or hide
    $('#discount').change(function(){
        var id = $(this).val();
        if (id != 0)
        {
            $('.coupon_text').fadeIn();
        }
        else
        {
            $('.coupon_text').fadeOut();
        }
    });
    $('body').tooltip({
    selector: "a[rel=tooltip]"
    });
    $('.alert-closeable').alert();

    $('#services_modal').on('show', function(){
        $('.modal-body').html($(this).attr('data-services-div-class'));
    });

    //$('#service_all_help').attr('data-content', $('#info_text').html());
    //$("table#sort_notice_table").tablesorter({headers: { 2:{sorter: false}}});
});
function text()
{
    return $('#info_text').html();
}