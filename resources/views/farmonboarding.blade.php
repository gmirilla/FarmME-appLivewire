<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
@php
$userid=Auth::user()->id;
@endphp
<x-layouts.app>

<div class="card">

  <div class="card-header"><h4>FARM ENTRANTS  ASSIGNED FOR {{$currentseason}} SEASON</h4></div>
  <input type="text" value="{{$userid}}" hidden disabled id="userid">

  <div class="card-body table-responsive offline">
    <table>
    <tbody id="farm-rows">
  <!-- JS will populate this -->
</tbody>
    </table>

    <table class="table table-striped display" id="farms">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Community</th>
            <th scope="col">Farm Code</th>
            <th scope="col">Farm Name</th>
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
            <td>{{$farm->farmstate}}</td>
            <td>
              <a href="/farm/view?id={{$farm->farmcode}}" type="button" class="btn btn-success" style="margin:3px" data-toggle="tooltip" data-placement="right" title="View Farm"><i class="fa fa-eye" aria-hidden="true"></i></a> 
             <a href="{{route('feprofile')}}?fcode={{$farm->farmcode}}" type="button" class="btn btn-success" style="margin:3px" data-toggle="tooltip" data-placement="right" title="Begin Farm Entrance"><i class="fa fa-pencil" aria-hidden="true"></i></a>
            </td>
          </tr>
            
          @empty
          <td></td>
            <td></td>
            <td></td>
            <td><h1>No Farms Registered on System!!</h1></td>
            <td></td>
            <td></td>
                      
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
        <input  type="submit" class="btn btn-success" value="Save">
      </div>
    </form>
    </div>
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

window.addEventListener('offline', () => {
  document.body.insertAdjacentHTML('afterbegin', '<div class="offline-banner">You’re offline. Showing cached data.</div>');
});

</script>
<script>
  new DataTable('#farms');
</script>
<script>
  const farmData = @json($farmlist);
  const farms = Object.values(farmData); // Now farms is an actual array

  const reportData=@json($reports);
  const reports=Object.values(reportData); // No of active reports in system



  if (navigator.onLine) {
    farms.forEach(farm => {
      db.farms.put({
        farmcode: farm.farmcode,
        community: farm.community,
        farmname: farm.farmname,
        farmstate: farm.farmstate,
        inspectorid: farm.inspectorid
      });
     
    });

    reports.forEach(report=>{
      db.reports.put({
        reportid: report.id,
        reportname: report.reportname,
        reportstate: report.reportstate
      });

    });
  }
</script>
<script>
if (!navigator.onLine) {
   console.log("Offline mode detected. Redirecting to offline view...");
  const tableHTML = `
    <table class="table table-strpied">
      <thead>
        <tr>
          <th>#</th>
          <th>Community</th>
          <th>Farm Code</th>
          <th>Farm Name</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="farm-rows"></tbody>
    </table>`;
  document.querySelector(".offline").innerHTML = tableHTML;

const currentUserId = document.getElementById("userid");
const x=currentUserId.value;


db.farms.toArray().then(farms => {
  const tbody = document.getElementById("farm-rows");
  let rowsHTML = "";

  farms
    .filter(farm => farm.inspectorid ==x) // Filter based on inspector match
    .forEach((farm, index) => {
      rowsHTML += `
        <tr>
          <td>${index + 1}</td>
          <td>${farm.community}</td>
          <td>${farm.farmcode}</td>
          <td>${farm.farmname}</td>
          <td>${farm.farmstate}</td>
          <td>
            <button class="btn btn-secondary" data-code="${farm.farmcode}" data-state="${farm.farmstate}">

          
              Offline Form
            </button>
          </td>
        </tr>
      `;
    });
    if (rowsHTML === "") {
  rowsHTML = `
    <tr>
      <td colspan="6" class="text-center">No farms found for this inspector.</td>
    </tr>
  `;
}


  tbody.innerHTML = rowsHTML;

  document.querySelectorAll(".btn-secondary").forEach(btn => {
  btn.addEventListener("click", () => {
    const farmcode = btn.dataset.code;
    const farmstate = btn.dataset.state;
    goToOfflineFormFE(farmcode, farmstate);
  });
});

});
}

function goToOfflineFormFE(farmcode, farmstate) {
  console.log("Farm state:", farmstate);

  if (farmstate == "PENDING") {
    localStorage.setItem("selectedFarm", farmcode);
    window.location.href = "/offline-fe";
  } else {
    alert("Unable to start Farm Entrance: farmer is not in PENDING state.");
  }
}



</script>

</x-layouts.app>

            
