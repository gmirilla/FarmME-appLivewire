<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<x-layouts.app>

  <div>
    
    <form method="post" action='/report/showsection' >
      @csrf
      <div class="d-flex flex-row">
      <div class="form-floating sm-3"> 
        <div class="input-group">
            <label for="floatingSelect" class="visually-hidden">REPORT NAME</label>
            <div class="input-group-text">Report</div>
          <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="reportid">
              @forelse ($reports as $report)
              @if ($report->reportstate=="ACTIVE")
                <option value="{{$report->id}}">{{$report->reportname}}</option>  
              @endif
               
              @empty
              <option disabled selected>NO REPORTS CONFIGURED ON SYTSTEM</option>
              @endforelse
          </select>
      
        </div> 
      </div>   
        <div style="margin-left: 10px; margin-right:10px">         
          <button type="submit" class="btn btn-success">GO</button>
        </div>
        <div>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
          data-bs-target="#exampleModal" data-bs-whatever="">New report</button>  
        </div>
        <div class="ml-3">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
          data-bs-target="#copyModal" data-bs-whatever="">Copy report</button>  
        </div>
        </div>

  </form>
  </div>



  <div>
    <table class="table table-striped" id="reportdt">
       <thead>
          <th>#</th>
          <th>Report Name </th>
          <th>Max Score</th>
          <th>Active</th>
          <th>Action</th>
        </thead>
      <tbody>
        @forelse ($reports as $report )
        <tr>
          <form action="{{route('rtoggle')}}" method="post">
            @csrf
          <td></td>
           <td>{{$report->reportname}}</td>
            <td>{{$report->max_score}}</td>
             <td>{{$report->reportstate}}</td>
              <td>
                @if ($report->reportstate=='ACTIVE')
                <input type="text" name="reportid" value="{{$report->id}}" hidden>
                  <button class="btn btn-danger" id="reporttoggle" name="reporttoggle" value="false">Deactivate</button>
                @else
                <input type="text" name="reportid" value="{{$report->id}}" hidden>
                  <button class="btn btn-success" id="reporttoggle" name="reporttoggle" value="true">Activate</button>
                @endif
              </td>
              </form>
        </tr>
        @empty
        <tr>
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
  <!-- REPORT Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create New Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="newreport" method="post" action='/report/new'>
        @csrf
      <div class="modal-body">
        
          <div class="mb-3">
            <label for="farmcode" class="col-form-label">Report Name</label>
            <input type="text" class="form-control fcode" id="reportname" name="reportname">
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
  <!--COPY  REPORT Modal -->
<div class="modal fade" id="copyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="copyModalLabel">Copy Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="newreport" method="post" action='{{route('rcopy')}}'>
        @csrf
      <div class="modal-body">

                <div class="input-group mb-3">
            <label for="floatingSelect" class="visually-hidden">REPORT TO COPY</label>
            <div class="input-group-text">Report</div>
          <select class="form-select" id="orgreport" aria-label="Floating label select example" name="orgreportid">
              @forelse ($reports as $report)
              <option value="{{$report->id}}">{{$report->reportname}}</option>   
              @empty
              <option disabled selected>NO REPORTS CONFIGURED ON SYTSTEM</option>
              @endforelse
          </select>
      
        </div> 
        
          <div class="mb-3">
            <label for="farmcode" class="col-form-label">New Report Name</label>
            <input type="text" class="form-control fcode" id="newreportname"  required name="newreportname">
          </div>      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Copy</button>
      </div>
    </form>
    </div>
  </div>
</div>
<script>
  new DataTable('#reportdt');
</script>
</x-layouts.app>