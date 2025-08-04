<x-layouts.app>

    
    @php
        $counter=0;
    @endphp


<div class="card">
    <div class="card-header" style="text-align: center">
    <h3><b>FARM ENTRANCE FORM | Registration Date: {{$farmentrance->regdate}}</b> <br>
        {{strtoupper($farm->farmname)}}</h3>
        <h4>UEBT/RA Certification Program</h4>
    <h4>@if (!empty($farmentrance->farm_period))
         {{$farmentrance->farm_period}} Season | 
    @endif
        {{$reportname->reportname}}</h4>
    </div>
    <div class="card-body">
    <table class="table table-bordered" style="border: 1px solid black">
        <tbody>
            <tr>
                <td style="width: 20%"><b>SURNAME:</b><br/>
                {{$farmentrance->surname}}
                </td>
                <td style="width: 20%"><b>OTHER NAME(s):</b><br/>
                {{$farmentrance->fname}}
                </td>
                <td style="width: 20%"><b>GENDER:</b><br/>
                {{$farm->gender}}
                </td>
                <td style="width: 20%"><b>FARMER'S CODE:</b><br/>
                {{$farmentrance->farmcode}}
                </td>
                <td style="width: 20%"><b>NATIONAL ID:</b><br/>
                {{$farmentrance->nationalidno}}
                </td>
            </tr>
            <tr>
            <td><b>YEAR OF BIRTH:</b><br/>
                {{$farm->yob}}
                </td>
                                <td><b>PHONE NUMBER:</b><br/>
                {{$farmentrance->phoneno}}
                </td>
                <td><b>HOUSEHOLD SIZE:</b><br/>
                {{$farmentrance->householdsize}}
                </td>
               <td><b>ADDRESS:</b><br/>
                {{$farmentrance->address}}
                </td>
                <td>
                    @if (!empty($farmentrance->farmerpicture))
                        <img src="{{public_path('/storage/'.$farmentrance->farmerpicture)}}" alt="" style="max-height: 100px; max-width: 100px;">
                    @endif 

                </td>
            </tr>
            <tr>
                <td colspan="2"> <b>DATE OF LAST INSPECTION: </b><br/>
                {{$farmentrance->lastinspection}}</td>
                <td colspan="3"><b>OUTCOME OF LAST INSPECTION:</b><br>{{$farmentrance->inpsectionresult}}
                </td>
            </tr>
            <tr>
                <td colspan="2"><b>NAME OF CROP: </b><br>{{$farmentrance->crop}}</td>
                <td colspan="2"><b>VARIETY:</b><br/>{{$farmentrance->cropvariety}}</td>
                <td><b>Source of Planting Material: </b>{{$farmentrance->sourceofcrop}}</td>
            </tr>
        </tbody>
    </table>

    <h4>B. HISTORICAL PRODUCTION : HIBISCUS PRODUCTION</h4>
    <table  class="table table-bordered" style="border: 1px solid black">
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
                        <td></td>
                     </tr>   
                    @empty
                <tr></tr>
            @endforelse

        </tbody>
    </table>
    <h4>C. HISTORICAL PRODUCTION: AGROCHEMICALS USED {{$prevseason}}</h4>
    <table  class="table table-bordered" style="border: 1px solid black">
                        <thead>
                    <th>Farm Size (Ha)</th>
                    <th>Products<br>(Fertilizers, Pesticides, Herbicides)
                    </th>
                    <th>QUANTITY APPLIED (LITER'S/BAGS)</th>
                    <th>NAME OF PERSON WHO APPLIED</th>
                    <th>PPE Used (Y/N)</th>
                    <th></th>
                </thead>
        <tbody>
                    @forelse ($farmentrance->reportagrochems() as $agrochem)
                        <tr>
                        <td><input type="text" disabled name="farmsize" class="form-control" value="{{$agrochem->farmsize}}"></td>
                        <td><input type="text" disabled name="herbicide" class="form-control" value="{{$agrochem->herbicidename}}"></td>
                        <td><input type="text"  disabled name="herbicideqty" class="form-control" value="{{$agrochem->quantity}}"></td>
                        <td><input type="text" disabled name="herbicideapplier" class="form-control" value="{{$agrochem->nameofperson}}"></td>
                        <td>    <input type="checkbox" name="ppeused" class="form-check-input" 
                                    disabled {{ $agrochem->ppeused ? 'checked' : '' }}>
                        </td>
                        <td></td>
                    </tr> 
            @empty
                <tr></tr>
            @endforelse

        </tbody>
    </table>
      <h4>D. CURRENT PRODUCTION {{$farmentrance->farm_period}}</h4>
    <table  class="table table-bordered" style="border: 1px solid black">
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
        <tbody>
             <tbody><!--TO DO-->
                    @forelse  ($farmentrance->reportprodhistory() as $farmplot )
                    <tr>
                        <td>{{$farmplot->plotname}}</td>
                        <td>{{$farmplot->fuarea}}</td>
                        <td>{{$farmplot->estimatedyield}}</td>
                        <td>{{$farmplot->crop}}</td>
                        <td>{{$farmplot->system}}</td>
                        <td>{{$farmplot->spacing}}</td>
                        <td>{{$farmplot->fulatitude}}</td>
                        <td>{{$farmplot->fulongitude}}</td>
                        <td><td></td>

                    </tr>
            @empty
                <tr></tr>
            @endforelse

        </tbody>
    </table>

 <h4>E. FARM ENGAGEMENT & INITIAL RISK ASSESSMENT</h4>
    <table  class="table table-bordered" style="border: 1px solid black; margin-top:5px;">
        <tbody>
        <thead>
            <th>#</th>
            <th style="width:50%">Question</th>
            <th style="width:20%">Answer</th>
            <th style="width:25%">Remarks</th>
        </thead>
    @forelse ($reportquestions as $reportquestion )
    <tr>
        <td>{{$counter+1}}</td>
        <td>{{$reportquestion->question }}</td>
        <td class="col-2">

            @switch($reportquestion->questiontype)
                @case("TYPEA")
                <!-- Html to handle TypeB questions (Yes/NO)-->
                <div class="form-check">
                    @if ($reportquestion->answer==1)
                    <input class="form-check-input"   disabled checked type="radio" id="yes" name="answers[{{$counter}}]" value="1" >
                    @else
                    <input class="form-check-input" disabled type="radio" id="yes" name="answers[{{$counter}}]" value="1" >
                    @endif
                    <label class="form-check-label" for="yes">YES</label>
                </div>
                <div class="form-check">
                    @if ($reportquestion->answer==2)
                    <input class="form-check-input" disabled  checked type="radio" id="no" name="answers[{{$counter}}]" value="2">
                    @else
                    <input class="form-check-input" disabled type="radio" id="no" name="answers[{{$counter}}]" value="2">
                    @endif
                    <label class="form-check-label" for="no">NO</label>
                </div>
                    
                    @break
                @case("TYPEB")
         <!-- Html to handle TypeB questions (Poor/fair/good/v.good)-->
            <div class="form-check">
                @if ($reportquestion->answer==1)
                <input class="form-check-input"    disabled checked type="radio" id="poor" name="answers[{{$counter}}]" value="1" >
                @else
                <input class="form-check-input" disabled type="radio" id="poor" name="answers[{{$counter}}]" value="1" required>
                @endif
                <label class="form-check-label" for="poor">Poor</label>
            </div>
            <div class="form-check">
                @if ($reportquestion->answer==2)
                <input class="form-check-input"   disabled checked type="radio" id="fair" name="answers[{{$counter}}]" value="2">
                @else
                <input class="form-check-input"   disabled type="radio" id="fair" name="answers[{{$counter}}]" value="2">
                @endif
                <label class="form-check-label" for="fair">Fair</label>
            </div>
            <div class="form-check">
                @if ($reportquestion->answer==3)
                <input class="form-check-input"   disabled checked type="radio" id="good" name="answers[{{$counter}}]" value="3">
                @else
                <input class="form-check-input"  disabled type="radio" id="good" name="answers[{{$counter}}]" value="3">
                @endif
                <label class="form-check-label" for="good">Good</label>
            </div>
            <div class="form-check">
                @if ($reportquestion->answer==4)
                <input class="form-check-input"   disabled checked type="radio" id="vgood" name="answers[{{$counter}}]" value="4">
                @else
                <input class="form-check-input"   disabled type="radio" id="vgood" name="answers[{{$counter}}]" value="4">
                @endif
                <label class="form-check-label" for="vgood">Very Good</label>
            </div> 
                @break

                @case("TYPEC") 
                <!-- Html to handle TypeB questions (comments only)-->
                <div class="form-check">
                    <input class="form-check-input"   disabled checked type="radio" id="commentonly" name="answers[{{$counter}}]" value="1" >
                    <label class="form-check-label" for="commentsonly">Comment Only</label>
                </div>   
                @break
            
                @default
                    
            @endswitch
        </td>
        <td>{{$reportquestion->sectionidcomments}}</td>
    </tr>
    @php
    $counter+=1;
    @endphp
    @empty
        
    @endforelse
        
        </tbody>
    </table>

    <!-- Declaration page-->
    <div style="margin-top: 20px">
    <table class="table table-bordered" style="border: 1px solid black">
        <tbody>
            <tr>
                <td colspan="2"><b>DECLARATION</b></td>
            </tr>
           <tr><td style="width: 50%" class="text-justify">
            <p>I, <b><u>{{$farm->farmname}}</u></b>, the farm owner, declare that, this information is correct, and on this day pledge to adhere
             at all times to the conditions for the UEBT/RA certification process and sustainable agriculture production </p><br>
            
            <div class="text-center flex-column">
                @if (!empty($farm->signaturepath))
                <div class="col-auto" style="margin-left:30%; margin-right:30%;">
                     <img src="{{Request::root().('/storage/'.$farm->signaturepath)}}" alt="" width="70px"></div>
                @endif
           <div>
            <span style="border-top: 1px dashed rgb(86, 84, 84)">Signature/Thumbprint</span><div>

           </div>

            <span style="border-top: 1px dashed rgb(86, 84, 84)">Date: {{$farmentrance->regdate}}</span>
        </td><td>
        <p>I, the field officer, confirm that the above information is correct.</p> 
        <br>
           <b><u>{{$farmentrance->reportinspectorname()->name}}</u></b><br>
           (Name of Field Officer) 

           <br>
            
           <div class="text-center">
            @if (!empty($farmentrance->reportinspectorname()->signaturepath))
                <img src="{{Request::root().('/storage/'.$farmentrance->reportinspectorname()->signaturepath)}}" alt="" width="70px"><br>

            @else
            <br><br>    
            @endif
            
            <span style="border-top: 1px dashed rgb(86, 84, 84)">Signature/Thumbprint</span><br><br>
           </div>
 
            

            <span style="border-top: 1px dashed rgb(86, 84, 84)">Date:{{$farmentrance->regdate}}</span>



        </td></tr>
        </tbody>
    </table>
    </div>
@forelse ($farmentrance->reportprodhistory() as $farmplot )

    <div style="page-break-before: always;">
        <br>
        <h4>FARM SKETCH</h4>
        <table  class="table table-bordered" style="border: 1px solid black">
            <tr>
                <td style="width: 25%"><b>NAME OF FARMER: </b></td>
                <td style="width: 25%">{{strtoupper($farm->farmname)}}</td>
                <td style="width: 25%"><b>FARM CODE:</b> </td>
                <td style="width: 25%">{{$farm->farmcode}}</td>
            </tr>
            <tr>
                <td style="width: 25%"><b>NAME OF FARM: </b></td>
                <td style="width: 25%">{{$farmplot->plotname}}</td>
                <td style="width: 25%"><b>FARM SIZE:</b> </td>
                <td style="width: 25%">{{$farmplot->fuarea}} Ha</td>
            </tr>
                        <tr>
                <td style="width: 25%"><b>LOCATION OF FARM:</b> </td>
                <td style="width: 25%">{{$farm->community}}</td>
                <td style="width: 25%"><b>NAME OF CROP: </b></td>
                <td style="width: 25%">{{$farm->crop}}</td>
            </tr>
                                    <tr>
                <td style="width: 25%"><b>LATITUDE:</b> </td>
                <td style="width: 25%">{{$farmplot->fulatitude}}</td>
                <td style="width: 25%"><b>LONGITUDE: </b></td>
                <td style="width: 25%">{{$farmplot->fulongitude}}</td>
            </tr>
            <tr>
                <td colspan="4" style="padding: 5px;">
                    {{$farmplot->id}}
                    @if (!empty($farmplot->imagefilepath))
                        <img src="{{ asset($farmplot->imagefilepath) }}" alt="" width="100%;" height="1000px;">
                    @endif           
                </td>
            </tr>
                        <tr>
                <td colspan="4">
                    @if (!empty($farmplot->imagefilepath))
                        {{url($farmplot->imagefilepath)}} <br>
                        {{Request::root().($farmplot->imagefilepath)}}
                    @endif           
                </td>
            </tr>
        </table>
    
@empty
    
@endforelse



    </div>
</div>
</div>


</x-layouts.app>