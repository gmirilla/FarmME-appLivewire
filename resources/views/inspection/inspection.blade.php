<x-layouts.app>
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
<table class="table table-striped">
<tbody>
    <thead>
        <th>#</th>
        <th>Farm</th>
        <th>Updated date</th>
        <th>Status</th>
        <th>Result</th>
        <th>Action</th>
    </thead>
    @forelse ($inspections as $inspection)
    <td></td>
    <td>{{$inspection->farmcode}} | {{$inspection->farmname}}</td>
    <td>{{$inspection->updated_at}}</td>
    <td>{{$inspection->inspectionstate}}</td>
    <td>{{number_format(($inspection->score/$inspection->max_score *100),2) }} % ({{$inspection->score}} /{{$inspection->max_score }} )    
    </td>
    <td><div>
        @if ($inspection->inspectionstate=='ACTIVE22')
            <a  href='#'  disabled class="btn btn-danger"> Continue </a>
        @else
        <form action="inspection/start" method="POST">
            @csrf
            <input type="text" value="{{$inspection->id}}" name="farmid" hidden>
            <input type="submit"  disabled class="btn btn-danger" value="Continue">
        </form>
        @endif
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
</x-layouts.app>