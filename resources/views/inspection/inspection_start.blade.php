<x-layouts.app>
    @php
    $nosections= sizeof($reportsections);
    $current=0+$sectioncounter;
    $noquestions= sizeof($reportquestions);
    $counter=0;
 @endphp
    <div>
        <div class="row">
            <input type="text"  disabled class="form-control" value={{$farm->farmcode}}>
        </div>
        <div class="row">
            <input type="text"  disabled class="form-control" value='{{$report->reportname}}'>
        </div>
        <div>
            <h4>Section {{$current+1}} of {{$nosections}} </h4>
            <h5>{{$reportsections[$current]->sectionname}} </h5>
            <form action="nextsection" method="POST">
                @csrf
            <table class="table table-striped">
                <tbody>
                <thead>
                    <th>#</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Remarks</th>
                </thead>
                @forelse ($reportquestions as $question )
                @if ($question->reportsectionid==$reportsections[$current]->id)

                <tr>
                    <td class="col-1">{{$counter+1}}</td>
                    <td class="col-4">{{$question->question}}</td>
                    <td class="col-2"> 
                        <div class="form-check">
                            <input class="form-check-input"  type="radio" id="poor" name="answers[{{$counter}}]" value="1" required>
                            <label class="form-check-label" for="poor">Poor</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input"  type="radio" id="fair" name="answers[{{$counter}}]" value="2">
                            <label class="form-check-label" for="fair">Fair</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input"  type="radio" id="good" name="answers[{{$counter}}]" value="3">
                            <label class="form-check-label" for="good">Good</label>
                        </div>
                        <div class="form-check">

                            <input class="form-check-input"  type="radio" id="vgood" name="answers[{{$counter}}]" value="4">
                            <label class="form-check-label" for="vgood">Very Good</label>
                        </div>              
                    </td>
                    <td class="col-3">
                        <textarea class="form-control" name="comments[]" placeholder="Enter any Remarks/Justification"></textarea>
                        <input class="form-check-input"  hidden type="number" id="question" name="question[]" value="{{$question->id}}" >
                    </td>
                </tr>
                @php
                $counter+=1;
                @endphp
                @endif
                @empty
                @endforelse
                </tbody>
            </table>
            <input type="number" hidden name="sectioncounter" value="{{$current+1}}">
            <input type="number" hidden name="inspectionreportid" value="{{$inspection->id}}">
            <input type="number" hidden name="farmid" value="{{$farm->id}}">
            @if ($current+1==$nosections)
            <input type="submit" class="btn btn-primary" value="Submit">
            @else
            <input type="submit" class="btn btn-primary" value="Next Section">
            @endif
            
        </form>
        </div>

        </div>
    </div>
</x-layouts.app>

