<x-layouts.app>
<div>  
<div>
    <div class="container-sm" >
        <div class="row">
            <div class="col">
                <h4 class="h4" style="text-align: center; background-color:green ; color:white">FARM DETAIL</h4>
                <div class="mb-3">
                <label>FARM NAME</label>

                <input type="text" readonly class="form-control" value="{{$farm->farmname}}">
                </div>
                <div class="mb-3">
                    <label>FARM CODE</label>
                    <input type="text" readonly class="form-control" value="{{$farm->farmCode}}">
                </div>
                <div class="mb-3">
                    <label>COMMUNITY</label>
                    <input type="text" readonly class="form-control" value="{{$farm->community}}">
                </div>
                <div class="mb-3">
                    <label>FARM AREA {{$farm->measurement}}</label>
                    <input type="text" readonly class="form-control" value="{{$farm->farmarea }}">
                </div>
                <div class="mb-3">
                    <label>ASSIGNED STAFF</label>
                    @if ($farm->name ==null)
                    <input type="text" class="form-control" disabled value="No Staff Assigned">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#staffModal" data-bs-whatever="">EDIT</button>
                    @else
                        <input type="text" class="form-control" disabled value="{{$farm->name}}">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#staffModal" data-bs-whatever="">EDIT</button>
                    @endif

                </div>
            </div>
            <div class="col">
                <h4 class="h4" style="text-align: center; background-color:green;  color: white">INSPECTION SUMMARY</h4>
                <div class="mb-3">
                    <label>STATUS</label>
                    <input type="text" readonly class="form-control" value="{{$farm->farmstate}}">
                </div>
                <div class="mb-3">
                    <label>LAST INSPECTION</label>
                    <input type="text" readonly class="form-control" value="DATE: {{$farm->lastinspection}} | Score : N/A'">
                </div>
                <div class="mb-3">
                    <label>NEXT INSPECTION</label>
                    <input type="text" readonly class="form-control" value="{{$farm->nextinspection}}">
                    <form action="" method="POST">
                        <input type="number" name="farmid" hidden value="{{$farm->id}}">
                        <button class="btn btn-primary"> >> </button>

                    </form>
                </div>
 
            </div>
          </div>
          <div class="row">
            <h4 class="h4" style="text-align: center; background-color:green; color: white">INSPECTION RECORDS</h4>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                    <th>Report Name</th>
                    <th>Score</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Comment</th>
                    </tr>
                </thead>  
                <tbody> 
             @forelse ($farmreports as $farmreport)
             <tr>
             <td>{{$farmreport->reportname}}</td>
             <td>{{number_format(($farmreport->score / $farmreport->max_score *100),2)}}%</td> 
             <td>{{$farmreport->created_at}}</td>
             <td>{{$farmreport->inspectionstate}}</td>
             <td><textarea name="" id="" cols="20" rows="3">Comments **To Do</textarea></td>
             </tr>
                 @empty
                <tr> <td>NO INSPECTIONS CARRIED OUT</td></tr>
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
                <select name="staffid" id="" class="form-control">
                    @forelse ($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>  
                    @empty
                        <option value="NIL">NO USERS ON SYSTEM</option>
                    @endforelse
                </select>
            </div>
            <button class="btn btn-primary" >Submit</button>
        </form>

          </div>
</div>
    </div>
</div>
</x-layouts.app>