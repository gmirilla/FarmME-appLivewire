
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
        <div class="card-header">Yield Estimate (Hibiscus) Configuration</div>
        <div class="card-body">
            
        </div>
        <div class="card-body ">
            <table class="table">
                <thead>
                    <th>Season</th>
                    <th>System</th>
                    <th>Estimate/sq. meter</th>
                    <th>Active</th>
                </thead>
                <tbody>
                    @forelse ($yieldcodes as $yieldcode )
                        <tr>
                            <form action="" method="post">
                            <td><input type="text" class="form-control" name="season" disabled id="season" value="{{$yieldcode->season}}"></td>
                            <td><input type="text" class="form-control" name="system" disabled id="system" value="{{$yieldcode->system}}"></td>
                            <td><input type="text" class="form-control" name="value" id="value" value="{{$yieldcode->value}}"></td>
                            <td><input type="checkbox" class="form-checked" name="active" id="active" value="1"
                                 {{ $yieldcode->active ? 'checked' : '' }}>
                                <button type="submit" class="btn btn-primary ml-3">UPDATE</button>
                            <input type="text" hidden class="form-control" name="id" id="id" value="{{$yieldcode->id}}">
                            </td>
                            </form>
                        </tr>
                    @empty
                        
                    @endforelse
                        <tr>
                            <form action="{{route('mye_add')}}" method="get">
                                @csrf
                            <td><input type="text" class="form-control" disabled value="{{$currentseason}}">
                            <input type="text" class="form-control" name="season" id="season" hidden value="{{$currentseason}}">
                            </td>
                            <td><select name="system" id="optsystem" class="form-select">
                                <option value="1:1">1:1</option>
                                <option value="1:2">1:2</option>
                                <option value="1:3">1:3</option>
                            </select>
                            </td>
                            <td><input type="text" class="form-control" name="value" id="value" placeholder="e.g 500"></td>
                            <td><input type="checkbox" class="form-checked" name="active" id="active">
                            <button type="submit" class="btn btn-primary ml-5">ADD</button>
                            </td>
                            </form>
                        </tr>

                    
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>