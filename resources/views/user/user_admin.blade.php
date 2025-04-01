<x-layouts.app>
<div>

    <table class="table table-striped">
        <thead>
            <th>#</th>
            <th>User Name</th>
            <th>User Email </th>
            <th>Role</th>
            <th>Actions</th>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <form method="POST" action="/user_update">
                        @csrf
                    <td>

                    </td>
                    <td>
                   {{$user->name}}
                    </td>
                    <td>
                       {{$user->email}}
                    </td>
                    <td>
                       <div class="form-check">
                        @if (str_contains($user->roles,'ADMIN'))
                        <input class="form-check-input" type="radio"  name="userrole" checked value="ADMINISTRATOR" id="flexCheckDefault">  
                        @else
                        <input class="form-check-input" type="radio" name="userrole"  value="ADMINISTRATOR" id="flexCheckDefault">   
                        @endif
                        <label class="form-check-label" for="flexCheckDefault">
                          ADMINISTRATOR
                        </label>
                        </div>
                        <div class="form-check">
                            @if (str_contains($user->roles,'INSPECT'))
                            <input class="form-check-input" type="radio"  name="userrole" checked value="INSPECTOR" id="flexCheckDefault">  
                            @else
                            <input class="form-check-input" type="radio" name="userrole"  value="INSPECTOR" id="flexCheckDefault">   
                            @endif
                            <label class="form-check-label" for="flexCheckDefault">
                              INSPECTOR
                            </label>
                            <input type="number" hidden name='userid' value="{{$user->id}}">
                            </div>
                            <div class="form-check">
                                @if (str_contains($user->roles,'NONE'))
                                <input class="form-check-input" type="radio"  name="userrole" checked value="NONE" id="flexCheckDefault">  
                                @else
                                <input class="form-check-input" type="radio" name="userrole"  value="NONE" id="flexCheckDefault">   
                                @endif
                                <label class="form-check-label" for="flexCheckDefault">
                                  NONE
                                </label>
                                <input type="number" hidden name='userid' value="{{$user->id}}">
                                </div>
                    </td>
                    <td>
                       <button class="btn btn-success" >GO</button>
                    </td>
                    </form>
                </tr>

            @empty
                
            @endforelse
            <tr>
                <td>
                   
                </td>
                <td>
                   
                </td>
                <td>
                   
                </td>
                <td>
                   
                </td>
                <td>
                   
                </td>
            </tr>
            <tr>
                <td>
                   
                </td>
                <td>
                   
                </td>
                <td>
                   
                </td>
                <td>
                   
                </td>
                <td>
                   
                </td>
            </tr>
        </tbody>
    </table>
</div>
    
</x-layouts.app>

