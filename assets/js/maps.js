window.onload = function () { 
    YMaps.jQuery(function () {  
        var map = new YMaps.Map(YMaps.jQuery("#YMapsID")[0]);
        //map.setCenter(new YMaps.GeoPoint(37.64, 55.76), 10);
        
        city = $("#YMapsID").attr('param');


        $('.select_region a.region, .cities_navigation .city').live('click', function(){
           var city = $(this).attr('rel');
            
            switch (city)
            {
                case "peterburg":
                    map.setCenter(new YMaps.GeoPoint(30.31, 59.93), 9);
                break;
                case 'moscow':
                    map.setCenter(new YMaps.GeoPoint(37.64, 55.76), 9);
                break;
                case 'novosib':
                    map.setCenter(new YMaps.GeoPoint(82.95, 55.00), 9);
                break;
                case 'novgorod':
                    map.setCenter(new YMaps.GeoPoint(44.00, 56.32), 9);
                break;
                case 'ekaterinburg':
                    map.setCenter(new YMaps.GeoPoint(60.59,56.83), 9);
                break;
                case 'samara':
                    map.setCenter(new YMaps.GeoPoint(50.19908,53.244418), 9);
                break;
                case 'omsk':
                    map.setCenter(new YMaps.GeoPoint(73.365364,54.990302), 9);
                break;
                case 'kazan':
                    map.setCenter(new YMaps.GeoPoint(49.122853,55.786764), 9);
                break;
                case 'chelabinsk':
                    map.setCenter(new YMaps.GeoPoint(61.40082,55.160324), 9);
                break;
                case 'rostov_na_donu':
                    map.setCenter(new YMaps.GeoPoint(39.744918,47.227163), 9);
                break;
                case 'ufa':
                    map.setCenter(new YMaps.GeoPoint(55.983161,54.738437), 9);
                break;
                case 'volgograd':
                    map.setCenter(new YMaps.GeoPoint(44.515942,48.707793), 9);
                break;
                case 'siktivkar':
                    map.setCenter(new YMaps.GeoPoint(50.837189,61.669587), 9);
                break;
                case 'krasnojarsk':
                    map.setCenter(new YMaps.GeoPoint(92.870412,56.008711), 9);
                break;
            }
        });
        

        $.getJSON("/ajax/get_addrs?city=" + city, function(data) {
            $.each(data, function(key, val) {            
                //определяем координаты сервиса по адресу
                var geocoder = new YMaps.Geocoder(val.addr, { results: 1 });
                
                //Обрабатываем полученные координаты
                YMaps.Events.observe(geocoder, geocoder.Events.Load, function () {
                    if (this.length()) {                 
                        // Создаем стиль значка метки
                        var s = new YMaps.Style();
                        s.balloonContentStyle = new YMaps.BalloonContentStyle(
                            new YMaps.Template("<div style=\"height: 65px\"><strong>$[name]</strong><br />$[description]</div>")
                        );                        
                        s.iconStyle = new YMaps.IconStyle();
                        s.iconStyle.href = "/assets/img/service.png";
                        s.iconStyle.size = new YMaps.Point(32, 40); 
                        
                        // Создаем метку
                        var placemark = new YMaps.Placemark(geocoder.get(0).getGeoPoint(), {style: s});
                        placemark.name = val.name;
                        //placemark.description = val.addr + "<a href='" + val.site + "' class='service_site' target='_blank'>" + val.site + "</a>";
                        placemark.description = val.addr;
                        // Добавляем метку на карту
                        map.addOverlay(placemark);
            switch (city)
            {
                case "peterburg":
                    map.setCenter(new YMaps.GeoPoint(30.31, 59.93), 9);
                break;
                case 'moscow':
                    map.setCenter(new YMaps.GeoPoint(37.64, 55.76), 9);
                break;
                case 'novosib':
                    map.setCenter(new YMaps.GeoPoint(82.95, 55.00), 9);
                break;
                case 'novgorod':
                    map.setCenter(new YMaps.GeoPoint(44.00, 56.32), 9);
                break;
                case 'ekaterinburg':
                    map.setCenter(new YMaps.GeoPoint(60.59,56.83), 9);
                break;
                case 'samara':
                    map.setCenter(new YMaps.GeoPoint(50.19908,53.244418), 9);
                break;
                case 'omsk':
                    map.setCenter(new YMaps.GeoPoint(73.365364,54.990302), 9);
                break;
                case 'kazan':
                    map.setCenter(new YMaps.GeoPoint(49.122853,55.786764), 9);
                break;
                case 'chelabinsk':
                    map.setCenter(new YMaps.GeoPoint(61.40082,55.160324), 9);
                break;
                case 'rostov_na_donu':
                    map.setCenter(new YMaps.GeoPoint(39.744918,47.227163), 9);
                break;
                case 'ufa':
                    map.setCenter(new YMaps.GeoPoint(55.983161,54.738437), 9);
                break;
                case 'volgograd':
                    map.setCenter(new YMaps.GeoPoint(44.515942,48.707793), 9);
                break;
                case 'siktivkar':
                    map.setCenter(new YMaps.GeoPoint(50.837189,61.669587), 9);
                break;
                case 'krasnojarsk':
                    map.setCenter(new YMaps.GeoPoint(92.870412,56.008711), 9);
                break;
            }
                    }
                });
                
            });
        });
        
        map.addControl(new YMaps.TypeControl());
        map.addControl(new YMaps.ToolBar());
        map.addControl(new YMaps.Zoom());
    });
};

function map_alert() {
    
}