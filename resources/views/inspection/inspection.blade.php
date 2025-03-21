<x-layouts.app>
    <div>
        <div>
    <form id='search' name='searchforfarm' method='get' action='farm'>
        <input type="text" placeholder="Search for Farm" name="farmsearch" style="border: solid 1px grey ; padding:3px">
        <input disabled type="submit" class="btn btn-success" value="Search **TO DO">
      <form>
    

<a class="btn btn-primary" href='inspection/new'>New Inspection</a>
</div>

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
    <td></td>
    <td><button disabled class="btn btn-primary"> Continue </button>
        <button disbled class="btn btn-primary"> Print Report</button></td>
    </tr>  
        
    @empty
        <td>No inspections conducted yet</td>
    @endforelse
</tbody>    
</table>
</div>
</x-layouts.app>