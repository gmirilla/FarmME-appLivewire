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
  font-size: 16px;
  font-weight: bold;
  color: #ffffff;
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
    <span style="font-size:0.8rem;">Note: Maize (M), Sorghum (S), Cowpea (C), Millet (ML), Sesame (Se), Groundnut (Gr)</span>
    
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
                        <input type="text" value="{{$farmunit->plotname}}" id="fuplotname"  name="fuplotname"   required class="form-control">
                        @endif
                    </div>
                     <div class="col-auto">
                    <label for="fucrop" class="form-label">Crop</label>
                      @if (empty($farmunit))
                        <input type="text" value="" id="fucrop"  name="crop"  required class="form-control"> 
                        @else
                        <input type="text" value="{{$farmunit->crop}}" id="fucrop"  name="crop"   required class="form-control">
                        @endif
                    </div>
                    <div class="col-auto">
                    <label for="fusystem" class="form-label">System</label>
                      @if (empty($farmunit))
                        <select name="system" id="optsystem" class="form-select">
                        @forelse ($misccodes as $misccode )
                            <option value="{{$misccode->id}}">{{$misccode->system}}</option>
                        @empty
                            <option disabled>No systems available</option>
                        @endforelse
                        </select>
                     @else
                        <input type="text" value="{{$farmunit->system}}" id="fusystem"  name="system"   required class="form-control">
                        @endif
                    </div>
                    <div class="col-auto">
                    <label for="fuspacing" class="form-label">Spacing (m)</label>
                      @if (empty($farmunit))
                        <input type="text" value="" id="fuspacing"  name="spacing"  required class="form-control"> 
                        @else
                        <input type="text" value="{{$farmunit->spacing}}" id="fuspacing"  name="spacing"   required class="form-control">
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
            </div>
            <div class="col-auto">
                <label for="mapfilepath" class="form-label">Saved Map</label>
                @if (empty($farmunit->imagefilepath))
                    <input type="text" disabled id="mapfilePath" name="mapfilePath" value="No Map saved" class="form-control">
                @else

                     <input type="text" disabled id="mapfilePath" name="mapfilePath" value="{{$farmunit->imagefilepath}}" class="form-control">
                     
                @endif              
            </div>
            <div class="col-auto" >

                @if (!empty($farmentrance))
                <input type="text" name="farmentranceid" hidden  value="{{$farmentrance->id}}">
                @endif
                @if (empty($farmunit->imagefilepath))
                     <input type="text"  hidden id="imagefilePath" name="imagefilePath" class="form-control" >
                     <button type="submit" disabled class="btn btn-primary" id="addfarmunit">Add</button>
                @else
                     <input type="text"  hidden id="imagefilePath" name="imagefilePath" class="form-control" value="{{$farmunit->imagefilepath}}">
                     <button type="submit" class="btn btn-primary"  id="addfarmunit">Add</button>
                @endif
            </div>
        </div>
        <div class="row">
            @if (!empty($farmunit->imagefilepath))
           <img src="{{Request::root().($farmunit->imagefilepath)}}" alt="" style="width: 80px; height: 80px;">
           @else
              <img id="uploadedImage" src="{{Request::root().('/storage/farmmap.png')}}" alt="No Map Uploaded" style="width: 80px; height: 80px;">
           @endif
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
            <div class="row" style="margin: 10px">
                <div class="card mb-3">
                    <div class="card-body">
                        <p><b>Latitude: </b><span id="latitude"></span></p>
                        <p><b>Longitude: </b><span id="longitude"></span></p>
                        <button class="btn btn-primary" onclick="addWaypoint()" data-toggle="tooltip" data-placement="right" title="Get Current Coordinates"><i class="fa fa-map-marker" aria-hidden="true"></i></button>
                        <button class="btn btn-success" onclick="setFarmLocation()" data-toggle="tooltip" data-placement="right" title="Set as Farm Coordinates"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                        <button class="btn btn-warning" onclick="showPolygonArea()" data-toggle="tooltip" data-placement="right" title="Calculate Area"><i class="fa fa-calculator" aria-hidden="true"></i>Calc</button>
                        <button class="btn btn-danger" onclick="resetPoly()" data-toggle="tooltip" data-placement="right" title="Clear all previous Coordinates"><i class="fa fa-eraser" aria-hidden="true"></i>Reset</button>  
                        <button class="btn btn-primary" id='startTrackingbtn' onclick="startTracking()">Start Tracking</button>
                        <button class="btn btn-danger" id='stopTrackingbtn' onclick="stopTracking()">Stop Tracking</button>
                        <button class="btn btn-primary" onclick="saveMap()">Save Map</button>
 
                        </div>



                                        <div class="card mb-3" >
                    <div class="card-title text-center" style="display: none;"><b>PLOTTED COORDINATES</b></div>
                    <div class="card-body table-responsive" style="display: none;">
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

                </div>


                <div  id="showArea" class="card" style="display:none">
                    <div class="card-title text-center"><b>FARM AREA</b></div>
                    <div class="card-body"></div>
                    <p><b><span id="farmareaha"></span> ha </b></p>
                    <p><b><span id="farmaream2"></span> m<sup>2</sup></b></p>
                    <button class="btn btn-success" onclick="setFarmarea()">Set as Farm Area</button>
                    
                </div>

            </div>
            <div class="card mb-3">
                <div class="card-header"><b>IMPORT MAP</b></div>
                <div class="card-body">
                        <div class="col-auto">
                        
                        <p><i>Use the file input field below to manually upload a map image. <br>
                            <b>Max File size:</b> 2MB, <b>Formats:</b> JPG,JPEG,PNG </i></p>
                        <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="importimage" id="importimage" class="form-control">
                        <button type="submit" class="btn btn-primary mt-2">Upload Map</button>
                        </form>

                        <div id="uploadStatus" class="alert alert-danger" style="margin-top: 10px;"></div>
                    </div>
                </div>
            </div>


        <div class="flex">
            <div class="card" style="height: 550px; width: 90%;">
                <div class="card-body">
                    <div id="map" hidden style="height: 500px; width: 100%;"></div>

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
        var waypointCoordinates = [];
        var infoWindow=null;
        var maparea;  //stores the value of the polygon area area



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
                fillColor: "#0b6623",
                fillOpacity: 0.8
            });

            polygon.setMap(map);



            // Listen for clicks on the map to add points
            map.addListener("click", function(event) {
                addPoint(event.latLng);
            });
        }
        
             function addPoint(location) {
            polygonCoordinates.push(location); // Add new coordinate
            polygon.setPaths(polygonCoordinates);// Update polygon shape
        }

function showPolygonArea() {
    if (!polygon) return console.warn("Polygon not ready.");

    const area = google.maps.geometry.spherical.computeArea(polygon.getPath().getArray());
    const areaha=area/10000;
    const center = getPolygonCenter(polygon.getPath());

        if (infoWindow) {
        infoWindow.close();
    }
    
    infoWindow = new google.maps.InfoWindow({
        content: `<div class="custom-info">Area: ${areaha.toFixed(3)} Ha</div>`,
        position: center
    });

    maparea=areaha.toFixed(3);

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
                let startTrackingbtn= document.getElementById('startTrackingbtn');
                let stopTrackingbtn= document.getElementById('stopTrackingbtn');
                startTrackingbtn.disabled=true;
                stopTrackingbtn.disabled=false;

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

        let startTrackingbtn = document.getElementById('startTrackingbtn');
        let stopTrackingbtn = document.getElementById('stopTrackingbtn');
        
        startTrackingbtn.disabled = false;
        stopTrackingbtn.disabled = true;

        if (watchID !== null) {
            navigator.geolocation.clearWatch(watchID); // Stop tracking
            watchID = null;
        }

        // Generate polygon from the tracked path
        if (pathCoordinates.length > 2) {  // Ensure there are enough points to form a polygon
            generatePolygon(pathCoordinates);
        } else {
            alert("Not enough data points to create a polygon.");
        }
    }
}
function addWaypoint() {
    if (pathCoordinates.length > 0) {
        let lastPoint = pathCoordinates[pathCoordinates.length - 1]; // Get last tracked position
        waypointCoordinates.push(lastPoint); // Save as waypoint
        console.log("Waypoint added: ", lastPoint);
    } else {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        let lastPoint=pathCoordinates[0]; 
        waypointCoordinates.push(lastPoint); // save as waypoint
        console.log("New Poly Waypoint added: ", lastPoint);
        //alert("No GPS position available yet.");
    }
}

    function successCallback(position) {
      const latitude = position.coords.latitude;
      const longitude = position.coords.longitude;
      let spanlatitude=document.getElementById('latitude'); 
      let spanlongitude=document.getElementById('longitude'); 
      console.log("Console Log: ", latitude, longitude);
      spanlongitude.textContent=longitude;
      spanlatitude.textContent=latitude;
      // Use latitude and longitude
    }

    function errorCallback(error) {
      // Handle errors, e.g., user denied location access
    }

function generatePolygon(coordinates) {
    if (polygon) {
        polygon.setMap(null); // Remove previous polygon
    }
        if (coordinates.length < 3) {
        alert("Not enough points to form a polygon.");
        return;
    }


    polygon = new google.maps.Polygon({
        paths: coordinates,
        strokeColor: "#90EE90",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#0b6623",
        fillOpacity: 0.8
    });

    polygon.setMap(map);

    // Display area of the polygon
    showPolygonArea();
}

function resetPoly() {
    // Remove the polygon if it exists
    if (polygon) {
        polygon.setMap(null);
    }

    // Close the InfoWindow if it's open
    if (infoWindow) {
        infoWindow.close();
    }

    // Reset coordinate arrays
    polygonCoordinates = [];
    pathCoordinates = [];

    // Reinitialize the polygon properly
    polygon = new google.maps.Polygon({
        paths: polygonCoordinates, // Use the correct variable
        strokeColor: "#90EE90",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#0b6623",
        fillOpacity: 0.8
    });

    // Ensure the polygon is added back to the map
    polygon.setMap(map);
}

    function setFarmLocation(){
        let fulatitude=document.getElementById('fulatitude'); 
        let fulongitude=document.getElementById('fulongitude'); 
        let spanlatitude=document.getElementById('latitude'); 
      let spanlongitude=document.getElementById('longitude'); 
      let fuarea=document.getElementById('fuarea');


        fulatitude.value=spanlatitude.textContent;
        fulongitude.value=spanlongitude.textContent;
        fuarea.value=maparea;
    

    }

    </script>
<script>
function saveMap() {
  const mapElement = document.getElementById('map');
  const overlay = document.getElementById('overlay');
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let imagePath=document.getElementById('imagefilePath');
    let mapFilePath=document.getElementById('mapfilePath');
    let addfarmunit=document.getElementById('addfarmunit');

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

        imagePath.value=data.path;
        mapFilePath.value=data.filename;
        addfarmunit.disabled=false; //enable the Add Farm unit button

        alert('Map saved successfully!')
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
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    let addfarmunit=document.getElementById('addfarmunit');
    addfarmunit.disabled=true; //disable the Add Farm unit button while uploading

 fetch('/upload-map', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        'Accept': 'application/json'
    },
    body: formData
})
.then(async response => {
    const data = await response.json();
    if (!response.ok) throw data;
    return data;
})
.then(data => {
    document.getElementById('uploadStatus').innerText = data.message || 'Upload successful!';
    if (data.filename) {
        document.querySelector('input[name="mapfilePath"]').value = data.path;
        document.querySelector('input[name="imagefilePath"]').value = data.path;
        addfarmunit.disabled=false; //enable the Add farm Unit button after succesful upload
        changeImage();
    }
})
.catch(error => {
    let message = 'Upload failed.';
    if (error.errors) {
        message = Object.values(error.errors).flat().join('\n');
    } else if (error.message) {
        message = error.message;
    }
    document.getElementById('uploadStatus').innerText = message;
    console.error(error);
});
})
</script>
<script>
  document.getElementById("importimage").addEventListener("change", function() {
    const file = this.files[0];
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes

    if (file && file.size > maxSize) {
      alert("File is too large! Please select a file smaller than 2MB.");
      this.value = ""; // Clear the input
    }
  });
</script>
<script>
  // Function to change the image
  function changeImage() {
    const newImageUrl = document.getElementById("imagefilePath").value;
    document.getElementById("uploadedImage").src = newImageUrl;
  }

</script>




</x-layouts.app>