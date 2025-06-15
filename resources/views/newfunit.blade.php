<style>
    #overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0,0,0,0.4); /* semi-transparent black */
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.loader-circle {
  border: 6px solid #f3f3f3;
  border-top: 6px solid #3498db;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
  margin-top: 10px;
}

.loader-text {
  color: white;
  font-size: 1.2rem;
  font-weight: bold;
  text-align: center;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.custom-info {
  font-size: 14px;
  font-weight: bold;
  color: #ef250b;
  padding: 8px;
  background-color: transparent;

}
.gm-style .gm-style-iw-c{
    background-color:transparent !important;
    overflow:hidden  !important;
    box-shadow: none !important;
}
.gm-style-iw-d{
    background-color:transparent !important;
    overflow:hidden  !important;
}
.gm-style-iw-chr{
    display: none !important;
}
.gm-style .gm-style-iw-tc{
    display:none !important;
}
</style>
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
                    <label for="fuid" class="form-label">Plot Name</label>
                      @if (empty($farmunit))
                        <input type="text" value="" id="fuplotname"  name="fuplotname"  required class="form-control"> 
                        @else
                        <input type="text" value="{{$farmunit->fuplotname}}" id="fuplotname"  name="fuplotname"   required class="form-control">
                        @endif
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
             <div><textarea id="polycoords" name="polycoords" class="form-control">
                @if (empty($farmunit->plot_coords))
                   
                    null
                @else
                   {{$farmunit->plot_coords}}
                @endif</textarea></div>                   
            </div>
            <div class="col-auto" >

                @if (!empty($farmentrance))
                <input type="text" name="farmentranceid" hidden  value="{{$farmentrance->id}}">
                @endif
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
<script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.13/dist/html-to-image.min.js"></script>
    </body>
    <div>
        
        <h4 class="text-center">Calculate Area {{config('name')}}</h4>
<div id="overlay" style="display: none;">
  <div class="loader-text">Saving map...</div>
  <div class="loader-circle"></div>
</div>


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
                        <button class="btn btn-warning" onclick="showPolygonArea()" data-toggle="tooltip" data-placement="right" title="Calculate Area"><i class="fa fa-calculator" aria-hidden="true"></i></button>
                        <button class="btn btn-danger" onclick="resetPoly()" data-toggle="tooltip" data-placement="right" title="Clear all previous Coordinates"><i class="fa fa-eraser" aria-hidden="true"></i></button>  
                        <button class="btn btn-primary" onclick="startTracking()">Start Tracking</button>
                        <button class="btn btn-danger" onclick="stopTracking()">Stop Tracking</button>
                        <button class="btn btn-primary" onclick="saveMap()">Save Map</button>
 
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
    <script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.13/dist/html-to-image.min.js"></script>
    <script>
        var map; // Store the map instance
        var pathCoordinates = []; // Array to store GPS coordinates
        var polyline; // Polyline for the walking path
        var trackingActive = false; // Flag to control tracking
        var watchID = null; // Stores the geolocation watch ID
        var polygon;
        var polygonCoordinates = []; // Stores clicked points


        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 20,
                center: { lat: 12.0022, lng: 8.5919 } // Starting location (Kano, Nigeria)
            });

            polyline = new google.maps.Polyline({
                path: pathCoordinates,
                geodesic: true,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2
            });

            polyline.setMap(map);

                polygon = new google.maps.Polygon({
                paths: polygonCoordinates,
                strokeColor: "#90EE90",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#90EE90",
                fillOpacity: 0.35
            });

            polygon.setMap(map);

            // Listen for clicks on the map to add points
            map.addListener("click", function(event) {
                addPoint(event.latLng);
            });
        }
        
             function addPoint(location) {
            polygonCoordinates.push(location); // Add new coordinate
            polygon.setPath(polygonCoordinates); // Update polygon shape
        }

function showPolygonArea() {
    if (!polygon) return console.warn("Polygon not ready.");

    const area = google.maps.geometry.spherical.computeArea(polygon.getPath());
    const areaha=area/10000;
    const center = getPolygonCenter(polygon.getPath());
    
    const infoWindow = new google.maps.InfoWindow({
        content: `<div class="custom-info">Area: ${areaha.toFixed(2)} Ha</div>`,
        position: center
    });

    infoWindow.open(map);
}

function getPolygonCenter(path) {
    const bounds = new google.maps.LatLngBounds();
    path.forEach(function(latlng) {
        bounds.extend(latlng);
    });
    return bounds.getCenter();
}



        function startTracking() {
            if (!trackingActive) {
                trackingActive = true;

                if (navigator.geolocation) {
                    watchID = navigator.geolocation.watchPosition(
                        function(position) {
                            var newPoint = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };

                            pathCoordinates.push(newPoint);
                            polyline.setPath(pathCoordinates);
                            document.getElementById('polycoords').textContent=JSON.stringify(pathCoordinates);
                            console.log(pathCoordinates);

                            map.setCenter(newPoint);
                        },
                        function(error) {
                            console.log("Error getting location: ", error);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 5000,
                            maximumAge: 5000
                        }
                    );
                } else {
                    alert("Geolocation is not supported by your browser.");
                }
            }
        }

        function stopTracking() {
            if (trackingActive) {
                trackingActive = false;

                if (watchID !== null) {
                    navigator.geolocation.clearWatch(watchID); // Stop tracking
                    watchID = null;
                }
            }

        }

    </script>
<script>
function saveMap() {
  const mapElement = document.getElementById('map');
  const overlay = document.getElementById('overlay');
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  if (!mapElement) {
    console.error('Map element not found!');
    return;
  }

  overlay.style.display = 'flex'; // Show the overlay

  htmlToImage.toPng(mapElement)
    .then((dataUrl) => {
      return fetch('/save-map-image', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ image: dataUrl })
      });
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Server responded with status ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      console.log('✅ Backend Response:', data);
      alert('Map saved successfully!');
    })
    .catch((err) => {
      console.error('❌ Failed to save map image:', err);
      alert('Error saving map. Please try again.');
    })
    .finally(() => {
      overlay.style.display = 'none'; // Hide the overlay
    });
}
</script>
<script>
  let currentMarker = null;

  function initMap(lat, lng) {
    const position = { lat, lng };
    map = new google.maps.Map(document.getElementById("map"), {
      center: position,
      zoom: 15
    });

    // Drop a pin at the current location
    currentMarker = new google.maps.Marker({
      position,
      map,
      title: "You are here!"
    });
  }

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;

          if (!map) {
            initMap(lat, lng);
          } else {
            const newPosition = { lat, lng };

            // Move the map and drop/update the pin
            map.setCenter(newPosition);

            if (currentMarker) {
              currentMarker.setPosition(newPosition);
            } else {
              currentMarker = new google.maps.Marker({
                position: newPosition,
                map,
                title: "You are here!"
              });
            }
          }
        },
        (error) => {
          console.error("Geolocation error:", error);
          alert("Unable to retrieve your location.");
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0
        }
      );
    } else {
      alert("Geolocation is not supported by this browser.");
    }
  }
</script>




</x-layouts.app>