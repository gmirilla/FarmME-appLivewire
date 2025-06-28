<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<x-layouts.app>
  @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif
  @if (!empty($message))
  <div class="alert alert-success">
      <ul>
              <li>{{ $message }}</li>
      </ul>
  </div>
@endif
<div class="card-body table-responsive">
    <div class="card-header mb-3">
        <h4>Users</h4>
    </div>
    <p class="text-muted">Manage users and their roles in the system.</p>
<button type="button" name="newuserbtn" id="newuserbtn" class="btn btn-success" data-bs-toggle="modal"
data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="right" title="Add  New User">
<i class="fa fa-user-plus" aria-hidden="true"></i></button>
<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-striped table-sm" id="users">
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
                       <button class="btn btn-primary"  type="button" data-toggle="tooltip" data-placement="right" title="Edit Password" name="editpwdbtn" data-bs-toggle="modal" 
                       data-bs-target="#passwordModal" data-bs-uname={{$user->name}}  data-bs-uid={{$user->id}}>
                        <i class="fa fa-key" aria-hidden="true"></i>

                       </button>

                    </td>
                    </form>
                </tr>

            @empty
                
            @endforelse

        </tbody>
    </table>
  </div>
</div>
</div>
<!-- MODAL FOR NEW USER CREATION -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="newuser" method="get" action='{{route('newuser')}}'>
          @csrf
        <div class="modal-body">
          
            <div class="mb-3">
              <label for="farmcode" class="col-form-label">User Name</label>
              <input type="text"  class="form-control uname" id="uname" name="name"  value="{{$user->name}}">
            </div>
            
            <div class="mb-3">
              <label for="message-dropdown" class="col-form-label">Email</label>
              <input type="email"  class="form-control email" id="email" name="email" required placeholder="User@example.com">
            </div>  
            <div class="mb-3">
                <label for="message-dropdown" class="col-form-label">Password</label>
                <input type="password"  class="form-control password" id="password" name="password" required>
            </div>  
            <div class="mb-3">
              <label for="message-text" class="col-form-label">Role</label>
              <select class="form-control" required>
                <option value="NONE">NONE</option>
                <option value="ADMINISTRATOR">ADMINISTRATOR</option>
                <option value="INSPECTOR">INSPECTOR</option>
              </select>
            </div>       
        </div>     
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input  type="submit" class="btn btn-primary" value="Save" name="newuserbtn">
        </div>
      </form>
      </div>
    </div>
  </div>
  <!--Password change modal -->
  <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="pwdchg" method="post" action='{{route('user_pwd')}}'>
          @csrf
        <div class="modal-body">
          
            <div class="mb-3">
              <label for="farmcode" class="col-form-label">User Name</label>
              <input type="text"  class="form-control uname" id="pwduname" name="name" disabled value="">
              <input type="text"  class="form-control uname" id="pwduid" name="uid" hidden value="">
            </div>
            <div class="mb-3">
                <label for="message-dropdown" class="col-form-label">New Password</label>
                <input type="password"  class="form-control password" id="chgpassword" name="password" required>
            </div>  
        </div>     
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input  type="submit" class="btn btn-primary" value="Save" name="chgpwdbtn">
        </div>
      </form>
      </div>
    </div>
  </div>
<script>
    new DataTable('#users');
  </script>  
  <script>
    var exampleModal = document.getElementById('passwordModal')
    exampleModal.addEventListener('show.bs.modal', function (event) {
      // Button that triggered the modal
      var button = event.relatedTarget
      // Extract info from data-bs-* attributes
      var recipient = button.getAttribute('data-bs-uname')
      var uid = button.getAttribute('data-bs-uid')
      // If necessary, you could initiate an AJAX request here
      // and then do the updating in a callback.
      //
      // Update the modal's content.
      var modalTitle = exampleModal.querySelector('.modal-title')
      var modalBodyName = exampleModal.querySelector('#pwduname')
      var modalBodyUid = exampleModal.querySelector('#pwduid')
    
      modalTitle.textContent = 'CHANGE USER PASSWORD ' 
      modalBodyName.value = recipient;
      modalBodyUid.value = uid;

    })

    </script> 
</x-layouts.app>

