<x-layouts.app>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <div>
        <div>
            <!-- 
    <form id='search' name='searchforfarm' method='get' action='farm'>
        <input type="text" placeholder="Search for Farm" name="farmsearch" style="border: solid 1px grey ; padding:3px">
        <input disabled type="submit" class="btn btn-success" value="Search **TO DO">
      <form>
    -->

<a class="btn btn-primary" href='inspection/new'>New Inspection</a>
</div>
<td>
<table class="table table-striped display table-success" id="inspectiondt" style="width: 100%">

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
    <td>{{$inspection->inspectionstate}}</td>
    <td>{{number_format(($inspection->score/$inspection->max_score *100),2) }} % ({{$inspection->score}} /{{$inspection->max_score }} )    
    </td>
    <td><div>
        @switch($inspection->inspectionstate)
            @case('PENDING')
            <form action="inspection/continue" method="POST">
                @csrf
                <input type="text" value="{{$inspection->id}}" name="farmid" hidden>
                <input type="text" value="{{$inspection->iid}}" name="inspectionid" hidden>
                <input type="text" value="{{$inspection->reportid}}" name="reportid" hidden>
                <input type="submit"  class="btn btn-success" value="Continue">
            </form>
                @break
                @case('ACTIVE')
                <form action="inspection/continue" method="POST">
                    @csrf
                    <input type="text" value="{{$inspection->id}}" name="farmid" hidden>
                    <input type="text" value="{{$inspection->iid}}" name="inspectionid" hidden>
                    <input type="text" value="{{$inspection->reportid}}" name="reportid" hidden>
                    <input type="submit"  class="btn btn-success" value="Continue">
                </form>
                    @break
        
            @default
                
        @endswitch
        <button disabled class="btn btn-primary"> Print</button>
    </div>
    </td>
    </tr>  
        
    @empty
        <td>No inspections conducted yet</td>
    @endforelse
</tbody>    
</table>
</div>
<script>
    new DataTable('#inspectiondt');
</script>
</x-layouts.app>