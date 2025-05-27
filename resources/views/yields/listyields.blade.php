<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
<x-layouts.app>
        <div class="d-flex flex-row-reverse bd-highlight">
        <div class="p-2 bd-highlight" style="margin-right: 5px"><form method="get" action="{{route('list_yield')}}">
            @csrf
            <input type="text" name="farmid" id="farmid" hidden value="{{$farm->id}}">
            <button  class="btn btn-primary"  disabled name="getyield"> Yield Details </button> </form></div>
        <div class="p-2 bd-highlight" style="margin-right: 5px"> 
            <form action="{{route('listfunits')}}" method="get">
                @csrf
                <input type="text" name="fid" id="fid" hidden value="{{$farm->id}}">
                <button class="btn btn-primary" name="gofunits">Farm Plot(s) Details </button>
        </form></div>   
        <div class="p-2 bd-highlight" style="margin-right: 5px"><button disabled class="btn btn-primary"> Farm Details </button></div>
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

<div class="card">
    <div class="card-body">
        <form action="{{route('addyield')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-auto">
                    <label for="year" class="form-label">YEAR</label>
                    <select class="form form-select" name="year" id="year">
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label" for="farmcode">FARM CODE</label>
                    <input class="form-control" type="text" value="{{$farm->farmcode}}" disabled>
                    <input class="form-control" name="farmid" type="text" value="{{$farm->id}}" hidden>
                </div>
                <div class="col-auto">
                    <label class="form-label" for="farmunits" class="form-label">Plot ID</label>
                    <select class="form-select" name="farmunits" id="farmunits">
                        @forelse ($farmunits as $farmunit )
                            <option value="{{$farmunit->id}}">ID: {{$farmunit->id}}  | Area: {{$farmunit->fuarea}}</option>
                        @empty
                            <option value="">No Farm Plots Registered</option> 
                        @endforelse
                    </select>
                </div>
                <div class="col-auto"><label for="estyield" class="form-label">Est. Yield (Kgs)</label>
                    <input class="form-control" type="number" placeholder="Estimated Yield in Kgs"   required name="estyield" id="estyield">

                </div>
                <div class="col-auto"><label for="actualyield" class="form-label">Actual Yield (Kgs)</label>
                    <input class="form-control" type="number" placeholder="Actual Yield in Kgs"   name="actualyield" id="actualyield">
                </div>
            </div>
            <div class="col-auto mt-3">
                                    <button class="btn btn-primary" name="addyield"><i class="fa fa-plus" aria-hidden="true"></i></button>

            </div>
        </form>
    </div>
    <div class="card-header"><h4 class="card-title text-center">Annual Yield Details for  {{$farm->farmcode}}</h4></div>
    <div class="card-body">
        <table class="table table-striped" id="plotyields">
            <thead>
                <th>S/No</th>
                <th>Farm Code</th>
                <th>Plot Details</th>
                <th>Year</th>
                <th>Est Yield (Kgs)</th>
                <th>Actual Yield (Kgs)</th>
                <th>Action(s)</th>
            </thead>
            <tbody>
                @php
                    $counter=0;
                @endphp
                @forelse ($farmyields as $farmyield )
                    <tr>
                        <form action="{{route('updyield')}}" method="post">
                            @csrf
                        <td>@php
                            $counter=$counter+1;
                        @endphp
                       {{$counter}} 
                    </td>
                        <td>{{$farm->farmcode}}</td>
                        <td>ID:  | Area: </td>
                        <td>
                            {{$farmyield->year}}
                        </td>
                        <td>{{$farmyield->estyield}}</td>
                        
                        <td><input class="form-control" type="number" value="{{$farmyield->actualyield}}" name="actualyield">
                            <input type="number" value="{{$farmyield->id}}" hidden name="farmyieldid">
                            <input type="number" value="{{$farm->id}}" hidden name="farmid">
                            </td>
                        <td>
                            <button class="btn btn-success mr-2" name="updateyieldbtn"><i class="fa fa-check"></i></button>
                            <button class="btn btn-danger mr-2" name="deleteyieldbtn"><i class="fa fa-trash-o"></i></button>
                        </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td>NO FARM YIELDS REGISTERED</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
  new DataTable('#plotyields');
</script>
</x-layouts.app>    