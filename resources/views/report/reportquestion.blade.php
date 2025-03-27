<x-layouts.app>
    <div>
        <div class="form-floating col-md">
            <label for="floatingSelect">Report Name</label> 
        <input type="text" disabled class="form-control" aria-label="Floating label select example" id="floatingSelect" value="{{$report->reportname}}"> 
        </div>
        <div class="form-floating col-md">
            <textarea class="form-control" type="text" id="sectionname" disabled name="sectionid">{{$section->sectionname}}
            </textarea>
            <label for="sectionname">Section Name</label>  
        </div>
        <a href="/report" disabled class="btn btn-danger">Go Back</a>
    </div>
    <div>
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
            <div class="form-floating col-7">
                <label for="floatingQuestion">Question</label> 
                <input type="text" class="form-control" id="floatingQuestion" name="question" aria-label="Floating label select example" >
            </div>
            <div class="form-floating col-3">
                <label for="floatingQuestiontype">Question Type</label> 
                <select id="floatingQuestiontype" name="questiontype">
                    <option value="TYPEA">YES/NO</option>
                    <option value="TYPEB">POOR/FAIR/GOOD/V.GOOD</option>
                </select>
            </div>
            <div class="col-1">
                <button class="btn btn-primary" type="submit">+</button>

            </div>
            </div>
        </form>
    </div>
   
    <div>
        <table class="table table-striped">
            <tbody>
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
              
                  @forelse ($questions as $question)
                  @php
                  $counter+=1;       
                 @endphp
                  <tr>
                  <td>{{$counter}}</td> 
                  <td>{{$question->question}}</td>
                  <td>{{$question->question_seq}}</td>
                  <td>{{$question->questiontype}}</td>
                  <td>{{$question->questionstate}}</td>
                  <td>Edit  Disable 
                    <div><form action="showquestion" method="POST">
                      @csrf
                    <input type="text" hidden value="{{$question->id}}" name="questionid">
                    <button disabled type="submit" class="btn btn-primary">GO</button>
                    </form></div></td>
                  </tr>   
                  @empty
                  <tr><td disabled selected>NO QUESTION CONFIGURED FOR SECTION</td></tr>
                  @endforelse
              
            </tbody>
          </table>
    </div>
</x-layouts.app>
