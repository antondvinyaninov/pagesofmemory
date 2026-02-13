<!DOCTYPE html>
<html>
<head>
    <title>Тест Яндекс.Карты</title>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=6645de69-1da7-468b-86da-59ced1a03485&lang=ru_RU" type="text/javascript"></script>
    <style>
        #map {
            width: 100%;
            height: 600px;
        }
    </style>
</head>
<body>
    <h1>Тест Яндекс.Карты</h1>
    <p>Кликните по карте чтобы поставить метку</p>
    <div id="map"></div>

    <script type="text/javascript">
        ymaps.ready(init);
        
        var myMap, myPlacemark;

        function init() {
            myMap = new ymaps.Map("map", {
                center: [55.76, 37.64],
                zoom: 10
            });

            myMap.events.add('click', function (e) {
                var coords = e.get('coords');
                
                // Если метка уже создана – просто передвигаем ее
                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
                // Если нет – создаем.
                else {
                    myPlacemark = createPlacemark(coords);
                    myMap.geoObjects.add(myPlacemark);
                }
                
                console.log('Координаты:', coords);
            });
        }

        function createPlacemark(coords) {
            return new ymaps.Placemark(coords, {
                iconCaption: 'Место захоронения'
            }, {
                preset: 'islands#violetDotIconWithCaption',
                draggable: true
            });
        }
    </script>
</body>
</html>
