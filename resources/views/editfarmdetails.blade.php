<x-layouts.app>
    <div class="d-flex flex-row-reverse bd-highlight">
        <div class="p-2 bd-highlight" style="margin-right: 5px"><button  class="btn btn-primary"> Certified Crop Details </button> </div>
        <div class="p-2 bd-highlight" style="margin-right: 5px"><button class="btn btn-primary">Farm Plot(s) Details </button></div>
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
    <div>
        
        <form method="post" action='/newfarm/Createfarm'>
            {{ csrf_field() }}
            <div class="flex">
                <div class="mb-3" style="margin-right: 10px">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" placeholder="First Name of Farm Owner" id="fname"  name="fname" required class="form-control">
                    </div>
                    <div class="mb-3" style="margin-right: 10px">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" placeholder="Surname of Farm Owner" id="surname"  name="surname" required class="form-control">
                        </div>
                        <div class="mb-3" style="margin-right: 10px">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel"  id="phone"  name="phone" required class="form-control">
                            </div>
                            <div class="mb-3" style="margin-right: 10px">
                                <label for="idno" class="form-label">ID Number</label>
                                <input type="text"  id="idno"  name="idno" required class="form-control">
                                </div>
                                <div class="mb-3" style="margin-right: 10px">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" id="gender" required class="form-select">
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE">FEMALE</option>
                                    </select>
                                    </div>
            </div>
            <div class="mb-3">
                <label for="community" class="form-label">Community/Village</label>
                <input type="text" placeholder="Name of Village/Community" id="community" name="community"  required class="form-control">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" placeholder="Name of City" id="community" name="city"  required class="form-control">
            </div>
            <div class="mb-3">
                <label for="state" class="form-label">State</label>
                <select required class="form-select" name="state" id="state">
                    <option value="Kaduna">Kaduna</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="region" class="form-label">Region</label>
                <input type="text" placeholder="Name of Internal Region" id="region" name="region"  required class="form-control">
            </div>
            <div class="mb-3">
                <label for="crop" class="form-label">Certified Crop</label>
                <select required class="form-select" name="crop" id="crop">
                    <option value="Ginger">Ginger</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="cropvariety" class="form-label">Certified Crop Variety</label>
                <select required class="form-select" name="cropvariety" id="cropvariety">
                    <option value="UG 1( TAFIN GIWA)">UG 1( TAFIN GIWA)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nopworkers" class="form-label">No of Permanent Workers</label>
                <input type="number"  id="nopworkers" name="nopworkers"  class="form-control">
                </div>
                <div class="mb-3">
                    <label for="notworkers" class="form-label">No of Temporary Workers</label>
                    <input type="number"  id="notworkers" name="notworkers"  class="form-control">
                    </div>
            <div class="mb-3">
            <label for="farmcode" class="form-label">Farm Code</label>
            <input type="text" placeholder="Farm Code" id="farmcode" name="farmcode" required  class="form-control">
            </div>
            <div class="mb-3">
                <label for="yearofcert" class="form-label">Year of RA Certification</label>
                <input type="number" placeholder="Enter year of Certification" id="yearofcert" name="yearofcert" required  class="form-control">
                </div>
            <div>
                <button type="submit" class="btn btn-primary">Update Details</button>
            </div>
        </form>
    </div>
</x-layouts.app>