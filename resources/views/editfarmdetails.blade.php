<x-layouts.app>
    <div class="d-flex flex-row-reverse bd-highlight">
        <div class="p-2 bd-highlight" style="margin-right: 5px"><form action="get" action="{{route('edit_farmunit')}}"><input type="text" name="farmid" id="farmid" hidden value="{{$farm->id}}">
            <button  class="btn btn-primary"> Certified Crop Details </button> </form></div>
        <div class="p-2 bd-highlight" style="margin-right: 5px"> 
            <form action="{{route('listfunits')}}" method="get">
                <input type="text" name="fid" id="fid" hidden value="{{$farm->id}}">
                <button class="btn btn-primary" name="gofunits">Farm Plot(s) Details </button>
        </form></div>   
        <div class="p-2 bd-highlight" style="margin-right: 5px"><button disabled class="btn btn-primary"> Farm Details </button></div>
    </div>
    @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif
    <div class="col-sm-12 card">
        <div class="card-header mb-3">
           
           <h4>{{$farm->farmname}}</h4></div>
         <div class="card-body">  
        <form method="post" action='/farm/updatefarm'>
            {{ csrf_field() }}
            <div class="row gy-2 gx-3 align-items-center">
                <div class="mb-3 col-auto" style="margin-right: 10px">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" value="{{$farm->fname}}" id="fname"  name="fname" required class="form-control">
                    </div>
                    <div class="mb-3 col-auto" style="margin-right: 10px">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" value="{{$farm->surname}}" id="surname"  name="surname" required class="form-control">
                        </div>
                        <div class="mb-3 col-auto" style="margin-right: 10px">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel"  id="phone"  name="phone" required class="form-control" value="{{$farm->phonenumber}}">
                            </div>
                            <div class="mb-3 col-auto" style="margin-right: 10px">
                                <label for="idno" class="form-label">ID Number</label>
                                <input type="text"  id="idno"  name="idno" class="form-control" value="{{$farm->nationalidnumber}}">
                                </div>
                                <div class="mb-3 col-auto" style="margin-right: 10px">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" id="gender" required class="form-select">
                                        <option value="{{$farm->gender}}" selected>{{$farm->gender}}</option>
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE">FEMALE</option>
                                    </select>
                                    </div>
            </div>
            <div class="row gy-2 gx-3 align-items-center">
            <div class="mb-3 col-auto">
                <label for="community" class="form-label">Community/Village</label>
                <input type="text" value={{$farm->community}} id="community" name="community"  required class="form-control">
            </div>
            <div class="mb-3 col-auto">
                <label for="city" class="form-label">City</label>
                <input type="text" value="#TO DO" id="community" name="city"  required class="form-control">
            </div>
            <div class="mb-3 col-auto">
                <label for="state" class="form-label">State</label>
                <select required class="form-select" name="state" id="state">
                    <option selected value={{$farm->state}}>{{$farm->state}}</option>
                    <option value="Kaduna">Kaduna</option>
                </select>
            </div>
            <div class="mb-3 col-auto">
                <label for="region" class="form-label">Region</label>
                <input type="text" value="{{$farm->region}}" id="region" name="region"  required class="form-control">
            </div>
        </div>
        <div class="row gy-2 gx-3 align-items-center">
            <div class="mb-3 col-auto">
                <label for="plots" class="form-label">No of Plots</label>
                <input type="text" value="{{$farm->nooffarmunits}}" id="plots" name="plots"  disabled class="form-control">
            </div>
            <div class="mb-3 col-auto">
                <label for="plots" class="form-label">Farm Area (Ha)</label>
                <input type="text" value="{{$farm->farmarea}}" id="farmarea" name="farmarea"  disabled class="form-control">
            </div>
            <div class="mb-3 col-auto">
                <label for="crop" class="form-label">Certified Crop</label>
                <select required class="form-select" name="crop" id="crop">
                    <option value="Ginger">Ginger</option>
                </select>
            </div>
            <div class="mb-3 col-auto">
                <label for="cropvariety" class="form-label">Certified Crop Variety</label>
                <select required class="form-select" name="cropvariety" id="cropvariety">
                    <option value="UG 1( TAFIN GIWA)">UG 1( TAFIN GIWA)</option>
                </select>
            </div>
            
        </div>
            <div class="mb-3">
                <label for="nopworkers" class="form-label">No of Permanent Workers</label>
                <input type="number"  id="nopworkers" name="nopworkers"  class="form-control" value="{{$farm->noofpermworkers}}">
                </div>
                <div class="mb-3">
                    <label for="notworkers" class="form-label">No of Temporary Workers</label>
                    <input type="number"  id="notworkers" name="notworkers"  class="form-control" value="{{$farm->nooftempworkers}}">
                    </div>
            <div class="mb-3">
            <label for="farmcode" class="form-label">Farm Code</label>
            <input type="text" placeholder="Farm Code" id="farmcode" name="farmcode" required  class="form-control" value="{{$farm->farmcode}}">
            <input type="text" value={{$farm->id}} id="fid"  name="fid" hidden>
            </div>
            <div class="mb-3">
                <label for="yearofcert" class="form-label">Year of RA Certification</label>
                <input type="number" placeholder="Enter year of Certification" id="yearofcert" name="yearofcert" required  class="form-control" value="{{$farm->yearofcertification}}">
                </div>
            <div>
                <button type="submit" class="btn btn-primary">Update Details</button>
            </div>
        </form>
    </div>
    </div>
</x-layouts.app>