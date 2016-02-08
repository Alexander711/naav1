$(document).ready(function(){
    if ($('.qiwi_order'))
    {
        if ($('input[name="order_type"]').val() == 2)
            $('.qiwi_order').show();
        else
            $('.qiwi_order').hide();

        $('input[name="order_type"]').change(function(){
            if ($(this).val() == 2)
            {
                $('.qiwi_order').fadeIn();
            }
            else
            {
                $('.qiwi_order').fadeOut();
            }

        });
    }
   $("#submit").click(function(){

     var txt="";

     $("#order-form-course .validate").each(function(){

     var th = $(this);
     var rel = $(this).attr("rel");
     var val = $.trim($(this).val());
     th.removeClass("required");
     if(rel=="name"&&!(/^.{4,}$/.test(val))) {txt+="Не верно указано имя\n"; th.addClass("required")}
     if(rel=="email"&&!(/^[A-z][A-z0-9\.\-\_]{1,15}@[A-z09]{1,10}\.[0-9A-z]{2,}$/.test(val))) {txt+="Не верно указан электронный ящик\n"; th.addClass("required")}
     if(rel=="telefon"&&!(/^[0-9\-\(\)\+]{5,20}$/.test(val))) {txt+="Не верно указан номер телефона\n"; th.addClass("required")}
     if(rel=="data"&&!(/^[0-9]{2,2}\.[0-9]{2,2}\.[0-9]{4,4}$/.test(val))) {txt+="Не верно указали дату\n"; th.addClass("required")}

     })

   if(txt!="") { alert(txt);    return false;}
   $.ajax(
       {
           url: $("#order-form-course").attr("action"),
           type: "POST",
           dataType: 'json',
           data: $("#order-form-course").serialize(),
           success: function()
           {
               alert("Сообщение отправлено! Наши менеджеры свяжутся с вами в самое ближайшее время."); $("#cboxClose").click();
$("#order-form-course .validate").val("");
           },
           error: function()
           {
               alert('Ошибка отправки/принятия данных');
           }
       });

   return false;
   })

});