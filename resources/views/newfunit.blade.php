<x-layouts.app>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    #overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background-color: rgba(0,0,0,0.4);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        z-index: 9999;
    }
    .loader-circle {
        border: 6px solid #f3f3f3; border-top: 6px solid #3498db;
        border-radius: 50%; width: 50px; height: 50px;
        animation: spin 1s linear infinite; margin-top: 10px;
    }
    .loader-text { color: white; font-size: 1.2rem; font-weight: bold; text-align: center; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    .custom-info { font-size: 16px; font-weight: bold; color: #ffffff; padding: 8px; background-color: transparent; }
    .gm-style .gm-style-iw-c  { background-color: transparent !important; overflow: hidden !important; box-shadow: none !important; }
    .gm-style-iw-d              { background-color: transparent !important; overflow: hidden !important; }
    .gm-style-iw-chr            { display: none !important; }
    .gm-style .gm-style-iw-tc  { display: none !important; }

    .page-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
    .page-title   { font-size: 1.25rem; font-weight: 700; color: #212529; margin: 0; }
    .map-preview  { width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6; }
    .map-preview.has-map { border-color: #198754; }
</style>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── PAGE TOOLBAR ─────────────────────────────────────────────────────── --}}
<div class="page-toolbar">
    <h5 class="page-title">
        <i class="fa fa-map-o me-2 text-success"></i>
        <span class="text-success font-monospace">{{ $farm->farmcode }}</span>
        &mdash; {{ !empty($farmunit) ? 'Edit Farm Plot' : 'New Farm Plot' }}
    </h5>
</div>

<p class="text-muted small mb-3">
    <i class="fa fa-info-circle me-1"></i>
    Crop abbreviations: Maize (M), Sorghum (S), Cowpea (C), Millet (ML), Sesame (Se), Groundnut (Gr)
</p>

{{-- ── CARD 1: PLOT DETAILS ────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-3">
    <div class="card-header" style="background:#f8f9fa;border-left:4px solid #198754;">
        <i class="fa fa-leaf me-2 text-success"></i><strong>Plot Details</strong>
    </div>
    <div class="card-body">
        <form action="{{ route('fusave') }}" method="post">
            @csrf

            {{-- Hidden fields --}}
            <input type="hidden" id="fid" name="fid" value="{{ $farm->id }}">
            @if (!empty($farmunit))
                <input type="hidden" name="farmunitid" id="farmunitid" value="{{ $farmunit->id }}">
            @endif
            @if (!empty($farmentrance))
                <input type="hidden" name="farmentranceid" value="{{ $farmentrance->id }}">
            @endif
            @if (empty($farmunit->imagefilepath))
                <input type="hidden" id="imagefilePath" name="imagefilePath">
            @else
                <input type="hidden" id="imagefilePath" name="imagefilePath" value="{{ $farmunit->imagefilepath }}">
            @endif

            {{-- Row 1: Identification --}}
            <div class="row g-3 mb-3">
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-semibold small">Farm Code</label>
                    <input type="text" value="{{ $farm->farmcode }}" name="farmcode" disabled
                           class="form-control form-control-sm bg-light">
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-semibold small">Farm ID</label>
                    <input type="text" value="{{ $farm->id }}" id="fuid" name="fuid" disabled
                           class="form-control form-control-sm bg-light">
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-semibold small">
                        Plot Name <span class="text-danger">*</span>
                    </label>
                    @if (empty($farmunit))
                        <input type="text" id="fuplotname" name="fuplotname" required
                               class="form-control form-control-sm">
                    @else
                        <input type="text" value="{{ $farmunit->plotname }}" id="fuplotname" name="fuplotname" required
                               class="form-control form-control-sm">
                    @endif
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-semibold small">
                        Crop <span class="text-danger">*</span>
                    </label>
                    @if (empty($farmunit))
                        <input type="text" id="fucrop" name="crop" required
                               class="form-control form-control-sm">
                    @else
                        <input type="text" value="{{ $farmunit->crop }}" id="fucrop" name="crop" required
                               class="form-control form-control-sm">
                    @endif
                </div>
            </div>

            {{-- Row 2: Agronomic details --}}
            <div class="row g-3 mb-3">
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-semibold small">
                        System <span class="text-danger">*</span>
                    </label>
                    @if (empty($farmunit))
                        <select name="system" id="optsystem" class="form-select form-select-sm">
                            @forelse ($misccodes as $misccode)
                                <option value="{{ $misccode->id }}">{{ $misccode->system }}</option>
                            @empty
                                <option disabled>No systems available</option>
                            @endforelse
                        </select>
                    @else
                        <input type="text" value="{{ $farmunit->system }}" id="fusystem" name="system" required
                               class="form-control form-control-sm">
                    @endif
                </div>
                <div class="col-sm-6 col-md-2">
                    <label class="form-label fw-semibold small">
                        Spacing (m) <span class="text-danger">*</span>
                    </label>
                    @if (empty($farmunit))
                        <input type="text" id="fuspacing" name="spacing" required
                               class="form-control form-control-sm">
                    @else
                        <input type="text" value="{{ $farmunit->spacing }}" id="fuspacing" name="spacing" required
                               class="form-control form-control-sm">
                    @endif
                </div>
                <div class="col-sm-6 col-md-2">
                    <label class="form-label fw-semibold small">
                        Unit Area (ha) <span class="text-danger">*</span>
                    </label>
                    @if (empty($farmunit))
                        <input type="text" id="fuarea" name="fuarea" required
                               class="form-control form-control-sm">
                    @else
                        <input type="text" value="{{ $farmunit->fuarea }}" id="fuarea" name="fuarea" required
                               class="form-control form-control-sm">
                    @endif
                </div>
                <div class="col-sm-6 col-md-2">
                    <label class="form-label fw-semibold small">Latitude</label>
                    @if (empty($farmunit))
                        <input type="text" id="fulatitude" name="fulatitude"
                               class="form-control form-control-sm">
                    @else
                        <input type="text" value="{{ $farmunit->fulatitude }}" id="fulatitude" name="fulatitude"
                               class="form-control form-control-sm">
                    @endif
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-semibold small">Longitude</label>
                    @if (empty($farmunit))
                        <input type="text" id="fulongitude" name="fulongitude"
                               class="form-control form-control-sm">
                    @else
                        <input type="text" value="{{ $farmunit->fulongitude }}" id="fulongitude" name="fulongitude"
                               class="form-control form-control-sm">
                    @endif
                </div>
            </div>

            {{-- Row 3: Map path + Submit --}}
            <div class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label class="form-label fw-semibold small">Saved Map</label>
                    @if (empty($farmunit->imagefilepath))
                        <input type="text" disabled id="mapfilePath" name="mapfilePath"
                               value="No map saved — use the map tool below to capture one"
                               class="form-control form-control-sm text-muted fst-italic">
                    @else
                        <input type="text" disabled id="mapfilePath" name="mapfilePath"
                               value="{{ $farmunit->imagefilepath }}"
                               class="form-control form-control-sm">
                    @endif
                </div>
                <div class="col-md-3">
                    @if (empty($farmunit->imagefilepath))
                        <button type="submit" disabled class="btn btn-success w-100" id="addfarmunit">
                            <i class="fa fa-check me-1"></i> Save Plot
                        </button>
                    @else
                        <button type="submit" class="btn btn-success w-100" id="addfarmunit">
                            <i class="fa fa-check me-1"></i> Save Plot
                        </button>
                    @endif
                </div>
            </div>
        </form>

        {{-- Map preview --}}
        <div class="mt-3">
            @if (!empty($farmunit->imagefilepath))
                <img src="{{ Request::root() . ($farmunit->imagefilepath) }}"
                     alt="Farm Map" class="map-preview has-map">
            @else
                <img id="uploadedImage"
                     src="{{ Request::root() . '/storage/farmmap.png' }}"
                     alt="No Map Uploaded" class="map-preview">
            @endif
        </div>
    </div>
</div>

{{-- ── LOADING OVERLAY ─────────────────────────────────────────────────── --}}
<div id="overlay" style="display: none;">
    <div class="loader-text">Saving map...</div>
    <div class="loader-circle"></div>
</div>

{{-- ── CARD 2: CALCULATE AREA ──────────────────────────────────────────── --}}
<div class="card shadow-sm mb-3">
    <div class="card-header" style="background:#f8f9fa;border-left:4px solid #198754;">
        <i class="fa fa-calculator me-2 text-success"></i><strong>Calculate Area</strong>
    </div>
    <div class="card-body">

        {{-- GPS readout --}}
        <div class="d-flex gap-4 mb-3 p-2 bg-light rounded small">
            <div>
                <span class="text-muted fw-bold">Latitude:</span>
                <span id="latitude" class="fw-bold ms-1"></span>
            </div>
            <div>
                <span class="text-muted fw-bold">Longitude:</span>
                <span id="longitude" class="fw-bold ms-1"></span>
            </div>
        </div>

        {{-- Map controls --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <button class="btn btn-primary btn-sm" onclick="addWaypoint()"
                    data-toggle="tooltip" title="Get Current Coordinates">
                <i class="fa fa-map-marker me-1"></i>Waypoint
            </button>
            <button class="btn btn-success btn-sm" onclick="setFarmLocation()"
                    data-toggle="tooltip" title="Set as Farm Coordinates">
                <i class="fa fa-pencil me-1"></i>Set Location
            </button>
            <button class="btn btn-warning btn-sm" onclick="showPolygonArea()"
                    data-toggle="tooltip" title="Calculate Area">
                <i class="fa fa-calculator me-1"></i>Calc Area
            </button>
            <button class="btn btn-danger btn-sm" onclick="resetPoly()"
                    data-toggle="tooltip" title="Clear all previous Coordinates">
                <i class="fa fa-eraser me-1"></i>Reset
            </button>
            <button class="btn btn-primary btn-sm" id="startTrackingbtn" onclick="startTracking()">
                <i class="fa fa-play me-1"></i>Start Tracking
            </button>
            <button class="btn btn-danger btn-sm" id="stopTrackingbtn" onclick="stopTracking()">
                <i class="fa fa-stop me-1"></i>Stop Tracking
            </button>
            <button class="btn btn-success btn-sm" onclick="saveMap()">
                <i class="fa fa-save me-1"></i>Save Map
            </button>
        </div>

        {{-- Plotted coordinates (hidden) --}}
        <div style="display: none;">
            <div class="fw-bold small text-center mb-1">PLOTTED COORDINATES</div>
            <div class="table-responsive" style="max-height:150px;overflow-y:auto;">
                <table class="table table-sm table-striped table-bordered fs-6">
                    <thead><tr><th>Lat</th><th>Long</th></tr></thead>
                    <tbody id="formartcoordinates"></tbody>
                </table>
            </div>
            @if (!empty($farmunit))
                <div class="mt-1">
                    <textarea id="polycoord" name="polycoords"
                              class="form-control form-control-sm" rows="2">{{ $farmunit->plot_coords }}</textarea>
                </div>
            @endif
        </div>

        {{-- Calculated area result (shown by JS) --}}
        <div id="showArea" class="card border-success mt-3" style="display:none">
            <div class="card-body py-2">
                <div class="fw-bold small text-center text-success mb-2">FARM AREA</div>
                <p class="mb-1"><b><span id="farmareaha"></span> ha</b></p>
                <p class="mb-2"><b><span id="farmaream2"></span> m<sup>2</sup></b></p>
                <button class="btn btn-success btn-sm" onclick="setFarmarea()">
                    <i class="fa fa-check me-1"></i>Set as Farm Area
                </button>
            </div>
        </div>

        {{-- Google Map --}}
        <div id="map" style="height:500px;width:100%;margin-top:1rem;border-radius:8px;border:1px solid #dee2e6;"></div>
    </div>
</div>

{{-- ── CARD 3: IMPORT MAP ───────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-4">
    <div class="card-header" style="background:#f8f9fa;border-left:4px solid #198754;">
        <i class="fa fa-upload me-2 text-success"></i><strong>Import Map</strong>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">
            <i>Manually upload a map image instead of using the live map tool.<br>
            <b>Max file size:</b> 2 MB &nbsp;|&nbsp; <b>Formats:</b> JPG, JPEG, PNG</i>
        </p>
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-9">
                    <input type="file" name="importimage" id="importimage" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fa fa-upload me-1"></i>Upload Map
                    </button>
                </div>
            </div>
        </form>
        <div id="uploadStatus" class="alert mt-2" style="display:none;"></div>
    </div>
</div>

{{-- ── SCRIPTS ──────────────────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.13/dist/html-to-image.min.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{env('MAP')}}&libraries=geometry,drawing&callback=initMap"></script>
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
    const statusEl = document.getElementById('uploadStatus');
    statusEl.classList.remove('alert-danger');
    statusEl.classList.add('alert-success');
    statusEl.style.display = 'block';
    statusEl.innerText = data.message || 'Upload successful!';
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
    const statusEl = document.getElementById('uploadStatus');
    statusEl.classList.remove('alert-success');
    statusEl.classList.add('alert-danger');
    statusEl.style.display = 'block';
    statusEl.innerText = message;
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
