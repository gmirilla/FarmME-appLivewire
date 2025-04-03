<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<x-layouts.app>

    REVIEW & Approval

    <div>
        <table class="table display table-striped table-hover table-success" id="reports">
            <thead>
                <th>ReportId</th>
                <th>ReportType</th>
                <th>FiledBy</th>
                <th>FarmName</th>
                <th>Score</th>
                <th>Status</th>
                <th>Date</th>
                <th>Comment(s)</th>
                <th>Action</th>
            </thead>
            <tbody>
                @forelse ($reportquestions as $inspection)
                    <tr>
                        <td>{{$inspection->iid}}</td>
                        <td>{{$inspection->reportname}}</td>
                        <td>{{$inspection->iname}}</td>
                        <td>{{$inspection->farmname}}</td>
                        <td>
                            @if ($inspection->max_score==0)
                            Check Report
                            @else
                            {{number_format(($inspection->score / $inspection->max_score * 100),2)}}%</td>
                            @endif
                        <td>{{$inspection->inspectionstate}}</td>
                        <td>{{$inspection->cdate}}</td>
                        <td><form action="iapprove" method="POST">
                            @csrf
                            <textarea class="form-control" name="comments" id="comments" ></textarea>
                        </td>
                        <td>
                            <div style="margin-top: 5px">
                                    <input type="text" hidden name="iid" value={{$inspection->iid}}>
                                    <button type="submit" id="viewbtn" class="btn btn-primary"><i class="fa fa-eye"></i></button>

                            </div>
                            @if ($inspection->inspectionstate=='SUBMITTED')
                            <div style="margin-top: 5px">
                                   
                                    <button type="submit" name="approvebtn" class="btn btn-success"><i class="fa fa-check-square-o"></i></button>
                            </div>
                            <div style="margin-top: 5px">
                                    
                                    <button type="submit" name="rejectbtn" class="btn btn-danger"><i class="fa fa-times-circle"></i></button>
                            </div>
                                
                            @endif
                        </form>
                            </td>
                        
                    </tr>    
                @empty
                <tr>
                    <td>No Inspection Sheets on System </td>
                </tr>   
                @endforelse
 
            </tbody>
        </table>
    </div>
    <script>
            new DataTable('#reports');
    </script>
</x-layouts.app>