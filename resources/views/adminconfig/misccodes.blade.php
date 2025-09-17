<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@php
    Auth::check();
    $user=Auth::user();
@endphp
<x-layouts.app>
    <div class="card">
        <div class="card-header"><h3>Miscellaneous Codes Configuration</h3></div>
        <div class="card-body flex">
            <div class="col-auto"> 
                <a href="{{route('mye_show')}}" style="color:white;"><div class="col-auto  mx-2 py-3 px-2 text-center" style="background-color: rgb(5, 100, 45)">CONFIGURE YIELD ESTIMATES</div></a> 
            </div>
           
            <div class="col-auto"> 
                <a href="{{route('apprcomm_show')}}" style="color:white;"><div class="col-auto  mx-2 py-3 px-2 text-center" style="background-color: rgb(5, 100, 45)">CONFIGURE APPROVAL COMITTEE</div></a> 

            </div>
           
        </div>
    </div>

</x-layouts.app>