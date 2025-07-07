<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@php
    Auth::check();
    $user=Auth::user();
@endphp
<x-layouts.app>
<div>  
<div>
    <div class="container-sm" >
        <div class="row">
            <div class="col card mb-3 mr-3">
                <div class="card-header">
                    <h4 class="text-center">FARM DETAILS</h4>
                </div>
                <div class="card-body">
                <div class="mb-3">
                <label>FARM NAME</label>

                <input type="text" readonly class="form-control" value="{{$farm->farmname}}">
                </div>
                <div class="mb-3">
                    <label>FARM CODE</label>

                    <input type="text" readonly class="form-control" value="{{$farm->farmcode}}">
                </div>
                <div class="mb-3">
                    <label>COMMUNITY</label>
                    <input type="text" readonly class="form-control" value="{{$farm->community}}">
                </div>
                <div class="mb-3">
                    <label>FARM AREA {{$farm->measurement}}</label>
                    <input type="text" readonly class="form-control" value="{{number_format($farm->farmarea,2) }}">
                </div>
                <div class="mb-3">
                    <label>FIELD STAFF</label>
                    <div class="d-flex">
                   @if (!$farm->name)
    <input type="text" class="form-control" disabled value="No Field Staff Assigned">
@else
    <input type="text" class="form-control" disabled value="{{ $farm->name }}">
@endif
    @if ($authuser->roles=='ADMINISTRATOR')
        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                data-bs-target="#staffModal" data-bs-whatever="">
            <i class="fa fa-pencil-square-o"></i>
        </button>
    @endif
</div>

                </div>
                <div class="mb-3">
                    <form action="{{route('generatecontract')}}" method="get">
                    <select name="cdseason" id=""  required class="form-select mb-2">
                        @forelse ( $seasons as $season )
                             @if (!empty($season))
                                <option value="{{$season}}">{{$season}}</option>
                             @endif     
                        @empty
                            <option value="">No Farm entrance conducted</option>
                        @endforelse
                    </select>
                    <input type="text" name="farmid" hidden value="{{$farm->id}}">
                    @if (!empty($season))
                        <a href="{{route('viewcontract', ['farmid' => $farm->id, 'cdseason' => $season])}}" class="btn btn-success">View Contract Agreement</a>
                        <button type="submit" class="btn btn-success">Download Contract Agreement</button>
                    @else

                    <a href="{{route('viewcontract', ['farmid' => $farm->id, 'cdseason' => $season])}}" disabled class="btn btn-success">View Contract Agreement</a>
                        <button type="submit" class="btn btn-success" disabled>Download Contract Agreement</button>
                    @endif
                    </form>
                </div>
                </div>
            </div>
            <div class="col-auto card mb-3">
                <div class="card-header mb-3"> 
                    <h4 class="text-center" >INSPECTION SUMMARY</h4>
                </div>
                
                <div class="card-body mb-3">
                <div class="row mb-3">
                    
                    <div class="col-auto">
                        <label for="farmstate">FARM STATUS</label>
                        </div>
                        <div class="d-flex">
                            <div>
                        <input type="text" readonly class="form-control" value="{{$farm->farmstate}}" id=farmstate>
                    </div>
                    <div class="ml-2">
                     @if ($authuser->roles=='ADMINISTRATOR')
                        <button type="button" class="btn btn-success py-2" data-bs-toggle="modal"
                        data-bs-target="#farmModal" data-bs-whatever=""><i class="fa fa-pencil-square-o"></i></button> 
                    @endif
                    </div>
            <div class="ml-2" style="border: #5D4037 solid 1px; padding: 5px; border-radius: 5px;">
    
            @if (!empty($farmerpicture))
           <img src="{{Request::root().('/storage/'.$farmerpicture->farmerpicture)}}" alt="" style="width: 100px; height: 100px;">
           @else
              <img id="uploadedImage" src="{{Request::root().('/storage/farmmap.png')}}" alt="No Photo Uploaded" style="width: 80px; height: 80px;">
           @endif
                </div>
   
                </div> 
  

                </div>
                <div class="mb-3">
                    <div>
                        FARM MEASURMENTS:
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td><b>House Latidude</b></td>
                                <td>{{$farm->latitude}} </td>
                                <td><b>House Longitude</b></td>
                                <td>{{$farm->longitude}}</td>
                            </tr>
                            <tr>
                                <td><b>Farm Size (Ha)</b></td>
                                <td>@if (!empty($farm->farmarea))
                                    {{number_format($farm->farmarea,2)}} Ha
                                @endif 
                                    </td>
                                <td><b> No of Plots</b></td>
                                <td> @if (!empty($farm->nooffarmunits))
                                    {{ $farm->nooffarmunits}}            
                                @endif
                            </td>
                            </tr>
                            <tr>   
                                <td>Name of Variety </td>
                                <td> @if (!empty($farm->cropvariety))
                                    {{ $farm->cropvariety}}            
                                @endif</td>
                                <td> Certified Crop</td>
                                <td> @if (!empty($farm->crop))
                                    {{ $farm->crop}}            
                                @endif
                            </td>                            
                            </tr>
                        </table>
                        <form action="/fu/edit" method="get">
                            <input hidden type="text" name="farmid" value="{{$farm->id}}">
                        <button type="submit" class="btn btn-success" name="editfunits"> Edit Farm Details</button>
                        </form>
                        
                    </div>
                </div>
                </div>
                <div class="mb-3 table-responsive">
                    <label>LAST INSPECTION</label>
                    <table class="table table-bordered" style="border:3px">
                        <thead>
                            <th>Date</th>
                            <th>InspectionID</th>
                            <th>Score</th>
                            <th>State</th>
                            <th>Comment</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$farm->lastinspection}} </td>
                                @if ($lastreport !=null)
                                <td>{{$lastreport->id}}</td>
                                <td>
                                    @php
                                        $farmscore=number_format((($lastreport->score/$farmreports[0]->max_score)*100),2)
                                    @endphp
                                    @switch($farmscore)
                                        @case($farmscore<=49.99)
                                            <b style="color: red">{{$farmscore}} %</b>
                                            @break
                                        @case($farmscore>49.99 && $farmscore<=69.99)
                                            <b style="color: orange">{{$farmscore}} %</b>
                                            @break
                                        @case($farmscore>69.99)
                                            <b style="color: green">{{$farmscore}} %</b>
                                            @break
                                            
                                    @endswitch
                                </td>
                                <td>{{$lastreport->inspectionstate }}</td>
                                @else
                                <td>No Reports done</td>
                                <td>N/A</td>
                                <td>N/A</td>
                                @endif
                                

                                <td>
                                    @if(!empty($lastreport->comments))
                                    {{$lastreport->comments}}
    
                                    @endif
                                    </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mb-3">
                    <label>NEXT INSPECTION</label>
                    <input type="text" readonly class="form-control" value="{{$farm->nextinspection}}">
                    <form action="" method="POST">
                        <input type="number" name="farmid" hidden value="{{$farm->id}}">
                

                    </form>
                </div>
 
            </div>
          </div>
          <div class="row table-responsive">
            <h4 class="h4" style="text-align: center; background-color:#5D4037; color: white">INSPECTION RECORDS</h4>
            <table class="table table-striped table-hover" id="farms">
                <thead>
                    <tr>
                    <th>Season</th>
                    <th>Report Name</th>
                    <th>Score</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Comment</th>
                    <th>Actions</th>
                    </tr>
                </thead>  
                <tbody> 
             @forelse ($farmreports as $farmreport)
             <tr>
              <td>{{$farmreport->season}}</td>  
             <td>{{$farmreport->reportname}}</td>

             <td>{{number_format(($farmreport->score / $farmreport->max_score *100),2)}}%</td> 
             <td>{{$farmreport->created_at}}</td>
             <td>{{$farmreport->inspectionstate}}</td>
             <td><textarea class="form-control" name="comments" id="" cols="20" rows="3">{{$farmreport->comments}}</textarea></td>
             <td>
                <form action="/iapprove" method="post">
                    @csrf
                    <input type="hidden" name="iid" value="{{$farmreport->iid}}">
                    <button name="viewsheet" type="submit" class="btn btn-success"><i class="fa fa-eye"></i></button>
                </form>
            
            <form action="{{route('continue')}}" method="POST">
            @csrf
            <input type="text" value="{{$farm->id}}" name="farmid" hidden>
            <input type="text" value="{{$farmreport->iid}}" name="inspectionid" hidden>
            <button class="btn btn-danger" name='printsheet' data-toggle="tooltip" data-placement="right" 
            title="Download Inspection PDF" style="margin: 3px"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
           
            

        </form>
                </td>
             </tr>
                 @empty
                <tr> <td>NO INSPECTIONS CARRIED OUT</td>
                <td></td><td></td><td></td><td></td><td></td></tr>
                 @endforelse
            </tbody>
            </table>
          </div>

    </div>
</div>
</div>
<!-- Show Staff Modal -->
<div class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="staffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Select Staff to be assigned</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/farm/assignstaff" method="post">
            @csrf

            <div class="row">
                <label>FARM NAME</label>
                <input disabled type="text" readonly class="form-control" value="{{$farm->farmname}}">
                <input hidden type="text" name="id" class="form-control" value="{{$farm->farmcode}}">
            </div>
            <div class="row">
                <label>STAFF</label>
                <select class="form-select" name="staffid" id="" class="form-control">
                    @forelse ($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>  
                    @empty
                        <option value="NIL">NO USERS ON SYSTEM</option>
                    @endforelse
                </select>
            </div>
            <button class="btn btn-success" name="assignstaff" >Submit</button>
        </form>

          </div>
</div>
    </div>
</div>

<div class="modal fade" id="farmModal" tabindex="-1" aria-labelledby="farmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            UPDATE FARM STATUS
          </div>
          <div class="modal-body">
          <form action="/farm/assignstaff" method="post">
            @csrf
            <div class="row">
                <label>FARM NAME</label>
                <input disabled type="text" readonly class="form-control" value="{{$farm->farmname}}">
                <input hidden type="text" name="id" class="form-control" value="{{$farm->farmcode}}">
            </div>
            <div class="row">
                <label>FARM STATUS</label>
                <select class="form-select" name="farmid" id="" class="form-control">
                      <option value="PENDING">PENDING</option> 
                        <option value="ACTIVE">ACTIVE</option>
                        <option value="REMEDIAL">REMEDIAL</option>
                        <option value="DISABLED">DISABLED</option>
                </select>
            </div>
            <button class="btn btn-success" name="farmstatus" style="margin-top: 8px" >Submit</button>
          </form>
        </div>
        </div>
    </div>
</div>
<script>
    new DataTable('#farms');
</script>
</x-layouts.app>