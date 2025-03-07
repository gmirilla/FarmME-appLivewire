<x-layouts.app>
  
<div>
  <!--
<form id='search'>
  <input type="text" placeholder="Search for Farm" name="farmsearch" style="border: solid 1px grey ; padding:3px">
  <input type="submit" class="btn btn-success" value="Search">
<form>
  -->
  <a href='/newfarm' ><button type="button" class="btn btn-primary" style="margin:5px"> Register New Farm</button> </a>   
</div>
<div>
  
    <table class="table table-striped">
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
              <button type="button" class="btn btn-primary" data-bs-toggle="modal"
               data-bs-target="#exampleModal" data-bs-whatever="{{$farm->farmcode}}">Schedule Inspection</button>            
              <a href="/farm/view?id={{$farm->farmcode}}" type="button" class="btn btn-success" style="margin:5px">View Farm</a></td>
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
            <label for="message-text" class="col-form-label">Date</label>
            <input type="date" class="form-control" id="scheduledate" name="newinspectiondate" style="background-color:cornflowerblue">
          </div>       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
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
</x-layouts.app>

            
