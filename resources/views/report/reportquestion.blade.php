<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<x-layouts.app>
    <div>
        <div class="form-floating col-md">
            <div class="input-group" style="margin-bottom: 20px">
                <div class="input-group-text">Report</div>
            <label class="visually-hidden" for="floatingSelect">Report</label> 
        <input type="text" disabled class="form-control" aria-label="Floating label select example" id="floatingSelect" value="{{$report->reportname}}"> 
            </div>
        </div>
        <div class="form-floating col-md">
            <div class="input-group"  style="margin-bottom: 20px">
                <div class="input-group-text">Report</div>
            <textarea class="form-control" type="text" id="sectionname" disabled name="sectionid">{{$section->sectionname}}
            </textarea>
            <label class="visually-hidden" for="sectionname">Section Name</label> 
            </div> 
        </div>
        <div  style="margin-bottom: 20px"> <a href="/report" disabled class="btn btn-danger">Go Back</a>  </div>
        
    </div>
    <div style="text-align: center; background-color:#228B22 ; padding:10px; color:white; margin-bottom:20px">
        <h4> NEW QUESTION</h4>
    </div>
    <div>
        <form action="newquestion" method="post">
            <div class="row">
                @csrf
            <div class="form-floating col-1">
                <label for="floatingSeq">Seq</label> 
                <input type="number" class="form-control" id="floatingSeq" name="question_seq" aria-label="Floating label select example" >
                <input type="number" hidden id="reportsectionid" name="reportsectionid" value="{{$section->id}}">
                <input type="number" hidden id="reportid" name="reportid" value="{{$report->id}}">
            </div>
            <div class="form-floating col-6">
                <label for="floatingQuestion">Question</label> 
                <input type="text" class="form-control" id="floatingQuestion" name="question" aria-label="Floating label select example" >
            </div>
            <div class="form-floating col-3">
                <label for="floatingQuestiontype">Question Type</label> 
                <select class="form-control form-select" id="floatingQuestiontype" name="questiontype">
                    <option value="TYPEA">YES/NO</option>
                    <option value="TYPEB">POOR/FAIR/GOOD/V.GOOD</option>
                    <option value="TYPEC">COMMENTS ONLY</option>
                </select>
            </div>
            <div class="col-1">
                <button class="btn btn-primary" type="submit">+</button>

            </div>
            </div>
        </form>
    </div>
   
    <div>
        <table class="table table-striped" id="reports">
              <thead>
                <th>#</th>
                <th>Question </th>
                <th>Question Seq</th>
                <th>Type</th>
                <th>Active</th>
                <th>Action</th>
              </thead>
              @php
               $counter=0;       
              @endphp
              <tbody>
              
                  @forelse ($questions as $question)
                  @php
                  $counter+=1;       
                 @endphp
                  <tr>
                    <form action="{{route('editquestion')}}" method="POST">
                  <td>{{$counter}}</td> 
                  <td>{{$question->question}}</td>
                  <td><input type="number" class="form-control" value="{{$question->question_seq}}" name='questionseq'>
                    </td>
                  <td>{{$question->questiontype}}</td>
                  <td>
                    @if ($question->questionstate=='ACTIVE')
                        <input type="checkbox" checked name='questionstate' class="form-check-input">
                    @else
                        <input type="checkbox" name='questionstate' class="form-check-input">
                    @endif
                    </td>
                  <td>
                    <div>
                      @csrf
                    <input type="text" hidden value="{{$question->id}}" name="questionid">
                    <button type="submit" class="btn btn-primary">GO</button>
                    </div></td>
                    </form>
                  </tr>   
                  @empty
                  <tr><td disabled selected>NO QUESTION CONFIGURED FOR SECTION</td></tr>
                  @endforelse
              
            </tbody>
          </table>
    </div>
    <script>
        new DataTable('#reports');
    </script>
</x-layouts.app>
