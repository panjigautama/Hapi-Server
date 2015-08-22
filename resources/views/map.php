<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>HaPi - Harga Sapi Bikin Hepi !</title>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="js/jquery-min.js"></script>
    <script type="text/javascript"
        src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/hapi.css">
  </head>
  <body>
    <div id="panel" class="col-md-12">
        <!-- <img src="https://placehold.it/80x80" alt="" />
        <input type="search" class="form-control" placeholder="Cari kecamatan atau pasar" autocomplete="off"> -->
        <div class="form-group">
            <div class="col-md-10 col-md-offset-1">
                <div class="col-xs-5 col-md-7 padding-margin-o">
                    <input type="search" id="pac-input" class="form-control" placeholder="Cari kecamatan atau pasar" autocomplete="off">
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
                <input type="radio" name="data" class="regular-bold" value='comm' checked="checked"> Data Masyarakat (Crowdsourcing)
            </div>
            <div class="col-md-3">
                <h4>Harga Tertinggi Global</h4>
                <span class="global-highest">Rp117.000 </span><span class="increment">meningkat 1%</span>
            </div>
            <div class="col=md-3">
                <h4>Harga Terendah Global</h4>
                <span class="global-lowest">Rp109.000</span>
            </div>
        </div>
    </div>
    <div id="map"></div>
    <script type="text/javascript">
        function initialize() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: new google.maps.LatLng(-6.182310, 106.828723),
                zoom: 10,
                styles:mapStyle
            });

            var layer = new google.maps.FusionTablesLayer();
            updateLayerQuery(layer, 10000);
            layer.setMap(map);

            initializeAutocomplete(map);

            google.maps.event.addListener(layer, 'click', function(e) {
                var districtName = e.row['nm_keca'].value;
                var highestPrice = e.row['highest_price'].value;
                var lowestPrice = e.row['lowest_price'].value;

                var html = "<img src='img/ic_cow.png'><br/>";
                html += "<span class='district'>"+districtName + "</span><br/><br/>";
                html += "Harga tertinggi : <br/>";
                html += "<span class='commodity'>Daging Sapi</span><br/>";
                html += "<span class='highest-price'>Rp"+highestPrice+"</span>";
                html += "<br/><br/>"
                html += "Harga terendah : <br/>";
                html += "<span class='commodity'>Daging Sapi</span><br/>";
                html += "<span class='lowest-price'>Rp"+lowestPrice+"</span>";
                html += "<br/><br/>"
                html += "<button class='btn btn-success' id='detail' type='button' name='button'>Selengkapnya</button>"
                e.infoWindowHtml = html;
            });

            $(document).ready(function() {
                $('input[type=radio][name=data]').change(function(){
                    if(this.value == 'comm'){
                        updateLayerQuery(layer, 10000);
                    }else{
                        updateLayerQuery(layer, 5000);
                    }
                    layer.setMap(map);
                });
            });
        }

        function updateLayerQuery(layer, data){
            var where = "data_source > "+data;
            layer.setOptions({
                query:{
                      from: '13492tA0Z8fabNTdMvkRto-ccdub7YQpxRBieA7Vl',
                      where: where
                  },
                styles: [{
                    where: "'price_fraction' <= 0.2",
                    polygonOptions: {
                            fillColor: "#3bb9c8",
                            fillOpacity: 0.6,
                            strokeColor: "#FFFFFF",
                            strokeWeight: 1
                        }
                    },
                    {
                    where: "'price_fraction' > 0.2 AND 'price_fraction' <= 0.4",
                    polygonOptions: {
                            fillColor: "#3ca0a0",
                            fillOpacity: 0.6,
                            strokeColor: "#FFFFFF",
                            strokeWeight: 1
                        }
                    },
                    {
                    where: "'price_fraction' > 0.4 AND 'price_fraction' <= 0.6",
                    polygonOptions: {
                            fillColor: "#179647",
                            fillOpacity: 0.6,
                            strokeColor: "#FFFFFF",
                            strokeWeight: 1
                        }
                    },
                    {
                    where: "'price_fraction' > 0.6 AND 'price_fraction' <= 0.8",
                    polygonOptions: {
                            fillColor: "#ffa300",
                            fillOpacity: 0.6,
                            strokeColor: "#FFFFFF",
                            strokeWeight: 1
                        }
                    },
                    {
                    where: "'price_fraction' > 0.8",
                    polygonOptions: {
                            fillColor: "#ED212E",
                            fillOpacity: 0.6,
                            strokeColor: "#FFFFFF",
                            strokeWeight: 1
                        }
                    }
                ]});
        }

        function initializeAutocomplete(map){
            var input = /** @type {!HTMLInputElement} */(
                 document.getElementById('pac-input'));
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                map: map,
                anchorPoint: new google.maps.Point(0, -29)
            });

            autocomplete.addListener('place_changed', function() {
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
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </body>
</html>
