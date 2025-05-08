<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
<x-layouts.app>
<div>
<!--
<form method='get' action='dashboard'>
  <input type="text" placeholder="Search for Farm" name="farmsearch" style="border: solid 1px grey ; padding:3px">
  <input type="submit" class="btn btn-success" value="Search **TO DO">
<form>
-->
  <a href='/newfarm' class="btn btn-primary" style="margin:5px"> Register New Farm</a>   
</div>
<div>

    <table class="table table-striped display" id="farms" style="width:100%">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Community</th>
            <th scope="col">Farm Code</th>
            <th scope="col">Farmer Name</th>
            <th scope="col">Last Inspection Date</th>
            <th scope="col">Next Inspection Date</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          @php
            $counter=0;
          @endphp
          @forelse ($farmlist as $farm )
          @php 
          $counter=$counter+1;
          $farmcode=$farm->farmcode;
          @endphp
          <tr>
            <td>{{$counter}}</td>
            <td>{{$farm->community}}</td>
            <td>{{$farm->farmcode}}</td>
            <td>{{$farm->farmname}}</td>
            <td>{{$farm->lastinspection}}</td>
            <td>{{$farm->nextinspection}}</td>
            <td>{{$farm->farmstate}}</td>
            <td>
          
              @if ($user->roles=='ADMINISTRATOR' && $farm->inspectorid!=null) 
              <button type="button" class="btn btn-primary" data-bs-toggle="modal"
              data-bs-target="#exampleModal" data-bs-whatever="{{$farm->farmcode}}" data-toggle="tooltip" data-placement="right" title="Schedule Inspection"><i class="fa fa-calendar" aria-hidden="true"></i></button>  
              @endif
              <a href="/farm/view?id={{$farm->farmcode}}" type="button" class="btn btn-success" style="margin:3px" data-toggle="tooltip" data-placement="right" title="View Farm"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
          </tr>
            
          @empty
            <h1>No Farms Registered on System!!</h1>
            {{$farmcode='None'}}
          @endforelse 
        </tbody>
      </table>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="farmschedule" method="post" action='/farm/schedule'>
        @csrf
      <div class="modal-body">
        
          <div class="mb-3">
            <label for="farmcode" class="col-form-label">Farm Code</label>
            <input type="text"  readonly class="form-control fcode" id="farmcode" name="farmcode">
          </div>
          <div class="mb-3">
            <label for="message-dropdown" class="col-form-label">Inspection Type</label>
            <select  class="form-select" id="scheduletype" name="inspectiontype" style="background-color:cornflowerblue">
              @forelse ($reports as $report )
              <option value="{{$report->id}}"> {{$report->reportname}}</option>
              @empty
                <option value="0" > No Reports Available</option>
              @endforelse
            </select>
          </div>  
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Date</label>
            <input type="date" class="form-control" id="scheduledate" name="newinspectiondate" style="background-color:cornflowerblue">
          </div>       
      </div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input  type="submit" class="btn btn-primary" value="Save">
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
  var recipient = button.getAttribute('data-bs-whatever')
  // If necessary, you could initiate an AJAX request here
  // and then do the updating in a callback.
  //
  // Update the modal's content.
  var modalTitle = exampleModal.querySelector('.modal-title')
  var modalBodyInput = exampleModal.querySelector('.modal-body input')

  modalTitle.textContent = 'Schedule Inspection for  ' + recipient
  modalBodyInput.value = recipient
})
</script>
<script>
  new DataTable('#farms');
</script>
</x-layouts.app>

            
