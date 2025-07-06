<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<x-layouts.app>
  <div>
  <div class="d-flex flex-row" style="margin-bottom:20px">
    <div class="form-floating sm-3">
      <div class="input-group col-auto">
        @forelse ($reports as $report)
        <div class="input-group-text">Report</div>
        <label class="visually-hidden" for="floatingSelect">Report Name</label> 
        <input type="text" disabled class="form-control"  id="floatingSelect" value="{{$report->reportname}}">  
        @empty
        <h1>NO SECTIONS CONFIGURED ON SYTSTEM</h1>
        @endforelse           
      </div> 

    </div>

      <div><a href="/report"  class="btn btn-danger" style="margin-left: 10px; margin-right:10px">Go Back</a></div> 
  </div>

  <div style="margin-bottom:20px">
    <div>
      <form id="newsection" method="POST" action="newsection">
        @csrf
        <div class="row">
            <div class="form-floating col-7" >
                <textarea class="form-control" type="textarea" id="sectionname" placeholder="Enter Section Name" name="sectionname">
                </textarea>
                <label for="sectionname">Section Name</label>  
            </div>
            <div class="form-floating col-1" >
                <input class="form-control" type="number" id="section_seq" placeholder="Seq" name="section_seq">
                <label for="section_seq">Sequence</label>  
            </div>
            <div class="form-floating col-1" >
              <select name="sectionstate" class="form-control" id="sectionstate">
                <option value="ACTIVE">ACTIVE</option>
                <option value="DISABLED">DISABLED</option>
              </select>
              <label for="sectionstate">Status</label>  
          </div>
            <div class="form-floating col-2" >
              <input class="form-control" type="number" id="reportid" value="{{$report->id}}" name="reportid" hidden>
                <button type="submit" class="btn btn-primary">+ </button>
            </div>
        </div>
        </form>
    </div>

  </div>


  <div>
    <table class="table table-striped display" id="reports" style="width:100%">
      
        <thead>
          <th>#</th>
          <th>Section Name </th>
          <th>Section Seq</th>
          <th>Active</th>
          <th>Action</th>
        </thead>
        <tbody>
        @php
         $counter=0;       
        @endphp
        
            @forelse ($sections as $section)
            @php
            $counter+=1;       
           @endphp
           
            <tr>
            <td>{{$counter}}</td> 
            <td>{{$section->sectionname}}</td>
            <td>{{$section->section_seq}}</td>
            <td>{{$section->sectionstate}}</td>
            <td>Edit  Disable 
              <div><form action="showquestion" method="POST">
                @csrf
              <input type="text" hidden value="{{$section->id}}" name="sectionid">
              <input type="text" hidden value="{{$report->id}}" name="reportid">
              <button type="submit" class="btn btn-primary">GO</button>
              </form></div></td>
            </tr>   
            @empty
            <tr><td disabled selected>NO REPORTS CONFIGURED ON SYTSTEM</td></tr>
            @endforelse
        
      </tbody>
    </table>
  </div>
  </div>
  <script>
    new DataTable('#reports');
</script>
</x-layouts.app>