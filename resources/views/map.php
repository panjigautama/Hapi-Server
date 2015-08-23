<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Hapi - Harga Sapi</title>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="js/jquery-min.js"></script>
    <script type="text/javascript"
            src="http://maps.google.com/maps/api/js?sensor=false&libraries=places,visualization"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="icon" type="img/ico" href="img/ic_cow.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/hapi.css">

    <script type="text/javascript">
        var service;
        var infoWindow;
        var map;

        function numberThousandFormat(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function initialize() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: new google.maps.LatLng(-6.182310, 106.828723),
                panControl: false,
                zoomControl: false,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                overviewMapControl: false,
                zoom: 10,
                styles: mapStyle
            });

            var layer = new google.maps.FusionTablesLayer();
            updateLayerQuery(layer, 10000);
            layer.setMap(map);

            initializeAutocomplete(map);

            google.maps.event.addListener(layer, 'click', function (e) {
                var districtName = e.row['nm_keca'].value;
                var highestPrice = e.row['highest_price'].value;
                var lowestPrice = e.row['lowest_price'].value;

                var html = "<img src='img/ic_cow.png'><br/>";
                html += "<span class='district'>" + districtName + "</span><br/><br/>";
                html += "Harga tertinggi : <br/>";
                html += "<span class='commodity'>Daging Sapi</span><br/>";
                html += "<span class='highest-price'>Rp" + numberThousandFormat(highestPrice) + "</span>";
                html += "<br/><br/>"
                html += "Harga terendah : <br/>";
                html += "<span class='commodity'>Daging Sapi</span><br/>";
                html += "<span class='lowest-price'>Rp" + numberThousandFormat(lowestPrice) + "</span>";
                html += "<br/><br/>"
                html += "<button class='btn btn-success' id='detail' type='button' name='button' onclick=\"location.href='<?php echo $app->make('url')->to('/');?>/chart?tgl1=<?php echo date('Y-m-d',strtotime('-6days'))?>&tgl2=<?php echo date('Y-m-d')?>'\">Selengkapnya</button>"
                e.infoWindowHtml = html;

                // console.log(e.row['geometry'].value);
                // performPlacesSearch(e.row['geometry'].value);

            });

            $(document).ready(function() {
                $('input[type=radio][name=data]').change(function(){
                    if(this.value == 'comm'){
                        updateLayerQuery(layer, 15000);
                    }else{
                        updateLayerQuery(layer, 10000);
                    }
                    layer.setMap(map);
                });
            });

            infoWindow = new google.maps.InfoWindow();
            service = new google.maps.places.PlacesService(map);
            for (var i = 0; i < pdPasarJayaLatLng.length; i++) {
                pdPasarJaya = pdPasarJayaLatLng[i];
                addMarker(pdPasarJaya);
            }
            //map.addListener('idle', performPlacesSearch());
        }


        function performPlacesSearch(bounds) {
            var request = {
                location: map.getCenter(),
                radius: 200,
                types: ['grocery_or_supermarket']
            };
            service.radarSearch(request, callback);
        }

        function callback(results, status) {
            console.log("callback");
            if (status !== google.maps.places.PlacesServiceStatus.OK) {
                console.error(status);
                return;
            }
            for (var i = 0, result; result = results[i]; i++) {
                addMarker(result);
            }
        }

        function addMarker(marker) {

          marker.setIcon({
              url: 'http://maps.gstatic.com/mapfiles/circle.png',
              anchor: new google.maps.Point(10, 10),
              scaledSize: new google.maps.Size(10, 17)
          });
          marker.setMap(map);
        //   var marker = new google.maps.Marker({
        //     map: map,
        //     position: place.geometry.location,
        //     icon: {
        //       url: 'http://maps.gstatic.com/mapfiles/circle.png',
        //       anchor: new google.maps.Point(10, 10),
        //       scaledSize: new google.maps.Size(10, 17)
        //     }
        //   });
            marker.addListener('click', function() {
                infoWindow.setContent(marker.getTitle());
                infowindow.open(map, marker);
            });
        }

        // var initialLocation;
        // function centerMapToCurrentLocation(map){
        //     if(navigator.geolocation) {
        //         browserSupportFlag = true;
        //         navigator.geolocation.getCurrentPosition(function(position) {
        //           initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
        //           map.setCenter(initialLocation);
        //         }, function() {
        //           handleNoGeolocation(browserSupportFlag);
        //         });
        //       }
        //       // Browser doesn't support Geolocation
        //       else {
        //         browserSupportFlag = false;
        //         handleNoGeolocation(browserSupportFlag);
        //       }
        // }
        //
        // function handleNoGeolocation(errorFlag) {
        //    if (errorFlag == true) {
        //      alert("Geolocation service failed.");
        //      initialLocation = newyork;
        //    } else {
        //      alert("Your browser doesn't support geolocation. We've placed you in Siberia.");
        //      initialLocation = siberia;
        //    }
        //    map.setCenter(initialLocation);
        //    map.setZoom(17);
        //  }

        function updateLayerQuery(layer, data) {
            var where = "data_source > " + data;
            layer.setOptions({
                query: {
                    from: '13492tA0Z8fabNTdMvkRto-ccdub7YQpxRBieA7Vl',
                    where: where
                },
                styles: [{
                    where: "'highest_price' >= 150000",
                    polygonOptions: {
                            fillColor: "#ED212E",
                            fillOpacity: 0.9,
                            strokeColor: "#FFFFFF",
                            strokeWeight: 1
                        }
                    },
                    {
                    where: "'highest_price' > 100000 AND 'highest_price' <= 149999",
                    polygonOptions: {
                            fillColor: "#ED212E",
                            fillOpacity: 0.7,
                            strokeColor: "#FFFFFF",
                            strokeWeight: 1
                        }
                    },
                    {
                    where: "'highest_price' > 80000 AND 'highest_price' <= 99999",
                    polygonOptions: {
                            fillColor: "#ED212E",
                            fillOpacity: 0.5,
                            strokeColor: "#FFFFFF",
                            strokeWeight: 1
                        }
                    },
                    {
                    where: "'highest_price' > 59999 AND 'highest_price' <= 80000",
                    polygonOptions: {
                            fillColor: "#ED212E",
                            fillOpacity: 0.3,
                            strokeColor: "#FFFFFF",
                            strokeWeight: 1
                        }
                    },
                    {
                    where: "'highest_price' < 59999",
                    polygonOptions: {
                            fillColor: "#ED212E",
                            fillOpacity: 0.2,
                            strokeColor: "#FFFFFF",
                            strokeWeight: 1
                        }
                    }
                ]
            });
        }

        function initializeAutocomplete(map) {
            var input = /** @type {!HTMLInputElement} */(
                document.getElementById('pac-input'));
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                map: map,
                anchorPoint: new google.maps.Point(0, -29)
            });

            autocomplete.addListener('place_changed', function () {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                map.setCenter(place.geometry.location);
                map.setZoom(12);
                marker.setIcon(/** @type {google.maps.Icon} */({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                }));
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);
            });
        }

        var mapStyle = [{
            'featureType': 'all',
            'elementType': 'all',
            'stylers': [{'visibility': 'off'}]
        }, {
            'featureType': 'landscape',
            'elementType': 'geometry',
            'stylers': [{'visibility': 'on'}, {'color': '#fcfcfc'}]
        }, {
            'featureType': 'water',
            'elementType': 'labels',
            'stylers': [{'visibility': 'off'}]
        }, {
            'featureType': 'water',
            'elementType': 'geometry',
            'stylers': [{'visibility': 'on'}, {'hue': '#5f94ff'}, {'lightness': 60}]
        }];

        var pdPasarJayaLatLng = [
            new google.maps.Marker({
                position: new google.maps.LatLng(-6.2567533,106.8654005),
                title: "PASAR JAMBUL BARU"
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-6.245664,106.8001754),
                title: "PASAR MELAWAI BLOK M SQUARE"
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-6.24167,106.87361),
                title: "PASAR CAWANG KAVLING"
            }),
            new google.maps.Marker({
                position: new google.maps.LatLng(-6.24167,106.87361),
                title: "PASAR KRAMATJATI"
            })
        ];

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
</head>
<body>
<div id="panel" class="col-md-12">

    <!-- <img src="https://placehold.it/80x80" alt="" />
    <input type="search" class="form-control" placeholder="Cari kecamatan atau pasar" autocomplete="off"> -->
    <div class="form-group">
        <div class="col-md-1 margin-100 logo">
            <img src="img/ic_logo.png" alt=""/>
        </div>
        <div class="col-md-10 col-md-offset-1" id="search_container">
            <div class="col-xs-5 col-md-7 padding-margin-o">
                <input type="search" id="pac-input" class="form-control" placeholder="Cari kecamatan atau pasar"
                       autocomplete="off">
            </div>
            <div class="col-md-3">
                <button class="btn btn-success" id="report" type="button" name="button">Lapor!</button>
            </div>
        </div>
    </div>
</div>
<div id="filter">
    <div class="inner-filter">
        <div class="col-md-5">
            <h4>Sumber Data</h4>
            <input type="radio" name="data" class="regular-bold" value='gov'> Data Pemerintah<br/>
            <input type="radio" name="data" class="regular-bold" value='comm' checked="checked"> Data Masyarakat
            (Crowdsourcing)
        </div>
        <div class="col-md-3">
            <h4>Harga Tertinggi Nasional</h4>
            <span class="global-highest">Rp117.000 </span><span class="increment">meningkat 1%</span>
        </div>
        <div class="col=md-3">
            <h4>Harga Terendah Nasional</h4>
            <span class="global-lowest">Rp109.000</span>
        </div>
    </div>
</div>
<div id="report-form" class="hide">
    <div class="inner-filter">
        <div class="col-md-12">
            <div class="col-md-4">
                <select class="form-control">
                    <option>Daging Has</option>
                    <option>Daging Has</option>
                    <option>Daging Has</option>
                    <option>Daging Has</option>
                    <option>Daging Has</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Harga Komoditas" autocomplete="off">
            </div>
            <div class="col-md-4">
                <button class="btn btn-success" id="save-report" type="button" name="button">Simpan!</button>
            </div>
        </div>
        <div class="col-md-12">
            <p>
                Dengan melapor, Anda membantu memberantas mafia daging sapi yang membuat harga daging mahal!
            </p>
        </div>
    </div>
</div>


<div id="map"></div>
</body>
</html>
