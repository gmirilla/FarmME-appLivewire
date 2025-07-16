<x-layouts.app>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div>
  @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif
<div class="card">
    <div class="card-header"><h4>INSPECTION SUMMARY REPORT</h4></div>
    <div class="card-header">
        <div class="row justify-content-center">
            <div class="col-auto">
                <b>SEASON :</b> {{$season}}
            </div>
            <div class="col-auto">
               <b> REPORT NAME :</b> {{$reportname->reportname}}
            </div>
              <div class="col-auto">
               <b> REPORT STATE :</b> {{$state}}
            </div>
        </div>

    </div>
<div class="card-body table-responsive">   
@php
    $reporttype='NIL';
    if (strpos($reportname->reportname,'Entrance')) {
        # code...
        $reporttype='Entrance';
    }
@endphp
@if ($reporttype=='Entrance')
<table class="table table-striped display table-sm table-sm" id="inspectiondt">

    <thead>

        <th>Farmer Name</th>
        <th>Farm Code</th>
        <th>Gender</th>
        <th>No of Plots</th>
        <th>Total Farm Size (ha)</th>
        <th>Estimated yield (kg)</th>
        <th>Previous Year Del.</th>
         <th>Previous 2 Years Del.</th>
          <th>Previous 3 Years Del.</th>
    </thead>
    <tbody>
        @forelse ( $internalinspection as $inspection )
  
        <tr>
            <td>{{$inspection->getfarm()->farmname}}</td>
            <td>{{$inspection->getfarm()->farmcode}}</td>
            <td>{{$inspection->getfarm()->gender}}</td>
             <td>{{$inspection->getfarm()->getreportfarmcount($season)}}</td>
            <td>{{number_format($inspection->getfarm()->getreportfarmarea($season),2)}}</td>
            <td>{{number_format($inspection->farmentrance->getestimatedyield(),2)}}</td>
            <td>@if (!empty($inspection->farmentrance->reportvolcropdel()[0]))
                {{number_format($inspection->farmentrance->reportvolcropdel()[0]->value,2)}}
                @endif
            </td>
            <td>@if (!empty($inspection->farmentrance->reportvolcropdel()[1]))
                {{number_format($inspection->farmentrance->reportvolcropdel()[1]->value,2)}}
                @endif
            </td>
            <td>@if (!empty($inspection->farmentrance->reportvolcropdel()[2]))
                {{number_format($inspection->farmentrance->reportvolcropdel()[2]->value,2)}}
                @endif
            </td>
        </tr>
           
        @empty
            
        @endforelse

</tbody>    
</table>
@else
    <h5>This report is unavailable for this inspection type.</5>
@endif 

</div>
</div>
</div>
<script>
    new DataTable('#inspectiondt');
</script>
</x-layouts.app>