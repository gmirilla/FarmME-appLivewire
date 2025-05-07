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
            <div class="card" style="height: 500px; width: 60%;">
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
            <div>
                <p>Latitude: <span id="latitude"></span></p>
                <p>Longitude: <span id="longitude"></span></p>
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

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    document.getElementById("latitude").textContent = latitude;
                    document.getElementById("longitude").textContent = longitude;

                    initMap(latitude, longitude);
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

    </script>
</x-layouts.app>