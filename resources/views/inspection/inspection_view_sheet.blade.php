<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<x-layouts.app>
    @php
        $counter=0;
    @endphp
<div class="card">
    <div class="card-header" style="text-align: center">
    <h3>{{strtoupper($farm->farmname)}}</h3>
    <h4>{{$reportname->reportname}}</h4>
    </div>
    <div class="card-body">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td> <b>FIELD OPERATOR: </b></td>
                <td></td>
                <td><b>CODE NUMBER:</b></td>
                <td> {{$farm->farmcode}}</td>
            </tr>
            <tr>
                <td><b> HOUSE COORDINATE: </b></td>
                <td><b>LATITUDE:</b></td>
                <td>{{$farm->latitude}}</td>
                <td><b>LONGITUDE:</b></td>
                <td>{{$farm->longitude}}</td>
            </tr>
            <tr>
                <td><b>NAME OF INTERNAL INSPECTOR: </b> </td>
                <td colspan="2">{{$inspector->name}}</td>
                <td>
                    <b>DATE OF INSPECTION:</b> 
                </td>
                <td>
                    {{$inspection->created_at}}
                </td>
            </tr>

        </tbody>
    </table>
    @if (strpos($reportname->reportname, '8B')!==false)
        <div class="card-body my-3">
        <table class="table table-striped">
          <thead>
            <th>Ginger Plot Names</th>
            <th>Plot Size in Hectares</th>
            <th>Estimation of production in KGs</th>
            <th>Latitude</th>
            <th>Longitude</th>
          </thead>
          <tbody>
            @php
                $farmentrance=$inspection->reportgingerproduction();
            @endphp
                        @forelse ($farmentrance->reportprodhistory() as $prodhistory )
                        <tr>
                <td>{{$prodhistory->plotname}}</td>
                 <td>{{$prodhistory->fuarea}}</td>
                  <td>{{number_format($prodhistory->estimatedyield,2)}}</td>
                   <td>{{$prodhistory->fulatitude}}</td>
                    <td>{{$prodhistory->fulongitude}}</td>
            
            </tr>
                
            @empty
                
            @endforelse
          </tbody>
        </table>
        </div>
        
    @endif



    
    


    <table class="table table-hover">
        <tbody>
        <thead>
            <th>#</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Remarks</th>
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
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td colspan="2"><b>DECLARATION</b></td>
            </tr>
            <tr>
                <td colspan="2">The farmer herewith confirms that he/she has complied with the internal 
                    control system of the standard and has declared all used inputs/activities as stated in this form. The farmer has noted the set conditions.
                </td>
            </tr>
            <tr>
                <td>Signature/Thumbprint of the field operator:<br/>  
                 @if (!empty($farm->signaturepath))
                <div class="col-auto" style="margin-left:30%; margin-right:30%;">
                     <img src="{{Request::root().('/storage/'.$farm->signaturepath)}}" alt="" width="70px"></div>
                @endif       
               </td>
                <td> Signature of the internal inspector: <br/>
                                @if (!empty($inspector->signaturepath))
                <img src="{{Request::root().('/storage/'.$inspector->signaturepath)}}" alt="" width="70px"><br>

            @else
            <br><br>    
            @endif
                
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Approval decision by the internal inspector
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Compliance this Year 
                    <div  class="d-flex flex-row"> 
                        @switch($inspection->inspectionstate)
                        @case('APPROVED')
                        <div class="p-2">
                            <input  class="form-check-input" checked type="checkbox" disabled name="" id="approved">
                            <label class="form-check-label" for="approved">Approved</label>
                        </div>
                        <div class="p-2">
                            
                            <input class="form-check-input"  type="checkbox" disabled name="" id="approvedwithcondition">
                            <label class="form-check-label" for="approvedwithcondition">Approved with Condition</label>
                        </div>
                        <div class="p-2">
                           
                            <input class="form-check-input"  type="checkbox" disabled name="" id="Notapproved">
                            <label class="form-check-label" for="Notapproved">Not Approved</label>
                        </div>
                            @break
                        @case('CONDITIONAL')
                        <div class="p-2">
                            <input  class="form-check-input" type="checkbox" disabled name="" id="approved">
                            <label class="form-check-label" for="approved">Approved</label>
                        </div>
                        <div class="p-2">
                            
                            <input class="form-check-input" checked  type="checkbox" disabled name="" id="approvedwithcondition">
                            <label class="form-check-label" for="approvedwithcondition">Approved with Condition</label>
                        </div>
                        <div class="p-2">
                           
                            <input class="form-check-input"  type="checkbox" disabled name="" id="Notapproved">
                            <label class="form-check-label" for="Notapproved">Not Approved</label>
                        </div>
                            @break
                        @case('REJECTED')
                        <div class="p-2">
                            <input  class="form-check-input" type="checkbox" disabled name="" id="approved">
                            <label class="form-check-label" for="approved">Approved</label>
                        </div>
                        <div class="p-2">
                            
                            <input class="form-check-input"  type="checkbox" disabled name="" id="approvedwithcondition">
                            <label class="form-check-label" for="approvedwithcondition">Approved with Condition</label>
                        </div>
                        <div class="p-2">
                           
                            <input class="form-check-input" checked type="checkbox" disabled name="" id="Notapproved">
                            <label class="form-check-label" for="Notapproved">Not Approved</label>
                        </div>
                            @break
                        @default
                        <div class="p-2">
                            <input  class="form-check-input" type="checkbox" disabled name="" id="approved">
                            <label class="form-check-label" for="approved">Approved</label>
                        </div>
                        <div class="p-2">
                            
                            <input class="form-check-input"  type="checkbox" disabled name="" id="approvedwithcondition">
                            <label class="form-check-label" for="approvedwithcondition">Approved with Condition</label>
                        </div>
                        <div class="p-2">
                           
                            <input class="form-check-input"  type="checkbox" disabled name="" id="Notapproved">
                            <label class="form-check-label" for="Notapproved">Not Approved</label>
                        </div>   
                    @endswitch

                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Additional conditions or sanctions or corrective actions to be undertaken:
                    <br/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Comments of Evaluation and Sanction committee: {{$inspection->comments}} || {{$inspection->conditions}}

                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Name /Signature of Evaluation and Sanction committee:
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Corrective action verification (when required)
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Name/Signature/Date of verification officer:
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Signature of evaluation and sanction committee:
                </td>
            </tr>
        </tbody>
    </table>
    </div>
</div>
</div>
</x-layouts.app>
