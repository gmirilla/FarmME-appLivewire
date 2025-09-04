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
    @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif
<x-layouts.app>
 <div class="card">
        <div class="card-header"><h4>Approval Committee Member(s)</h4></div>
        <div class="card-body">
            
        </div>
        <div class="card-body ">
            <table class="table">
                <thead>
                    <th>Name of Member</th>
                    <th>Designation</th>
                    <th>Signature</th>
                    <th>Active</th>
                </thead>
                <tbody>
                    @forelse ($apprcomms as $apprcomm)
                        <tr>
                            <form action="{{route('apprcomm_delete')}}" method="post">
                                @csrf
                            <td><input type="text" class="form-control" name="name" disabled id="name" value="{{$apprcomm->name}}"></td>
                            <td><input type="text" class="form-control" name="position" disabled value="{{$apprcomm->position}}"></td>
                            <td>
                            @if (!empty($apprcomm->signaturepath))
                    <img src="{{url('/storage/'.$apprcomm->signaturepath)}}" alt="" width="70px"><br> 
                    @endif
                            </td>
                            <td><input type="checkbox" class="form-checked" name="is_active" id="is_active" value="1"
                                 {{ $apprcomm->is_active ? 'checked' : '' }}>
                                <button type="submit" class="btn btn-danger ml-3">DISABLE</button>
                            <input type="text" hidden class="form-control" name="id" id="id" value="{{$apprcomm->id}}">
                            </td>
                            </form>
                        </tr>
                    @empty
                        
                    @endforelse
                        <tr>
                            <form action="{{route('apprcomm_add')}}" method="post" enctype="multipart/form-data">
                                @csrf
                            <td><input type="text" name="name" id="name" class="form-control">
                            </td>
                            <td><input type="text" name="position" class="form-control">
                            </td>
                            <td><input type="file" name="signaturepath" accept="image/*" class="form-control">
                            </td>
                            <td><input type="checkbox" class="form-checked" name="is_active" id="active">
                            <button type="submit" class="btn btn-primary ml-5">ADD</button>
                            </td>
                            </form>
                        </tr>

                    
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>