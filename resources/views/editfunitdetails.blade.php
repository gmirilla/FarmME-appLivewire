<x-layouts.app>
    <div class="d-flex flex-row-reverse bd-highlight">
        <div class="p-2 bd-highlight" style="margin-right: 5px"><button  class="btn btn-primary" name=> Certified Crop Details </button> </div>
        <div class="p-2 bd-highlight" style="margin-right: 5px"><button disabled class="btn btn-primary" name="gofunits">Farm Plot(s) Details </button>
            </div>
            <div class="p-2 bd-highlight" style="margin-right: 5px"><button class="btn btn-primary"> Farm Details </button></div>
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
    <div>
            <div class="flex">
                <div class="mb-3" style="margin-right: 10px">
                    <label for="farmcode" class="form-label">Farm Code</label>
                    <input type="text" value={{$farm->farmcode}} id="farmcode"  name="farmcode" disabled class="form-control">
                    <input type="text" value={{$farm->id}} id="fid"  name="fid" hidden class="form-control">
                    </div>
 
            </div>
            <div class="mb-3">

            </div>
            <div class="mb-3">
                <table class="table table-striped">
                    <thead>
                        <th>Farm Unit ID</th>
                        <th>Farm Unit Area (ha)</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Action(s)</th>
                    </thead>
                    <tbody>
                        @forelse ($farmunits as $funit )
                        <form method="post" action='/farm/updatefunits'>
                            {{ csrf_field() }}
                        <tr>
                            <td><input type="text" value={{$farm->id}} id="fid"  name="fid" hidden class="form-control"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button type="submit" class="btn btn-primary">Update Details</button></td>
           
                        </tr> 
                        </form>
                        @empty
                        <td colspan=5 style="text-align: center"><b>NO FARM UNITS RECORDED !! </b></td>
                        @endforelse
                        <tr>
                            <td><button class="btn btn-primary"> Add Farm Unit(to do LOAD MODAL***)</button></td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div>
            </div>
    </div>
</x-layouts.app>