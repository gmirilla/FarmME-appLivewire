<x-layouts.offline>
    <script src="https://cdn.jsdelivr.net/npm/dexie@3.2.2/dist/dexie.min.js"></script>

    <div class="card">

  <div class="card-header"><h4>FARM ENTRANTS  ASSIGNED FOR {{$currentseason}} SEASON</h4></div>

  <div class="card-body table-responsive">
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
  console.log(farmData);

  if (navigator.onLine) {
    farms.forEach(farm => {
      db.farms.put({
        farmcode: farm.farmcode,
        community: farm.community,
        farmname: farm.farmname,
        farmstate: farm.farmstate
      });
      console.log(farm.farmcode, farm.community);
    });
  }
</script>
<script>
if (!navigator.onLine) {
  const tableHTML = `
    <table class="table">
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
  document.querySelector(".card-body").innerHTML = tableHTML;

  db.farms.toArray().then(farms => {
    const tbody = document.getElementById("farm-rows");
    farms.forEach((farm, index) => {
      tbody.innerHTML += `
        <tr>
          <td>${index + 1}</td>
          <td>${farm.community}</td>
          <td>${farm.farmcode}</td>
          <td>${farm.farmname}</td>
          <td>${farm.farmstate}</td>
          <td><button class="btn btn-secondary" disabled>Offline</button></td>
        </tr>
      `;
    });
  });
}
</script>

</x-layouts.offline>