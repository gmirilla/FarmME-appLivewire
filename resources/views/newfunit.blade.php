<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
<x-layouts.app>

    <h4 class="text-center">{{$farm->farmcode}} : NEW FARM UNIT</h4>
    
    <form action="{{route('fusave')}}" method="post">
        @csrf
        
        <div class="row gy-2 gx-3 align-items-center">
            <div class="col-auto">
                <label for="farmcode" class="form-label">Farm Code</label>
                <input type="text" value="{{$farm->farmcode}}" id="farmcode"  name="farmcode" disabled class="form-control">
                <input type="text" value="{{$farm->id}}" id="fid"  name="fid" hidden class="form-control">
                </div>
            <div class="col-auto">
                    <label for="fuid" class="form-label">Farm ID**</label>
                    <input type="text" value="{{$farm->id}}" id="fuid"  name="fuid" disabled class="form-control">
                    </div>
            <div class="col-auto">
                        <label for="fuarea" class="form-label">Unit area (ha)*</label>
                        <input type="text" value="{{$farm->fuarea}}" id="fuarea"  name="fuarea"  required class="form-control">
                        </div>
            <div class="col-auto">
                            <label for="fulatitude" class="form-label">Latitude</label>
                            <input type="text" value="{{$farm->fulatitude}}" id="fulatitude"  name="fulatitude"   class="form-control">
                            </div>
            <div class="col-auto" >
                                <label for="fulongitude" class="form-label">Longitude</label>
                                <input type="text" value="{{$farm->fulongitude}}" id="fulongitude"  name="fulongitude"   class="form-control">
                                
            </div>
            <div class="col-auto" >
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </div>
        

    </form>

    <body onload="initMap()">
        
    </body>
    <div>
        <h4 class="text-center">Calculate Area</h4>
        <div class="flex">
            <div class="card" style="height: 550px; width: 68%;">
                <div class="card-body">
                    <div id="map" style="height: 500px; width: 100%;"></div>

                </div>
                
            </div>
            <div style="width: 30%; margin: 10px">
                <div class="card mb-3">
                    <div class="card-body">
                        <p><b>Latitude: </b><span id="latitude"></span></p>
                        <p><b>Longitude: </b><span id="longitude"></span></p>
                        <button class="btn btn-primary" onclick="getLocation()" data-toggle="tooltip" data-placement="right" title="Get Current Coordinates"><i class="fa fa-map-marker" aria-hidden="true"></i></button>
                        <button class="btn btn-success" onclick="setFarmLocation()" data-toggle="tooltip" data-placement="right" title="Set as Farm Coordinates"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                        <button class="btn btn-warning" onclick="calcPoly()" data-toggle="tooltip" data-placement="right" title="Calculate Area"><i class="fa fa-calculator" aria-hidden="true"></i></button>
                        <button class="btn btn-danger" onclick="resetPoly()" data-toggle="tooltip" data-placement="right" title="Clear all previous Coordinates"><i class="fa fa-eraser" aria-hidden="true"></i></button>   
                        </div>

                </div>

                <div class="card mb-3" >
                    <div class="card-title text-center"><b>PLOTTED COORDINATES</b></div>
                    <div class="card-body">
                        <p><span id="polycoord"></span></p>
                    </div>
                </div>
                <div  id="showArea" class="card" style="display:none">
                    <div class="card-title text-center"><b>FARM AREA</b></div>
                    <div class="card-body"></div>
                    <p><b><span id="farmareaha"></span> ha </b></p>
                    <p><b><span id="farmaream2"></span> m<sup>2</sup></b></p>
                    <button class="btn btn-success" onclick="setFarmarea()">Set as Farm Area</button>
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
        function setFarmarea()
        {
            document.getElementById("fuarea").value=document.getElementById("farmareaha").textContent;

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

            // Calculate the area in square meters and ha
                var area = (google.maps.geometry.spherical.computeArea(polygon.getPath())/10000)                ;
                var showarea=document.getElementById("showArea");
                
                document.getElementById("farmaream2").textContent= area;
                document.getElementById("farmareaha").textContent= (area*10000);
                showarea.style.display='block';

        }


    </script>
        <script>

        </script>

        


</x-layouts.app>