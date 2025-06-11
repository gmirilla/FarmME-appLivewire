<x-layouts.app>
    <div class="table-responsive">
<table class="table table-striped">
    <thead>
        <th>Farm Code</th>
        <th>National Farm ID</th>
        <th>Internal Farm ID</th>
        <th>Village</th>
        <th>Community</th>
        <th>State</th>
        <th>Region</th>
        <th>Farm Area (ha)</th>
        <th>Farm Type</th>
        <th>Number of Farm units</th> 
        <th>Year of Certification</th>
        <th>First Name</th>
        <th>Surname</th>
        <th>Farmer Name</th>
        <th>Phone Number</th>
        <th>National ID No.</th>
        <th>Gender</th>
        <th>No of Permanent Workers</th>
        <th>No of Temporary  Workers</th>
        <th>Internal Inspector</th>
        <th>Inspection Year</th>
        <th>Inspection Month</th>
        <th>Inspection Day</th>

    </thead>
    <tbody>
        @forelse ($farms as $farm )
        <tr>
          <td>{{$farm->farmcode}}</td>
          <td>N/A</td>
          <td>{{$farm->farmcode}}</td>
          <td>{{$farm->village}}</td>
          <td>{{$farm->community}}</td>  
          <td>{{$farm->farmstate}}</td>
          <td>{{$farm->region}}</td>
          <td>{{$farm->farmarea}}</td>
          <td>{{$farm->crop}}</td>
          <td>{{$farm->getfarmplots()}}</td>
          <td>{{$farm->yearofcertification}}</td>  
          <td>{{$farm->fname}}</td>
          <td>{{$farm->surname}}</td>
          <td>{{$farm->farmname}}</td>
          <td>{{$farm->phonenumber}}</td>
          <td>{{$farm->nationalidnumber}}</td>
          <td>{{$farm->gender}}</td>  
          <td>{{$farm->noofpermworkers}}</td>
          <td>{{$farm->nooftempworkers}}</td>
          <td>{{$farm->getinspectorname()}}</td>
          <td>
          </td>
          <td></td>
          <td></td>
        </tr>
        @empty
        <tr>
            <td></td>
          <td>NO RECORDS FOUND FOR PERIOD</td>
          <td></td>
          <td></td>
          <td></td>  
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>  
          <td></td>
          <td></td>
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
</x-layouts.app>

