
<style>
    th, td {
  border: 1px solid;
}
td{
    padding: 5px;
    textalign:justify;}
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 12px;
}
input[type="radio"] {
    
    font-size: 0.75rem;
}
.form-check {
    margin-bottom: 0.5rem;
}
</style>

    @php
        $counter=0;
    @endphp
<div class="card">
    <div class="card-header" style="text-align: center">
    <h3>{{strtoupper($farm->farmname)}}</h3>
    <h4>{{$reportname->reportname}}</h4>
    </div>
    <div class="card-body">
    <table class="table table-bordered" style="border: 1px solid black">
        <tbody>
            <tr>
                <td> <b>FIELD OPERATOR: </b></td>
                <td>{{$farm->farmname}}</td>
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
                <td colspan="2">{{$user->name}}</td>
                <td>
                    <b>DATE OF INSPECTION:</b> 
                </td>
                <td>
                    @if (!empty($inspection->inspectiondate))
                    {{$inspection->inspectiondate}}
                        
                    @else
                      {{$inspection->created_at}}  
                    @endif
                    
                </td>
            </tr>

        </tbody>
    </table>

    
    


    <table  style="border: 1px solid black">
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
                        <div class="form-check">
                @if ($reportquestion->answer==0)
                <input class="form-check-input"   disabled checked type="radio" id="notapp" name="answers[{{$counter}}]" value="0">
                @else
                <input class="form-check-input"   disabled type="radio" id="notapp" name="answers[{{$counter}}]" value="0">
                @endif
                <label class="form-check-label" for="notapp">N/A</label>
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
            <tr style="border: 1px solid black">
                <td colspan="2">The farmer herewith confirms that he/she has complied with the internal 
                    control system of the standard and has declared all used inputs/activities as stated in this form. The farmer has noted the set conditions.
                </td>
            </tr>
            <tr >
                <td>
                    <div  class="text-center">
                @if (!empty($farm->signaturepath))
                     <img src="{{public_path('/storage/'.$farm->signaturepath)}}" alt="" width="70px"><br>
                    <u>{{$farm->farmname}}</u>
                    
                @endif
           <div>
            <br><br>
            <span style="border-top: 1px dashed rgb(86, 84, 84)">Signature/Thumbprint/Name</span><br><br> 
            </div>      
               </td>
                <td>            <div class="text-center">
            @if (!empty($inspection->reportinspectorname()->signaturepath))
                <img src="{{public_path('/storage/'.$inspection->reportinspectorname()->signaturepath)}}" alt="" width="70px"><br>
                <u>{{$inspection->reportinspectorname()->name}}</u>

            @else
            <br><br>    
            @endif
            <br><br>
            <span style="border-top: 1px dashed rgb(86, 84, 84)">Signature/Thumbprint/Name</span><br><br>
           </div></td>
            </tr>
            <tr >
                <td colspan="2">
                    Approval decision by the IMS Manager
                </td>
            </tr>
            <tr >
                <td colspan="2">
                    Compliance this Year 
                    <div  class="d-flex flex-row" style="margin-top: 3px; margin-bottom: 3px"> 
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
                    Name /Signature(s) of Evaluation and Sanction committee:
                    @php
                        $acounter=0;
                    @endphp
                    <table class= "table">
                    @forelse ($inspection->getapprcomm() as $committeemember )
                    @php
                        $acounter+=1;
                    @endphp
                    <tr>
                        <td>{{$acounter}}.</td>
                        <td><b>{{$committeemember->name}}</b> <i style="font-size: 0.75em">({{$committeemember->position}})</i> </td>
                        <td><img src="{{public_path('/storage/'.$committeemember->signaturepath)}}" alt="" width="50px" ></td>
                    </tr>
                    @empty
                        
                    @endforelse
                    </table>

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

