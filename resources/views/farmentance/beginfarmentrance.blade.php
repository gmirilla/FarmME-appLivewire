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
@php
    $year=date('Y');
    if (empty($farmentrance->getcropdeliver())) {
        # code...
        $prevseason=$seasonrange[0];
        $prevcropdelivered=null;
    } else {
        # code...
        $prevseason=$farmentrance->getcropdeliver()->season;
        $prevcropdelivered=$farmentrance->getcropdeliver()->value;
    }
        if (empty($farmentrance->getcropproduced())) {
        # code...
        $prevcropproduced=null;
    } else {
        # code...
        $prevcropproduced=$farmentrance->getcropproduced()->value;
    }
    

    
    
@endphp

    <div class="card">
        <div class="card-header"><h4>Annex 3:  Field Entrance Form {{$currentseason}} Season</h4></div>
        <div class="card-body">
            <h5>A. FIELD OPERATOR BIO-DATA</h5>
            <div class="row my-3 gx-5">
                <div class="col-3 p-3">
                    <label for="surname" class="form-label">Surname</label>
                    <input type="text" disabled class="form-control" value="{{$farmerdetail->surname}}">
                </div>
                <div class="col-3 p-3">
                    <label for="fname" class="form-label">Other name</label>
                    <input type="text" disabled class="form-control" value="{{$farmerdetail->fname}}">
                </div>
                <div class="col-3 p-3">
                    <label for="gender" class="form-label">Gender</label>
                    <input type="text" disabled class="form-control" value="{{$farmerdetail->gender}}">
                </div>
                <div class="col-3 p-3">
                    <label for="farmercode" class="form-label">Farmer Code</label>
                    <input type="text" disabled class="form-control" value="{{$farmerdetail->farmcode}}">
                </div>
                <div class="col-3 p-3">
                    <label for="idnumber" class="form-label">ID Number</label>
                    <input type="text" disabled class="form-control" value="{{$farmerdetail->nationalidnumber}}">
                </div>

                <div class="col-3 p-3">
                    <label for="yearofbirth" class="form-label">Year of Birth</label>
                     <input type="number" value="{{$farmerdetail->yob}}" class="form-control" disabled/>  
                </div>
                <div class="col-3 p-3">
                    <label for="phoneno" class="form-label">Phone Number</label>
                    <input type="text" name="phonenumber" id="phonenumber" value="{{$farmerdetail->phonenumber}}" disabled class="form-control" />
                </div>
                <div class="col-3 p-3">
                    <label for="householdsize" class="form-label">Household Size</label>
                    <input type="text" disabled name="householdsize" id="householdsize" value="{{$farmerdetail->householdsize}}"  class="form-control" />
                </div>
                <div class="col-3 p-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address"  disabled id="address" class="form-control" >{{$farmerdetail->address}}</textarea>
                </div>
               
                <div class="col-3 p-3">
                    <label for="dateoflastinspection" class="form-label">Date of Last Inspection</label>
                        <input type="text" disabled name="dateoflastinspection" id="dateoflastinspection" class="form-control" value="{{$farmentrance->lastinspection}}"/>
                        </div>
                <div class="col-3 p-3">
                    <label for="dateoflastinspection" class="form-label">Outcome of Last Inspection</label>
                    <input type="text" disabled name="outcomeoflastinspection" id="dateoflastinspection" class="form-control" value="{{$farmentrance->inspectionresult}})"/>
                </div>
                    
                <div class="col-3 p-3">
                    <label for="nameofcrop" class="form-label">Name of Crop</label>
                    <input type="text"  disabled name="nameofcrop" id="crop" class="form-control" value="{{$farmerdetail->crop}}"/>
                </div>
                <div class="col-3 p-3">
                    <label for="varietyofcrop" class="form-label">Variety of Crop</label>
                    <input type="text"  disabled name="varietyofcrop" id="varietycrop" class="form-control" value="{{$farmerdetail->cropvariety}}"/>
                </div> 
                <div class="col-3 p-3">
                    <label for="sourceofcrop" class="form-label">Source of Crop</label>
                    <input type="text"  disabled name="sourceofcrop" id="sourceofcrop" class="form-control" value="{{ $farmentrance->sourceofcrop }}"/>
                </div> 
                                <div class="col-3 p-3">
                    @if (!empty($farmerdetail->signaturepath))
                    <img src="{{url('/storage/'.$farmerdetail->signaturepath)}}" alt="" width="70px"><br> 
                    @endif
                    <label for="signature" class="form-label">Farmers Signature</label>
                </div>          
            </div>
        </div>
    </div>
            <div class="card my-3">
    <div class="card-body responsive">
            <h5>B. Historical Production: Hibiscus Production</h5><span style="font-size:0.8rem;">Note: Maize (M), Sorghum (S), Cowpea (C), Millet (ML), Sesame (Se), Groundnut (Gr)</span>
            <table class="table">
                <thead>
                    <th>Year</th>
                    <th>Farm Size (Ha)</th>
                    <th>Harvest Volume (BAGS)</th>
                    <th>Intercrop <br/>Crop</th>
                    <th>Intercrop <br/>System</th>
                    <th>Intra-row Spacing (m)</th>
                     <th></th>
                </thead>
                <tbody>
                    @forelse ($farmentrance->getvolumesold() as $volsold)
                     <tr>
                        <td>{{$volsold->season}}</td>
                        <td>{{$volsold->farmsize}}</td>
                        <td>{{$volsold->value}}</td>
                        <td>{{$volsold->crop}}</td>
                        <td>{{$volsold->system}}</td>
                        <td>{{$volsold->spacing}}</td>
                        <td><a href="{{ route('disablevolsold', ['farmcode' => $farmerdetail->farmcode, 'vsid' => $volsold->id]) }}"  required class="btn btn-danger">Disable</a></td>
                     </tr>   
                    @empty
                        
                    @endforelse
                    <tr>
                        <form action="{{route('addvolsold')}}" method="post">
                            @csrf
                        <td><select name="volseason" id="seasonrange" class="form-select">
                            @foreach ($seasonrange as $srange)
                                <option value="{{$srange}}">{{$srange}}</option>
                            @endforeach
                            </select>
                        </td>
                        <td><input type="number" step=any name="farmsize" required class="form-control"></td>
                        <td><input type="number" name="harvest" required class="form-control"></td>
                        <td><input type="text" name="crop" required class="form-control"></td>
                        <td><input type="text" name="sysyem" required class="form-control"></td>
                        <td>
                            <input type="number" step=any name="spacing" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="farmcode" hidden class="form-control" value="{{$farmerdetail->farmcode}}">
                            <input type="text" name="farmid" hidden class="form-control" value="{{$farmerdetail->id}}">
                            <input type="text" name="farmentranceid" hidden class="form-control" value="{{$farmentrance->id}}">
                            
                        <button type="submit" class="btn btn-primary" >ADD</button></td>
                        </form>
                    </tr> 

                </tbody>
            </table>
    </div>
    </div>
        <div class="card my-3">
    <div class="card-body">
            <h5>C. Historical Production: Agrochemicals Used : {{$prevseason}}  </h5>
            <table class="table">
                <thead>
                    <th>Farm Size (Ha)</th>
                    <th>Products<br>(Fertilizers, Pesticides, Herbicides)
                    </th>
                    <th>QUANTITY APPLIED (LITER'S/BAGS)</th>
                    <th>NAME OF PERSON WHO APPLIED</th>
                    <th>PPE Used (Y/N)</th>
                    <th></th>
                </thead>
                <tbody>'
                    @forelse ($agrochems as $agrochem )
                                           <tr>
                        <td><input type="text" disabled name="farmsize" class="form-control" value="{{$agrochem->farmsize}}"></td>
                        <td><input type="text" disabled name="herbicide" class="form-control" value="{{$agrochem->herbicidename}}"></td>
                        <td><input type="text"  disabled name="herbicideqty" class="form-control" value="{{$agrochem->quantity}}"></td>
                        <td><input type="text" disabled name="herbicideapplier" class="form-control" value="{{$agrochem->nameofperson}}"></td>
                        <td>    <input type="checkbox" name="ppeused" class="form-check-input" 
                                    disabled {{ $agrochem->ppeused ? 'checked' : '' }}>
                        </td>
                        <td><a href="{{ route('disablechems', ['farmcode' => $farmerdetail->farmcode, 'aid' => $agrochem->id]) }}" class="btn btn-danger">Disable</a></td>
                    </tr> 
                    @empty
                        
                    @endforelse
                    <tr><form action="{{route('addchems')}}" method="post">
                        @csrf
                        <td><input type="text" required name="farmsize" class="form-control"></td>
                        <td><input type="text" required name="herbicide" class="form-control" placeholder="Enter name of Chemical.."></td>
                        <td><input type="text" required name="herbicideqty" class="form-control"></td>
                        <td><input type="text" required name="herbicideapplier" class="form-control"></td>
                        <td><input type="checkbox"  name="ppeused" class="form-check-input"></td>
                        <td>
                            <input type="text" name="farmcode" hidden class="form-control" value="{{$farmerdetail->farmcode}}">
                            <input type="text" name="farmid" hidden class="form-control" value="{{$farmerdetail->id}}">
                            <input type="text" name="farmentrance" hidden class="form-control" value="{{$farmentrance->id}}">
                            <input type="text" name="season" hidden class="form-control" value="{{$currentseason}}">
                            <button type="submit" name="addherbicide" class="btn btn-primary">ADD</a></td>
                            </form>
                    </tr>
                </tbody>
            </table>
    </div>
    </div>

    <div class="card my-3">
    <div class="card-body">
            <h5>D. Current Production {{$currentseason}}</h5><span style="font-size:0.8rem;">Note: Maize (M), Sorghum (S), Cowpea (C), Millet (ML), Sesame (Se), Groundnut (Gr)</span>
            <table class="table">
                <thead>
                    <th>Plot</th>
                    <th>Plot Size (Ha)</th>
                    <th>Yield Est (Bags)</th>
                    <th>Crop</th>
                    <th>System</th>
                    <th>Intra-row Spacing (m)</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th></th>

                </thead>
                <tbody><!--TO DO-->
                    @forelse ($farmplots as $farmplot)
                    <tr>
                        <td>{{$farmplot->plotname}}</td>
                        <td>{{$farmplot->fuarea}}</td>
                        <td>{{$farmplot->estimatedyield}}</td>
                        <td>{{$farmplot->crop}}</td>
                        <td>{{$farmplot->system}}</td>
                        <td>{{$farmplot->spacing}}</td>
                        <td>{{$farmplot->fulatitude}}</td>
                        <td>{{$farmplot->fulongitude}}</td>
                        <td><td><a href="{{ route('disablefarm', ['farmcode' => $farmerdetail->farmcode, 'fuid' => $farmplot->id]) }}" class="btn btn-danger">Disable</a></td></td>

                    </tr>
                    @empty
                    <tr>
                        <td>NO FARM PLOTS ON RECORD</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                         <td></td>
                        <td></td>

                    </tr>   
                    @endforelse
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                         <td></td>
                        <td></td>
                        <td><form action="{{route('newfunit')}}" method="get">

                                    <input type="text" name="farmid" id="farmid2" hidden value="{{$farmerdetail->id}}">
                                    <input type="text" name="farmentranceid" id="farmentranceid" hidden value="{{$farmentrance->id}}">
                                    <button class="btn btn-primary" name="newfunit"> Add Farm Unit</button>

                                </form></td>   

                    </tr>
                </tbody>
            </table>
        </div>
        </div>

    @if (empty($farmentrance->internalinspectionid))
            <div class="d-flex">
        <form action="{{route('start')}}" method="post">
            @csrf
        <input type="text" name="farmentrance" hidden class="form-control" value="{{$farmentrance->id}}">
        <input type="text" name="internalinspectionid" hidden class="form-control" value="{{$farmentrance->internalinspectionid}}">
        <input type="text" name="farmid" hidden class="form-control" value="{{$farmerdetail->id}}">
        <input type="text" name="reportid" hidden class="form-control" value="{{$report->id}}">
        <button type="submt" class="btn btn-success">PROCEED</button>
        </form>
        <div class="ml-3"><a  href="{{route('feprofile', ['fcode' => $farmerdetail->farmcode])}}" class="btn btn-danger">Go Back</a></div>
    </div>

    @else
                <div class="d-flex">
        <form action="{{route('continue')}}" method="post">
            @csrf
        <input type="text" name="farmentrance" hidden class="form-control" value="{{$farmentrance->id}}">
        <input type="text" name="internalinspectionid" hidden class="form-control" value="{{$farmentrance->internalinspectionid}}">
        <input type="text" name="inspectionid" hidden class="form-control" value="{{$farmentrance->internalinspectionid}}">
        <input type="text" name="farmid" hidden class="form-control" value="{{$farmerdetail->id}}">
        <input type="text" name="id" hidden class="form-control" value="{{$report->id}}">
        <button type="submt" class="btn btn-success">PROCEED</button>
        </form>
        <div class="ml-3"><a  href="{{route('feprofile', ['fcode' => $farmerdetail->farmcode])}}" class="btn btn-danger">Go Back</a></div>
    </div>
    
        
    @endif
    

</x-layouts.app>