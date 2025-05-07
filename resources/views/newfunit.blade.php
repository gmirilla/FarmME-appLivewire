<x-layouts.app>

    <h4 class="text-center">{{$farm->farmcode}} : NEW FARM UNIT</h4>
    
    <form action="" method="post">
        
        <div class="flex">
            <div class="mb-3" style="margin-right: 10px">
                <label for="farmcode" class="form-label">Farm Code</label>
                <input type="text" value="{{$farm->farmcode}}" id="farmcode"  name="farmcode" disabled class="form-control">
                <input type="text" value="{{$farm->id}}" id="fid"  name="fid" hidden class="form-control">
                </div>
            <div class="mb-3" style="margin-right: 10px">
                    <label for="fuid" class="form-label">Farm Unit ID</label>
                    <input type="text" value="{{$farm->id}}" id="fuid"  name="fuid" disabled class="form-control">
                    </div>
            <div class="mb-3" style="margin-right: 10px">
                        <label for="fuarea" class="form-label">Farm unit area (ha)*</label>
                        <input type="text" value="{{$farm->fuarea}}" id="fuarea"  name="fuarea"  required class="form-control">
                        </div>
            <div class="mb-3" style="margin-right: 10px">
                            <label for="fulatitude" class="form-label">Latitude (DD format)**</label>
                            <input type="text" value="{{$farm->fulatitude}}" id="fulatitude"  name="fulatitude"   class="form-control">
                            </div>
            <div class="mb-3" style="margin-right: 10px">
                                <label for="fulongitude" class="form-label">Longitude (DD format)**</label>
                                <input type="text" value="{{$farm->fulongitude}}" id="fulongitude"  name="fulongitude"   class="form-control">
                                
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Add</button>
    </form>

    <body onload="initMap()">
        
    </body>
    <div>
        <h4 class="text-center">Calculate Area</h4>
        <div class="flex">
            <div class="card" style="height: 500px; width: 70%;">
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
            <div>
                <div>
                <p>Latitude: <span id="latitude"></span></p>
                <p>Longitude: <span id="longitude"></span></p>
                <button class="btn btn-primary" onclick="getLocation()">Get Cordinates</button>
                <button class="btn btn-success" onclick="setFarmLocation()">Set as Farm Coordinates</button>
                <button class="btn btn-success" onclick="calcPoly()">Calculate Area</button>
                <button class="btn btn-danger" onclick="resetPoly()">Reset</button>
                </div>
                <div class="card" style="margin: 10px">
                    <h3>Plotted Coordinates</h3>
                    <p><span id="polycoord"></span></p>

                </div>
            </div>            

        </div>


    </div>
    <script async src="https://maps.googleapis.com/maps/api/js?key={{config('MAP_API_KEY')}}&libraries=geometry"></script>
    <script>
                function initMap(latitude, longitude) {
            var userLocation = { lat: latitude, lng: longitude };

            var map = new google.maps.Map(document.getElementById("map"), {
                center: userLocation,
                zoom: 17
            });

            new google.maps.Marker({
                position: userLocation,
                map: map,
                title: "You are here!"
            });
        }

        function savePoly()
            {   
            var polycoord=document.getElementById("polycoord").textContent;
            var latitude=document.getElementById("latitude").textContent;
            var longitude=document.getElementById("longitude").textContent;

            if (polycoord.length == 0 ) {
                var result=polycoord.concat('{ "lat":  ', latitude ,', "lng": ', longitude,' }');
                document.getElementById("polycoord").textContent=result;
            } else {

               var result=polycoord.concat(',','{ "lat":  ', latitude ,', "lng": ', longitude,' }');


            }

               // var result=polycoord.concat("{ lat: " , latitude ,", lng: ", longitude," }");
                document.getElementById("polycoord").textContent=result;
    
            }
            function resetPoly()
            {   
            document.getElementById("polycoord").textContent="";
            //TO DO handle potential trailing ","
            
    
            }    
    

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    document.getElementById("latitude").textContent = latitude;
                    document.getElementById("longitude").textContent = longitude;

                    initMap(latitude, longitude);
                    savePoly();


                }, function(error) {
                    console.error("Error getting location: ", error);
                    alert("Geolocation failed. Please enable location services.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Call the function to get the user's location
        getLocation();

        function setFarmLocation()
        {
            document.getElementById("fulatitude").value=document.getElementById("latitude").textContent;
            document.getElementById("fulongitude").value=document.getElementById("longitude").textContent;

        }
        function calcPoly()
        {
            // Define the polygon coordinates
            var polycoords=document.getElementById("polycoord").textContent;
            var jsonPolyString="["+polycoords+"]";
            //Convert coordinate strings into Array of Json Objects
            const jsonArray = JSON.parse(jsonPolyString);

            var polygonCoords = jsonArray;

            // Create the polygon
                var polygon = new google.maps.Polygon({
                paths: polygonCoords,
                strokeColor: "#008000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#008000",
                fillOpacity: 0.35
            });

            polygon.setMap(map);

            // Calculate the area in square meters
                var area = (google.maps.geometry.spherical.computeArea(polygon.getPath())/10000)                ;
                alert("Polygon Area: " + area.toFixed(2) + " ha");               


        }


    </script>
        <script>

        </script>

        


</x-layouts.app>