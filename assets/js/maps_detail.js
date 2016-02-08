window.onload = function () {
    $.getJSON("/ajax/get_location?service=" + $("#service").val(), function(data) {
      $.each(data, function(key, val) {
        addr = val.addr; //Алрес сервиса
        name = val.name; //Название сервиса
        site = val.site; //Сайт сервиса
        
        //Создаем карту
        var map = new YMaps.Map(document.getElementById("YMapsID"));
        
        //определяем координаты сервиса по адресу
        var geocoder = new YMaps.Geocoder(addr, { results: 1 });
        
        //Обрабатываем полученные координаты
        YMaps.Events.observe(geocoder, geocoder.Events.Load, function () {
            if (this.length()) { 
                //Устанавливаем центр карты на сервис               
                map.setCenter(geocoder.get(0).getGeoPoint(), 14);
                
                // Создаем стиль значка метки
                var s = new YMaps.Style();
                s.iconStyle = new YMaps.IconStyle();
                s.iconStyle.href = "/assets/img/service.png";
                s.iconStyle.size = new YMaps.Point(32, 40);
                
                // Создаем метку
                var placemark = new YMaps.Placemark(geocoder.get(0).getGeoPoint(), {style: s});
                placemark.name = name;
                placemark.description = addr + "<a href='" + site + "' class='service_site' target='_blank'>" + site + "</a>";
                // Добавляем метку на карту
                map.addOverlay(placemark);                 
            }
        });
        map.addControl(new YMaps.TypeControl()); //Переключение типов
        map.addControl(new YMaps.ToolBar()); //Инструменты
        map.addControl(new YMaps.Zoom()); //Зуминг +/-
      });
    });
    
    
}