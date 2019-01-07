<!DOCTYPE html>
<html>
    <head>
        <title>คำนวนพื้นที่ - ปากันสุข app</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.86">
        <meta charset="utf-8">
        <style>
            html, body {
                height: 100%;
                margin: 0px;
                padding: 25px
            }
            
            #map-canvas{
                height: 100%;
                margin: 0px;
                padding: 0px
            }

            .controls {
                margin-top: 10px;
                border: 1px solid transparent;
                border-radius: 2px 0 0 2px;
                box-sizing: border-box;
                -moz-box-sizing: border-box;
                height: 32px;
                outline: none;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            }

            #pac-input {
                background-color: #fff;
                font-family: Roboto;
                font-size: 15px;
                font-weight: 300;
                margin-left: 12px;
                padding: 0 11px 0 13px;
                text-overflow: ellipsis;
                width: 300px;
            }

            #pac-input:focus {
                border-color: #4d90fe;
            }

            .pac-container {
                font-family: Roboto;
            }

            #type-selector {
                color: #fff;
                background-color: #4d90fe;
                padding: 5px 11px 0px 11px;
            }

            #type-selector label {
                font-family: Roboto;
                font-size: 13px;
                font-weight: 300;
            }
            #target {
                width: 345px;
            }


            #content_web{
                width:800px;
                height:600px;
            }
            @media screen and (max-width: 750px) {
                #content_web{
                    width:100%;
                    height:82%;
                }
                html, body {
                    height: 100%;
                    margin: 0px;
                    padding: 0px
                }
            }
            @media screen and (max-width: 500px) {
                #content_web{
                    width:100%;
                    height:85%;
                }
                html, body {
                    height: 100%;
                    margin: 0px;
                    padding: 0px
                }
                #pac-input {
                    width:285px;
                }
            }
            

        </style>
        <script type="text/javascript" src="jquery-1.10.2.min.js"></script>        
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places,geometry&key=AIzaSyAfObMpP7VjqLlwooDn5Dy5pgUdQCRq53Q"></script>

        <script>
                        
            var map;
            var counter;
            var geocoder;
            // Create a meausure object to store our markers, MVCArrays, lines and polygons
            var measure = {
                mvcLine: new google.maps.MVCArray(),
                mvcPolygon: new google.maps.MVCArray(),
                mvcMarkers: new google.maps.MVCArray(),
                line: null,
                polygon: null
            };
            // When the document is ready, create the map and handle clicks on it
            
            var test_jsonfm = JSON.parse('{ "coords":{"latitude":"19.3581238","longitude":"99.7779964"}}');
            
            jQuery(document).ready(function () {
                
                showposition(test_jsonfm);
                
            });
            
            function showposition(getLatLng) {
                
                var lat=getLatLng.coords.latitude;
                var lon=getLatLng.coords.longitude;
                
                var latLng=new google.maps.LatLng(lat, lon);
                
                geocoder = new google.maps.Geocoder();
                map = new google.maps.Map(document.getElementById("map-canvas"), {
                    zoom: 15,
                    center: latLng,
                    mapTypeId: google.maps.MapTypeId.HYBRID,
                    //mapTypeId: google.maps.MapTypeId.ROADMAP, //view แผนที่
                    draggableCursor: "crosshair" // Make the map cursor a crosshair so the user thinks they should click something
                });
                google.maps.event.addListener(map, "click", function (evt) {
                    // When the map is clicked, pass the LatLng obect to the measureAdd function
                    measureAdd(evt.latLng);
                });
                var input = document.getElementById('pac-input');
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', map);
                google.maps.event.addListener(autocomplete, 'place_changed', function () {//ทำงานเมื่อคลิกที่รายการค้นหา

                    //infowindow.close();                 

                    place = autocomplete.getPlace();
                    if (!place.geometry) {
                        //console.log('s1');
                        //alert('ไม่พบสถานที่ที่ค้นหา');
                        geocodeAddress(geocoder, map);
                        return;
                    }
                    if (place.geometry.viewport) {
                        console.log('s2' + place.geometry.location);
                        map.fitBounds(place.geometry.viewport);
                        map.setZoom(15);
                        placeMarker(place.geometry.location, map, 'df');
                        counter++;
                    } else {
                        console.log('s3' + place.geometry.location);
                        map.setCenter(place.geometry.location); //Set Center ของแผนที่ตามตำแหน่งที่ค้นหา
                        map.setZoom(15); //กำหนดซูมแผนที่ขยายเป็น 15
                        placeMarker(place.geometry.location, map, 'df');
                        counter++;
                    }

                });
            }
            
            
            
            var x=$("#demo");
//            function getLocation()
//              {            
//
//              if (navigator.geolocation)
//                {                                   
//                    navigator.geolocation.getCurrentPosition(showposition,showError);                
//                }
//              else{x.innerHTML="Geolocation is not supported by this browser.";}
//              }



                var apiGeolocationSuccess = function(position) {
                alert("API geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
            };

            var tryAPIGeolocation = function() {
                jQuery.post( "https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyAfObMpP7VjqLlwooDn5Dy5pgUdQCRq53Q", function(success) {
                //jQuery.post( "http://ip-api.com/json", function(success) {
                    //apiGeolocationSuccess({coords: {latitude: success.location.lat, longitude: success.location.lng}});
                    console.log(success.lat+" , "+success.lon);
                    
                    //var test_jsonfm8881 = JSON.parse('{ "coords":{"latitude":"'+success.lat+'","longitude":"'+success.lon+'"}}');        
                    //showposition(test_jsonfm8881);
                                        
                    
              })
              .fail(function(err) {
                alert("API Geolocation error! \n\n"+err);
              });
            };

            var browserGeolocationSuccess = function(position) {
                alert("Browser geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
            };

            var browserGeolocationFail = function(error) {
              switch (error.code) {
                case error.TIMEOUT:
                  alert("Browser geolocation error !\n\nTimeout.");
                  break;
                case error.PERMISSION_DENIED:
                  if(error.message.indexOf("Only secure origins are allowed") == 0) {
                    tryAPIGeolocation();
                  }
                  break;
                case error.POSITION_UNAVAILABLE:
                  alert("Browser geolocation error !\n\nPosition unavailable.");
                  break;
              }
            };

            var tryGeolocation = function() {
              if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    showposition,
                  browserGeolocationFail,
                  {maximumAge: 50000, timeout: 20000, enableHighAccuracy: true});
              }
            };
            
              
              
              function showError(error)
          {
          switch(error.code) 
            {
            case error.PERMISSION_DENIED:
              x.innerHTML="User denied the request for Geolocation."
              break;
            case error.POSITION_UNAVAILABLE:
              x.innerHTML="Location information is unavailable."
              break;
            case error.TIMEOUT:
              x.innerHTML="The request to get user location timed out."
              break;
            case error.UNKNOWN_ERROR:
              x.innerHTML="An unknown error occurred."
              break;
            }
          }
            
            
            function measureAdd(latLng) {

                // Add a draggable marker to the map where the user clicked
                var marker = new google.maps.Marker({
                    map: map,
                    position: latLng,
                    draggable: true,
                    raiseOnDrag: false,
                    title: "Drag me to change shape"
                            //icon: new google.maps.MarkerImage("/images/demos/markers/measure-vertex.png", new google.maps.Size(9, 9), new google.maps.Point(0, 0), new google.maps.Point(5, 5))
                });
                // Add this LatLng to our line and polygon MVCArrays
                // Objects added to these MVCArrays automatically update the line and polygon shapes on the map
                measure.mvcLine.push(latLng);
                measure.mvcPolygon.push(latLng);
                // Push this marker to an MVCArray
                // This way later we can loop through the array and remove them when measuring is done
                measure.mvcMarkers.push(marker);
                // Get the index position of the LatLng we just pushed into the MVCArray
                // We'll need this later to update the MVCArray if the user moves the measure vertexes
                var latLngIndex = measure.mvcLine.getLength() - 1;
                // When the user mouses over the measure vertex markers, change shape and color to make it obvious they can be moved
//                google.maps.event.addListener(marker, "mouseover", function () {
//                    marker.setIcon(new google.maps.MarkerImage("/images/demos/markers/measure-vertex-hover.png", new google.maps.Size(15, 15), new google.maps.Point(0, 0), new google.maps.Point(8, 8)));
//                });

                // Change back to the default marker when the user mouses out
//                google.maps.event.addListener(marker, "mouseout", function () {
//                    marker.setIcon(new google.maps.MarkerImage("/images/demos/markers/measure-vertex.png", new google.maps.Size(9, 9), new google.maps.Point(0, 0), new google.maps.Point(5, 5)));
//                });

                // When the measure vertex markers are dragged, update the geometry of the line and polygon by resetting the
                //     LatLng at this position
                google.maps.event.addListener(marker, "drag", function (evt) {
                    measure.mvcLine.setAt(latLngIndex, evt.latLng);
                    measure.mvcPolygon.setAt(latLngIndex, evt.latLng);
                });
                // When dragging has ended and there is more than one vertex, measure length, area.
                google.maps.event.addListener(marker, "dragend", function () {
                    if (measure.mvcLine.getLength() > 1) {
                        measureCalc();
                    }
                });
                // If there is more than one vertex on the line
                if (measure.mvcLine.getLength() > 1) {

                    // If the line hasn't been created yet
                    if (!measure.line) {

                        // Create the line (google.maps.Polyline)
                        measure.line = new google.maps.Polyline({
                            map: map,
                            clickable: false,
                            strokeColor: "#FF0000",
                            strokeOpacity: 1,
                            strokeWeight: 3,
                            path: measure.mvcLine
                        });
                    }

                    // If there is more than two vertexes for a polygon
                    if (measure.mvcPolygon.getLength() > 2) {

                        // If the polygon hasn't been created yet
                        if (!measure.polygon) {

                            // Create the polygon (google.maps.Polygon)
                            measure.polygon = new google.maps.Polygon({
                                clickable: false,
                                map: map,
                                fillOpacity: 0.25,
                                strokeOpacity: 0,
                                paths: measure.mvcPolygon
                            });
                        }

                    }

                }

                // If there's more than one vertex, measure length, area.
                if (measure.mvcLine.getLength() > 1) {
                    measureCalc();
                }

            }


            function measureCalc() { //function show ตัวเลขวัดพื้นที่

                // Use the Google Maps geometry library to measure the length of the line
                var length = google.maps.geometry.spherical.computeLength(measure.line.getPath());
                jQuery("#span-length").text(length.toFixed(1))

                // If we have a polygon (>2 vertexes in the mvcPolygon MVCArray)
                if (measure.mvcPolygon.getLength() > 2) {
                    // Use the Google Maps geometry library to measure the area of the polygon
                    var area = google.maps.geometry.spherical.computeArea(measure.polygon.getPath());
                    jQuery("#span-area").text(area.toFixed(1));


                    //post cal from php
                    $.ajax({
                        type: "POST",
                        url: "calculator_th.php",
                        async: true,
                        data: {
                            "tarangmet": area.toFixed(1)
                        },
                        success: function (success) {
                            console.log(success);
                            $("#show_cal_th").text(success);
//                            success = success.trim().split("/");
//                            var once = success[0];
//                            var two = success[1];
//                            if (once == 1) {
//
//                            }
                            
                        }
                    });

                }

            }

            function measureReset() {

                // If we have a polygon or a line, remove them from the map and set null
                if (measure.polygon) {
                    measure.polygon.setMap(null);
                    measure.polygon = null;
                }
                if (measure.line) {
                    measure.line.setMap(null);
                    measure.line = null
                }

                // Empty the mvcLine and mvcPolygon MVCArrays
                measure.mvcLine.clear();
                measure.mvcPolygon.clear();
                // Loop through the markers MVCArray and remove each from the map, then empty it
                measure.mvcMarkers.forEach(function (elem, index) {
                    elem.setMap(null);
                });
                measure.mvcMarkers.clear();
                jQuery("#span-length,#span-area").text(0);
                
                
                $("#show_cal_th").text('');
            }





            function placeMarker(position, map, ftype) {

                map.panTo(position);
            }


            function geocodeAddress(geocoder, resultsMap) {
                var address = $("#pac-input").val();
                geocoder.geocode({'address': address}, function (results, status) {
                    if (status === 'OK') {
                        //resultsMap.setCenter(results[0].geometry.location);
                        // var marker = new google.maps.Marker({
                        //   map: resultsMap,
                        //   position: results[0].geometry.location
                        // });

                        map.setZoom(15);
                        //check ว่าเป็นค้นแบบ lat lng
                        var chk_stxt_latlng = address.indexOf(",");
                        if (chk_stxt_latlng != -1) {
                            var latlngStr = address.split(',', 2);
                            var n = parseFloat(latlngStr[0]);
                            var chkfloat = Number(n) === n && n % 1 !== 0;
                            if (chkfloat == true) {

                                var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
                                console.log(results[0].geometry.location);
                                console.log(latlng);
                                placeMarker(latlng, map, 'ez');
                                counter++;
                                //end check ว่าเป็นค้นแบบ lat lng   
                            } else {
                                placeMarker(results[0].geometry.location, map, 'df');
                                counter++;
                            }
                        } else {
                            placeMarker(results[0].geometry.location, map, 'df');
                            counter++;
                        }


                    } else {
                        alert('ไม่สามารถค้นหาสถานที่นี้ได้: ' + status);
                        return false;
                    }
                });
//                var input = $("#pac-input").val();
//                var latlngStr = input.split(',', 2);
//                var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
//                geocoder.geocode({'location': latlng}, function(results, status) {
//                  if (status === 'OK') {
//                    if (results[1]) {
//                      map.setZoom(15);
//                      placeMarker(latlng, map);
//                      counter++;
//                      
//                    } else {
//                      window.alert('No results found');
//                    }
//                  } else {
//                    window.alert('Geocoder failed due to: ' + status);
//                  }
//                });
            }


        </script>
    </head>
    <body>

        <div id="content_web" >
            <input id="pac-input" class="controls" type="text" placeholder="ช่องค้นหา" autocomplete="off" style="z-index: 1; position: absolute; margin-left: 123px; margin-top: 10px;">
            
            <div style="display: flex;align-items: center; box-sizing: border-box;text-align: center;padding: 5px 10px;background-color:#FFCF45;border-radius: 2px;position: absolute;z-index: 9;margin-top: 60px;margin-left: 10px;cursor: pointer;" 
                 onclick="/*getLocation();*/ tryGeolocation();" >
                <img class="animat_btn antm8881" style="height: 16px;" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgNTYxIDU2MSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTYxIDU2MTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJncHMtZml4ZWQiPgoJCTxwYXRoIGQ9Ik0yODAuNSwxNzguNWMtNTYuMSwwLTEwMiw0NS45LTEwMiwxMDJjMCw1Ni4xLDQ1LjksMTAyLDEwMiwxMDJjNTYuMSwwLDEwMi00NS45LDEwMi0xMDIgICAgQzM4Mi41LDIyNC40LDMzNi42LDE3OC41LDI4MC41LDE3OC41eiBNNTA3LjQ1LDI1NUM0OTQuNywxNDcuOSw0MTAuNTUsNjMuNzUsMzA2LDUzLjU1VjBoLTUxdjUzLjU1ICAgIEMxNDcuOSw2My43NSw2My43NSwxNDcuOSw1My41NSwyNTVIMHY1MWg1My41NUM2Ni4zLDQxMy4xLDE1MC40NSw0OTcuMjUsMjU1LDUwNy40NVY1NjFoNTF2LTUzLjU1ICAgIEM0MTMuMSw0OTQuNyw0OTcuMjUsNDEwLjU1LDUwNy40NSwzMDZINTYxdi01MUg1MDcuNDV6IE0yODAuNSw0NTlDMTgxLjA1LDQ1OSwxMDIsMzc5Ljk1LDEwMiwyODAuNVMxODEuMDUsMTAyLDI4MC41LDEwMiAgICBTNDU5LDE4MS4wNSw0NTksMjgwLjVTMzc5Ljk1LDQ1OSwyODAuNSw0NTl6IiBmaWxsPSIjMDAwMDAwIi8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />
                <span class="animat_btn antm8881" style="margin-left: 0px;" ></span>
            </div>
            
            <div id="map-canvas" ></div>
            <div id="show_cal_th" ></div>
            <div>ความยาว (เส้นแดง): <span id="span-length">0</span> m - พื้นที่ (สี่ดำ): <span id="span-area">0</span> m² - <a href="javascript:measureReset();">Reset</a></div>            
        </div>
        
        <p class="intro" id="demo" style="display:block;" ></p>



        <div style="position:relative;top:100px;">
            <h4 class="post-title">รวมหน่วยมาตราวัดทางคณิตศาสตร์</h4>
            
            <p><span style="color: #ff00ff;"><span style="text-decoration: underline;"><b>มาตรา</b></span><span style="text-decoration: underline;"><b>วัดพื้นที่ไทย</b></span></span></p>
            <p><span style="color: #0000ff;">1 ตารางวา &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp;4 ตารางเมตร</span></p>
            <p><span style="color: #0000ff;">1 งาน &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp;400 ตารางเมตร</span></p>
            <p><span style="color: #0000ff;">1 งาน &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp;100 ตารางวา</span></p>
            <p><span style="color: #0000ff;">1 ไร่ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp;1600 ตารางเมตร</span></p>
            <p><span style="color: #0000ff;">1 ไร่ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp; 4 งาน</span></p>
            <p><span style="color: #0000ff;">1 ไร่ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;=&nbsp; &nbsp; &nbsp;400 ตารางวา</span></p>
            <p><span style="color: #0000ff;">1 ไร่ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; 0.4 เอเคอร์</span></p>
            
            <p><span style="text-decoration: underline; color: #ff00ff;"><b>มาตราวัดระยะทางไทย</b></span></p>
            <p><span style="color: #0000ff;">400 เส้น &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp; 1 โยชน์</span></p>
            <p><span style="color: #0000ff;">&nbsp;&nbsp; 1 เส้น &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;= &nbsp; &nbsp; &nbsp;20 วา</span></p>
            <p><span style="color: #0000ff;">&nbsp;&nbsp; 1 วา &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp;4 ศอก</span></p>
            <p><span style="color: #0000ff;">&nbsp;&nbsp; 1 ศอก&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp; 2 คืบ</span></p>
            <p><span style="color: #0000ff;">&nbsp;&nbsp; 1 คืบ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;=&nbsp; &nbsp; &nbsp; &nbsp;12 นิ้ว</span></p>
            
            <p><span style="color: #ff00ff;"><span style="text-decoration: underline;"><b>ระยะทาง</b></span><span style="text-decoration: underline;"><b> &amp; </b></span><span style="text-decoration: underline;"><b>ความยาว</b></span> <span style="text-decoration: underline;"><b>(</b></span><span style="text-decoration: underline;"><b>distance</b></span> <span style="text-decoration: underline;"><b>&amp; Length)</b></span></span></p>
            <p><span style="color: #0000ff;">1 กิโลเมตร (km)&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;=&nbsp; &nbsp; &nbsp; &nbsp;1000 เมตร (m)</span></p>
            <p><span style="color: #0000ff;">1 เมตร (m) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;=&nbsp; &nbsp; &nbsp; 100 เซนติเมตร (cm)</span></p>
            <p><span style="color: #0000ff;">1 เซนติเมตร (cm) &nbsp; &nbsp; &nbsp;&nbsp;=&nbsp; &nbsp; &nbsp; 10 มิลลิเมตร (mm)</span></p>
            <p><span style="color: #0000ff;">1 นิ้ว (inch) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp;2.54 เซนติเมตร (cm)</span></p>
            <p><span style="color: #0000ff;">1 ฟุต (ft) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp;30.48 เซนติเมตร (cm)</span></p>
            <p><span style="color: #0000ff;">1 กิโลเมตร (km) &nbsp; &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp; 0.6214 ไมล์ (mile)</span></p>
            
            <p><span style="color: #ff00ff;"><span style="text-decoration: underline;"><b>น้ำหนัก</b></span><span style="text-decoration: underline;"><b>/</b></span><span style="text-decoration: underline;"><b>มวล</b></span><span style="text-decoration: underline;"><b>/</b></span><span style="text-decoration: underline;"><b>การตวง</b></span></span></p>
            <p><span style="color: #0000ff;">1 ตัน (ton)&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;=&nbsp; &nbsp; &nbsp;1000 กิโลกรัม (kg)</span></p>
            <p><span style="color: #0000ff;">1 กิโลกรัม (kg) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;=&nbsp; &nbsp; &nbsp;1000 กรัม (g) </span></p>
            <p><span style="color: #0000ff;">1 กิโลกรัม (kg)&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;= &nbsp; &nbsp; 10 ชีด</span></p>
            <p><span style="color: #0000ff;">1 ขีด &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;= &nbsp; &nbsp; 100 กรัม (g)</span></p>
            <p><span style="color: #0000ff;">1 กิโลกรัม (kg)&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp;2.2 ปอนด์ (lb)</span></p>
            <p><span style="color: #0000ff;">1 ปอนด์ (lb) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp;454 กรัม (g)</span></p>
            <p><span style="color: #0000ff;">1 ปอนด์ (lb) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp;16 ออนซ์ (oz)</span></p>
            <p><span style="color: #0000ff;">1 ลิตร (l) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp;1000 มิลลิลิตร (ml)</span></p>
            <p><span style="color: #0000ff;">1 มิลลิลิตร (ml) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;=&nbsp; &nbsp; &nbsp; 1 ซีซี (cc)</span></p>
            <p><span style="color: #0000ff;">1 ช้อนโต๊ะ&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp;3 ช้อนชา (ช.ช.)</span></p>
            <p><span style="color: #0000ff;">1 ช้อนโต๊ะ&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp;15 มิลลิลิตร (ml)</span></p>
            <p><span style="color: #0000ff;">1 ช้อนชา&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp; 5 มิลลิลิตร (ml)</span></p>
            
                        
            <p><span style="text-decoration: underline; color: #ff00ff;"><b>มาตราตวงของไทย</b></span></p>
            <p><span style="color: #0000ff;">1 เกวียน&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp;100&nbsp; ถัง</span></p>
            <p><span style="color: #0000ff;">1 ถัง&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; =&nbsp; &nbsp; &nbsp; &nbsp; 20&nbsp; ลิตร</span></p>
            <p><span style="color: #0000ff;">1 แกลลอน&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; =&nbsp; &nbsp; &nbsp; &nbsp;46&nbsp; ลิตร</span></p>
            
        </div>
          
</body>
</html>