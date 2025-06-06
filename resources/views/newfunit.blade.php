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
                @if (!empty($farmunit))
                <input type="text" value="{{$farmunit->id}}" id="farmunitid"  name="farmunitid" hidden class="form-control">
                @endif
                </div>
            <div class="col-auto">
                    <label for="fuid" class="form-label">Farm ID**</label>
                    <input type="text" value="{{$farm->id}}" id="fuid"  name="fuid" disabled class="form-control">
                    </div>
            <div class="col-auto">
                        <label for="fuarea" class="form-label">Unit area (ha)*</label>
                        @if (empty($farmunit))
                        <input type="text" value="" id="fuarea"  name="fuarea"  required class="form-control"> 
                        @else
                        <input type="text" value="{{$farmunit->fuarea}}" id="fuarea"  name="fuarea"  required class="form-control">
                        @endif
                        
                        </div>
            <div class="col-auto">
                            <label for="fulatitude" class="form-label">Latitude</label>
                            @if (empty($farmunit))
                            <input type="text" value="" id="fulatitude"  name="fulatitude"   class="form-control">
                            @else
                            <input type="text" value="{{$farmunit->fulatitude}}" id="fulatitude"  name="fulatitude"   class="form-control">
                            @endif
                            
                            </div>
            <div class="col-auto" >
                                <label for="fulongitude" class="form-label">Longitude</label>
                                @if (empty($farmunit))
                                <input type="text" value="" id="fulongitude"  name="fulongitude"   class="form-control">
                                @else
                                <input type="text" value="{{$farmunit->fulongitude}}" id="fulongitude"  name="fulongitude"   class="form-control">
                                @endif
             <div><textarea hidden id="polycoords" name="polycoords" class="form-control">
                @if (empty($farmunit->plot_coords))
                    // If no farm unit exists, provide default coordinates
                    null
                @else
                   {{$farmunit->plot_coords}}
                @endif</textarea></div>                   
            </div>
            <div class="col-auto" >
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </div>
        

    </form>

    <body 
    @if (!empty($farmunit) && !empty($farmunit->fulatitude) && !empty($farmunit->fulongitude))
    onload="initMap(parseFloat({{$farmunit->fulatitude}}), parseFloat({{$farmunit->fulongitude}}))"> 
    @else
    onload="initMap(0, 0)">
    @endif

    </body>
    <div>
        
        <h4 class="text-center">Calculate Area {{config('name')}}</h4>
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
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-bordered fs-6">
                            <thead>
                                <th>Lat</th><th>Long</th>
                            </thead>
                            <tbody id="formartcoordinates">

                            </tbody>
                        </table>
                        @if (!empty($farmunit))
                        <div><textarea id="polycoord" name="polycoords" class="form-control">{{$farmunit->plot_coords}}</textarea></div>                         
                        @endif
                            
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
    <script async src="https://maps.googleapis.com/maps/api/js?key={{env('MAP')}}&libraries=geometry,drawing"></script>
   <script>

        let polygonsArray = [];
                function initMap(latitude, longitude) {
            var userLocation = { lat: parseFloat(latitude), lng: parseFloat(longitude) };

            var map = new google.maps.Map(document.getElementById("map"), {
                center: userLocation,
                zoom: 17
            });

            new google.maps.Marker({
                position: userLocation,
                map: map,
                title: "You are here!"
            });

    const drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.POLYGON,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: ["polygon"],
    },
        polygonOptions: {
        fillColor: "#FF5733",
        fillOpacity: 0.5,
        strokeWeight: 2,
        strokeColor: "#C70039",
    },

  });

    //Load previously saved polygons from localStorage
const savedPolygons = document.getElementById("polycoords").value;
if (savedPolygons) {
    const data = JSON.parse(savedPolygons);
    console.log("Array Polygons:", data);

    var resultArray = JSON.parse(savedPolygons);
    var polyCoords = [];

    for (var i=0; i<resultArray.length; i++) {
        polyCoords[i] = new google.maps.LatLng(resultArray[i].lat, resultArray[i].lng);
    }

    //use the array as coordinates
 farmplot = new google.maps.Polygon({
         paths: polyCoords,
         strokeColor: '#ff0000',
         strokeOpacity: 0.8,
         strokeWeight: 1,
         fillColor: '#ff0000',
         fillOpacity: 0.30
});
farmplot.setMap(map);
};


  // Add the drawing manager to the map

            drawingManager.setMap(map);

            google.maps.event.addListener(drawingManager, 'polygoncomplete', function(event) {
                const polygon = event;
                const path = polygon.getPath();
                let polycoord = [];

                path.forEach(function(latLng) {
                    polycoord.push({ lat: latLng.lat(), lng: latLng.lng() });

                });

                 // Store the new polygon in the global array
        polygonsArray.push(polycoord);
        localStorage.setItem("polygonsData", JSON.stringify(polygonsArray)); // Persist in localStorage


                document.getElementById("polycoord").textContent = JSON.stringify(polycoord);
                 document.getElementById("polycoords").textContent = JSON.stringify(polycoord);
                document.getElementById("latitude").textContent = latitude;
                document.getElementById("longitude").textContent = longitude;
                console.log("Polygon coordinates:", polycoord);
                console.log("Json: ", JSON.stringify(polycoord));
            });


        }

        

        function savePoly()
            {   
            var polycoord=document.getElementById("polycoord").textContent;
            var latitude=document.getElementById("latitude").textContent;
            var longitude=document.getElementById("longitude").textContent;
            let tbody = document.getElementById("formartcoordinates");
            let newRow = document.createElement("tr");
            let newCelllat = document.createElement("td");
            let newCelllong = document.createElement("td");
            newCelllat.textContent = latitude;
            newCelllong.textContent = longitude; // Add content to the cells
            newRow.appendChild(newCelllat); 
            newRow.appendChild(newCelllong); // Add cell to row

            tbody.appendChild(newRow); // Add row to tbody



            if (polycoord.length == 0 ) {
                var result=polycoord.concat('{ "lat":  ', latitude ,', "lng": ', longitude,' }');
                let coordrow=document.getElementById("formartcoordinates").innerHTML+ "<tr><td></td></tr>";
                document.getElementById("polycoord").textContent=result;
                document.getElementById("formartcoordinates").innerHTML=coordrow;
                

            } else {

               var result=polycoord.concat(',','{ "lat":  ', latitude ,', "lng": ', longitude,' }');


            }

               // var result=polycoord.concat("{ lat: " , latitude ,", lng: ", longitude," }");
                document.getElementById("polycoord").textContent=result;
    
            }
            function resetPoly()
            {   
            document.getElementById("polycoord").textContent="";
            document.getElementById("formartcoordinates").textContent="";
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
            var polycoords=document.getElementById("polycoords").textContent;
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
                
                document.getElementById("farmaream2").textContent= area*10000;
                document.getElementById("farmareaha").textContent= area;
                showarea.style.display='block';

        }


    </script>


</x-layouts.app>