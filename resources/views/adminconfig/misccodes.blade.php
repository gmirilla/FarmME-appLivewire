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
        <div class="card-header">Miscellaneous Codes Configuration</div>
        <div class="card-body">
            
        </div>
    </div>

</x-layouts.app>