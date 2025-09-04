<x-layouts.app>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

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
        <th>Year of Birth</th>
        <th>ID NO</th>
        <th>Plot name</th>
        <th>Plot Size (ha)</th>
        <th>Plot Lat.</th>
        <th>Plot Long.</th>
        <th>No of Plots</th>
        <th>Total Farm Size (ha)</th>
        <th>Estimated yield (kg)</th>
        <th>Non Ginger Hectare</th>
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
            <td>{{$inspection->getfarm()->yob}}</td>
            <td>{{$inspection->getfarm()->nationalidnumber}}</td>
            <td>{{$inspection->getplotdetails()->plotname}}</td>
            <td>{{$inspection->getplotdetails()->fuarea}}</td>
            <td>{{$inspection->getplotdetails()->fulatitude}}</td>
            <td>{{$inspection->getplotdetails()->fulongitude}}</td>
             <td>{{$inspection->getfarm()->getreportfarmcount($season)}}</td>
            <td>{{number_format($inspection->getfarm()->getreportfarmarea($season),2)}}</td>
            @if (!empty($inspection->farmentrance))

            <td>{{number_format($inspection->farmentrance->getestimatedyield(),2)}}</td>
            <td>{{$inspection->getothercropsize()}}</td>
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
                
            @else
              <td></td>
              <td></td>
              <td></td>
              <td></td>  
            @endif

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
$(document).ready(function() {
    $('#inspectiondt').DataTable({
        dom: 'Bfrtip',
        pageLength: 200,
        order: [[1, 'asc']],
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Inspection_Report',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });
});

</script>
</x-layouts.app>