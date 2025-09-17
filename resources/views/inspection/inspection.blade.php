<x-layouts.app>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div>
        <div class="d-flex flex-row-reverse mb-3">
            <!-- 
    <form id='search' name='searchforfarm' method='get' action='farm'>
        <input type="text" placeholder="Search for Farm" name="farmsearch" style="border: solid 1px grey ; padding:3px">
        <input disabled type="submit" class="btn btn-success" value="Search **TO DO">
      <form>
    -->

<a class="btn btn-success mt-3" href='inspection/new'>New Inspection</a>
</div>
<div class="card">
    <div class="card-header"><h4>Assigned Inspections</h4></div>
<div class="card-body table-responsive">    
<table class="table table-striped display table-sm table-sm" id="inspectiondt">

    <thead>

        <th>Type</th>
        <th>Farm</th>
        <th>Updated date</th>
        <th>Status</th>
        <th>Result</th>
        <th>Action</th>
    </thead>
    <tbody>
    @forelse ($inspections as $inspection)
    <td>{{$inspection->reportname}}</td>
    <td>{{$inspection->farmcode}} | {{$inspection->farmname}}</td>
    <td>{{$inspection->updated_at}}</td>
    <td>
        @switch($inspection->inspectionstate)
        @case('APPROVED')
       <b style="color:green"> {{$inspection->inspectionstate}}</b>
            @break
        @case('CONDITIONAL')
            <b style="color: orange"> {{$inspection->inspectionstate}}</b>
                 @break
        @case('REJECTED')
                 <b style="color: red"> {{$inspection->inspectionstate}}</b>
                      @break
        @default
        {{$inspection->inspectionstate}}
    @endswitch
    </td>
    <td>
                        @if ($inspection->max_score==0)
                    0.00%
                @else
                    <b style="font-size: 0.9em">{{number_format(($inspection->score/$inspection->max_score *100),2) }} % ({{$inspection->score}} /{{$inspection->max_score }} ) </b>   
                @endif
        
        
    </td>
    <td><div>
        @switch($inspection->inspectionstate)
            @case('PENDING')
            <form action="inspection/continue" method="POST">
                @csrf
                <input type="text" value="{{$inspection->id}}" name="farmid" hidden>
                <input type="text" value="{{$inspection->iid}}" name="inspectionid" hidden>
                <input type="text" value="{{$inspection->reportid}}" name="reportid" hidden>
                <button type="submit"  class="btn btn-warning" data-toggle="tooltip" data-placement="right" title="Continue Inspection"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
            </form>
                @break
                @case('ACTIVE')
                <form action="inspection/continue" method="POST">
                    @csrf
                    <input type="text" value="{{$inspection->id}}" name="farmid" hidden>
                    <input type="text" value="{{$inspection->iid}}" name="inspectionid" hidden>
                    <input type="text" value="{{$inspection->reportid}}" name="reportid" hidden>
                    <button type="submit"  class="btn btn-warning" data-toggle="tooltip" data-placement="right" title="Continue Inspection"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                </form>
                    @break
        
            @default
                
        @endswitch
        <form action="inspection/continue" method="POST">
            @csrf
            <input type="text" value="{{$inspection->id}}" name="farmid" hidden>
            <input type="text" value="{{$inspection->iid}}" name="inspectionid" hidden>
            <input type="text" value="{{$inspection->reportid}}" name="reportid" hidden>

            <button class="btn btn-success" name='viewsheet' data-toggle="tooltip" data-placement="right" 
            title="View Inspection Sheet" style="margin: 3px"><i class="fa fa-eye" aria-hidden="true"></i></button>
            <button class="btn btn-danger" name='printsheet' data-toggle="tooltip" data-placement="right" 
            title="Download Inspection PDF" style="margin: 3px"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
           
            

        </form>
        @if ( $inspection->inspectionstate=='ACTIVE')
        <form action="{{route('icancel')}}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this inspection?');">
            @csrf
            <input type="text" value="{{$inspection->id}}" name="farmid" hidden>
            <input type="text" value="{{$inspection->iid}}" name="inspectionid" hidden>
            <input type="text" value="{{$inspection->reportid}}" name="reportid" hidden>

            <button class="btn btn-danger" name="deletetbtn" data-toggle="tooltip" data-placement="right" 
            title="Cancel Inspection" style="margin: 3px"><i class="fa fa-times" aria-hidden="true"></i></button>
        </form>
        @endif

        @if ($inspection->inspectionstate=='ACTIVE') 
              <button type="button" class="btn btn-success" data-bs-toggle="modal"
              data-bs-target="#exampleModal" data-bs-whatever="{{$inspection->farmcode}}" 
              data-bs-orgdate="{{$inspection->inspectiondate}}"
              data-bs-reportname="{{$inspection->reportname}}"
              data-bs-inspectionid="{{$inspection->iid}}"
              data-toggle="tooltip" 
              data-placement="right" title="Çhange Inspection Date"><i class="fa fa-calendar" aria-hidden="true"></i></button>  
              @endif
        
    </div>
    </td>
    </tr>  
        
    @empty
        <td>No inspections conducted yet</td>
        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
    @endforelse
</tbody>    
</table>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateinspectiondate" method="post" action='{{route('changedate')}}'>
        @csrf
      <div class="modal-body">
        
          <div class="mb-3">
            <label for="farmcode" class="col-form-label">INSPECTION TYPE</label>
            <input type="text"  disabled class="form-control itype" name="inspectiontype">
          </div>
          <div class="mb-3">
            <label for="message-dropdown" class="col-form-label">Inspection Date</label>
            <input type="text" disabled class="form-control orgdate" name="orgdate">
          </div>  
          <div class="mb-3">
            <label for="message-text" class="col-form-label">New Date</label>
            <input type="date" class="form-control" required name="newinspectiondate" style="background-color:cornflowerblue">
            <input type="text" hidden class="form-control inspectionid"  name="inspectionid" >
          </div>       
      </div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input  type="submit" class="btn btn-success" value="Save">
      </div>
    </form>
    </div>
  </div>
</div>

</div>
<script>
var exampleModal = document.getElementById('exampleModal')
exampleModal.addEventListener('show.bs.modal', function (event) {
  // Button that triggered the modal
  var button = event.relatedTarget
  // Extract info from data-bs-* attributes
  var farmcode = button.getAttribute('data-bs-whatever')
  var orgdate = button.getAttribute('data-bs-orgdate')
  var reportname = button.getAttribute('data-bs-reportname')
  var inspectionid = button.getAttribute('data-bs-inspectionid')
  // If necessary, you could initiate an AJAX request here
  // and then do the updating in a callback.
  //
  // Update the modal's content.
  var modalTitle = exampleModal.querySelector('.modal-title')
  var modalBodyItype = exampleModal.querySelector('.itype')
  var modalBodyOrgdate = exampleModal.querySelector('.orgdate')
   var modalBodyIid = exampleModal.querySelector('.inspectionid')

  modalTitle.textContent = 'Update Inspection Date for  ' + farmcode
  modalBodyItype.value = reportname
  modalBodyOrgdate.value = orgdate
  modalBodyIid.value = inspectionid
})
</script>
<script>
    new DataTable('#inspectiondt');
</script>
</x-layouts.app>