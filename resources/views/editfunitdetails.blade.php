<x-layouts.app>
    <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight" style="margin-right: 5px"><form method="get" action="{{route('list_yield')}}">
            @csrf
            <input type="text" name="farmid" id="farmid" hidden value="{{$farm->id}}">
            <button  class="btn btn-primary" name="getyield"> Yield Details </button> </form></div>
        <div class="p-2 bd-highlight" style="margin-right: 5px"><button disabled class="btn btn-primary" name="gofunits">Farm Plot(s) Details </button>
            </div>
            <div class="p-2 bd-highlight" style="margin-right: 5px"><form method="get" action="{{route('edit_farmunit')}}">
                @csrf
                <input type="text" name="farmid" id="farmid" hidden value="{{$farm->id}}">
                <button class="btn btn-primary"> Farm Details </button></form></div>
        </div>
        

    @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif
<div class="col-md col-xl col-sm py-md-3 pl-md-5t  fs-6, fs-md-5, fs-lg-5, fs-xl-1" style="font-size: 0.9rem">
<div class="card">
        <div class="card-header">

            <div class="flex">
                <div class="mb-3" style="margin-right: 10px">
                    <label for="farmcode" class="form-label">Farm Code</label>
                    <input type="text" value={{$farm->farmcode}} id="farmcode"  name="farmcode" disabled class="form-control">
                    <input type="text" value={{$farm->id}} id="fid"  name="fid" hidden class="form-control">
                    </div>
                    <div class="mb-3" style="margin-right: 10px">
                        <label for="nooffarmunits" class="form-label">No of Units</label>
                        <input type="text" value="{{$farm->nooffarmunits}}" id="nooffarmunits"  name="nooffarmunits" disabled class="form-control">
                        </div>
                    <div class="mb-3" style="margin-right: 10px">
                            <label for="farmarea" class="form-label">Total Area (Ha)</label>
                            <input type="text" value="{{$farm->farmarea}}" id="farmarea"  name="farmarea" disabled class="form-control">
                            </div>
        
                </div>

    
            </div>
 
            <div class="mb-3 card-body table-responsive">
                <table class="table table-striped">
                    <thead>
                        <th>Farm Unit ID</th>
                        <th>Farm Unit Area(ha)</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Action(s)</th>
                    </thead>
                    <tbody>

                        @forelse ($farmunits as $funit )
                        <form method="post" action="{{route('editfu')}}">
                            {{ csrf_field() }}
                        <tr>
                            <td>
                                {{$funit->id}}
                                <input type="text" value={{$funit->id}} id="fid"  name="fid" hidden class="form-control">
                                <input type="text" value={{$farm->id}} id="farmid"  name="farmid" hidden class="form-control">
                                <input type="text" value={{$farm->id}} id="farmcode"  name="farmid" hidden class="form-control"></td>
                            <td>{{$funit->fuarea}}</td>
                            <td>{{$funit->fulatitude}}</td>
                            <td>{{$funit->fulongitude}}</td>
                            <td><button type="submit" class="btn btn-success" name="updatefu">Update Details</button>
                                <button type="submit" class="btn btn-danger" name="deletefu">Delete Plot</button>

                            
                            </td>
           
                        </tr> 
                        </form>
                        @empty
                        <td colspan=5 style="text-align: center"><b>NO FARM UNITS RECORDED !! </b></td>
                        @endforelse
                        <tr>
                            <td>
                                <form action="{{route('newfunit')}}" method="get">

                                    <input type="text" name="farmid" id="farmid2" hidden value="{{$farm->id}}">
                                    <button class="btn btn-primary" name="newfunit"> Add Farm Unit</button>

                                </form>
                                </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div>
            </div>
    </div>
</div>
    
</x-layouts.app>