<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<x-layouts.app>
  @php
     $year=date('Y');
  @endphp
  @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif


    <div class="card bg-transparent">
      <div class="card-header">
        <h4>Inspection Review</h4>
        <form action="{{route('summarypage')}}" method="get">
        <div class="d-flex flex-row">      
          <div class="p-3">
            <label for="" class="form-label">Season</label>
            <select class="form-select" id="season" name="season">
            @forelse ($seasons as $season)
              <option value="{{$season->season}}" selected>{{$season->season}}</option>              
            @empty
              <option value="No seasons available" disabled>No Season Available</option>
            @endforelse
            </select>
          </div>
          <div class="p-3">
            <label for="" class="form-label">Report</label>
            <select class="form-select" id="report" name="report">
              @forelse ($reports as $report)
                <option value="{{$report->id}}" selected>{{$report->reportname}}</option>
              @empty
                <option value=null disabled>No Report Available</option>
              @endforelse
            </select>
          </div>
          <div class="p-3">
            <label for="" class="form-label">Report State</label>
            <select class="form-select" id="reportstate" name="reportstate">
              <option value="ALL" selected>ALL</option>
              <option value="APPROVED">APPROVED</option>
              <option value="CONDITIONAL">APPROVED WITH CONDITIONS</option>
              <option value="PENDING">PENDING</option>
              <option value="SUBMITTED">SUBMITTED</option>
               <option value="REJECTED">REJECTED</option>
            </select>
          </div>
          <div class="col-auto p-3">
            <br>
            <button class="btn btn-success" type="type">Generate Summary </button>
          </div>
           

        

        </div>
        </form>
       

      </div>
      <div class="card-body table-responsive">
        <table class="table display table-striped table-hover" id="reports">
            <thead>
                <th>Season</th>
                <th>ReportType</th>
                <th>FiledBy</th>
                <th>FarmName</th>
                <th>Score</th>
                <th>Status</th>
                <th>Error Check</th>
                <th>Date</th>
                <th>Comment(s)</th>
                <th>Action</th>
            </thead>
            <tbody>
                @forelse ($reportquestions as $inspection)
                    <tr>
                        <td>{{$inspection->season}}</td>
                        <td>{{$inspection->reportname}}</td>
                        <td>{{$inspection->iname}}</td>
                        <td>{{$inspection->farmname}}</td>
                        <td>
                            @if ($inspection->max_score==0)
                            Check Report
                            @else
                           <b> {{number_format(($inspection->score / $inspection->max_score * 100),2)}}% </b></td>
                            @endif
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
                            <td>@if (!empty($inspection->farmentrance) || $inspection->inspectionstate =='ACTIVE')
                              @if (strpos($inspection->reportname, 'Entrance') != false)
                                <b style="color: green; font-size: 0.5em ">No Issues detected</b>
                              @endif                            
                            @else
                              <b style="color: red; font-size: 0.5em">Potential Issues detected (Check Plots mapped)</b>
                            @endif</td>
                        <td>{{$inspection->cdate}}</td>
                        <td><form action="iapprove" method="POST">
                            @csrf
                            <textarea class="form-control" name="comments" id="comments" >{{$inspection->comments}}</textarea>
                        </td>
                        <td>
                            <div style="margin-top: 5px">
                                    <input type="text" hidden name="iid" value={{$inspection->iid}}>
                                    <button name="viewsheet" type="submit" id="viewbtn" class="btn btn-success" data-toggle="tooltip" data-placement="right" title="View inspection Sheet"><i class="fa fa-eye"></i></button>

                            </div>
                            @if (in_array($inspection->inspectionstate, ['SUBMITTED', 'PENDING','ACTIVE']))
                            <div style="margin-top: 5px">
                                   
                                    <button type="submit" name="approvebtn" class="btn btn-success" data-toggle="tooltip" data-placement="right" title="Approve Inspection"><i class="fa fa-check-square-o"></i></button>
                            </div>
                          @if (strpos($inspection->reportname, 'Entrance') == false)
                          <!-- Entrance String is not found display approve with condition button-->
                          <div style=" margin-top: 5px">
                                <button  type="button" name="approvewithconditionmodal" class="btn btn-warning" 
                                data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="{{$inspection->farmname}} : {{($inspection->reportname)}}"
                                data-bs-whatever2="{{$inspection->iid}}"
                                data-toggle="tooltip" data-placement="right" title="Approve with Condition"><i class="fa fa-check-square-o"></i></button>
                        </div>
                          @endif


                            
                            <div style="margin-top: 5px">
                                    
                                    <button type="submit" name="rejectbtn" class="btn btn-danger"><i class="fa fa-times-circle" data-toggle="tooltip" data-placement="right" title="Inspection not Approved"></i></button>
                            </div>
                            <div style="margin-top: 5px">
                                    
                                    <button type="submit" name="deletetbtn" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="right" title="Delete Inspection"></i></button>
                            </div>
                            @endif
                        </form>
                            </td>
                        
                    </tr>    
                @empty
                <tr>
                  <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>No Inspection Sheets on System </td>
                    <td></td>
                    <td></td>
                </tr>   
                @endforelse
 
            </tbody>
        </table>
      </div>
    </div>
    <!-- MODAL approve with condition -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Conditions for Approval</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="farmschedule" method="post" action='iapprove'>
          @csrf
        <div class="modal-body">
          
            <div class="mb-3">
              <label for="iid" class="col-form-label">Inspection Report ID:</label>
              <input type="text"  readonly class="form-control fcode" id="report_name" name="report_name">
               <input type="text" class="form-control fiid" hidden name="iid">
            </div>  
            <div class="mb-3">
              <label for="message-text" class="col-form-label">Conditions</label>
              <textarea class="form form-control" name="apprconditions" id="approveconditions" cols="20" rows="10"></textarea>
            </div>
            <div class="mb-3">
              <label for="list_ac" class="col-form-label">{{$year}} Current Approval Committee</label>
              <p>
                @forelse ( $approvalcommittees as $acmember )
                 <b>{{$acmember->name}}</b>({{$acmember->position}})<br/>
                @empty
                  <b>No Committee Members for {{$year}} on Record</b>
                @endforelse
              </p>
              @if ($approvalcommittees->isNotEmpty())
                <label for="addcommittee" class="form-label">Add Committee Members to Note </label>
                <input type="checkbox" name="addcommittee" class="form-checked">
              @endif

            </div>     
        </div>     
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input  type="submit" class="btn btn-success" value="Save" name="approvewithcondition">
        </div>
      </form>
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
      var reportid = button.getAttribute('data-bs-whatever2')
      // If necessary, you could initiate an AJAX request here
      // and then do the updating in a callback.
      //
      // Update the modal's content.
      var modalTitle = exampleModal.querySelector('.modal-title')
      var modalBodyInput = exampleModal.querySelector('.fcode')
      var modalBodyreportid = exampleModal.querySelector('.fiid')
    
      modalTitle.textContent = 'Approval Conditions for Farm  ' 
      modalBodyInput.value = recipient
      modalBodyreportid.value =reportid
    })
    </script>
    <script>
            new DataTable('#reports');
    </script>
</x-layouts.app>