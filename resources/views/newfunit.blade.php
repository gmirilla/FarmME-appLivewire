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
             <div><textarea hidden id="polycoords" name="polycoords" class="form-control">
                @if (empty($farmunit->plot_coords))
                    // If no farm unit exists, provide default coordinates
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
        var map;
        var pathCoordinates = [];
        var polyline;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 20,
                center: { lat: 12.0022, lng: 8.5919 } // Example starting point (Kano, Nigeria)
            });

            polyline = new google.maps.Polyline({
                path: pathCoordinates,
                geodesic: true,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 3
            });

            polyline.setMap(map);

            trackLocation();
        }

        function trackLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(function(position) {
                    var newPoint = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    pathCoordinates.push(newPoint);
                    polyline.setPath(pathCoordinates);

                    map.setCenter(newPoint);
                }, function(error) {
                    console.log("Error getting location: ", error);
                });
            } else {
                alert("Geolocation is not supported by your browser");
            }
        }
    </script>


</x-layouts.app>