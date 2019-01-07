<!DOCTYPE html>
<html>
    <head>
        <title>คำนวนพื้นที่ - ปากันสุข app</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.86">
        <meta charset="utf-8">

        <script type="text/javascript" src="jquery-1.10.2.min.js"></script>        
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places,geometry&key=AIzaSyAfObMpP7VjqLlwooDn5Dy5pgUdQCRq53Q"></script>

        <script>

            var x = $("#demo");
            function getLocation()
            {

                if (navigator.geolocation)
                {
                    navigator.geolocation.getCurrentPosition(showposition, showError);
                }
                else {
                    x.innerHTML = "Geolocation is not supported by this browser.";
                }
            }


            function showposition(getLatLng) {

                var lat = getLatLng.coords.latitude;
                var lon = getLatLng.coords.longitude;
                
                x.innerHTML = '{ "coords":{"latitude":"'+lat+'","longitude":"'+lon+'"}}';

            }

            function showError(error)
            {
                switch (error.code)
                {
                    case error.PERMISSION_DENIED:
                        x.innerHTML = "User denied the request for Geolocation."
                        break;
                    case error.POSITION_UNAVAILABLE:
                        x.innerHTML = "Location information is unavailable."
                        break;
                    case error.TIMEOUT:
                        x.innerHTML = "The request to get user location timed out."
                        break;
                    case error.UNKNOWN_ERROR:
                        x.innerHTML = "An unknown error occurred."
                        break;
                }
            }


        </script>
    </head>
    <body>

        <p class="intro" id="demo" style="display:block;" ></p>

    </body>
</html>