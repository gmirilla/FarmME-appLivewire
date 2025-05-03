<x-layouts.app>
    <meta name="_token" content="{{ csrf_token() }}">
    <div>
        <div>
            <form>
                <div class="form-floating col-md">
                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example" onchange="getSection()">
                        @forelse ($reports as $report)
                        <option value="{{$report->id}}">{{$report->reportname}}</option>   
                        @empty
                        <option disabled selected>NO REPORTS CONFIGURED ON SYTSTEM</option>
                        @endforelse
                    </select>
                    <label for="floatingSelect">Report Name</label>            
                    <button type="submit" disabled class="btn btn-primary">GO</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#exampleModal" data-bs-whatever="test for creating">New report</button>  
                </div> 
            </form>
        </div>
        <div>
            <form>
              <div id="test">
                <select class="	form-select" name="test2" id="test2">
                  <option value="3">ANGRY</option>
                </select>
              </div>
                <div class="form-floating col-md">
                    <select class="form-select" id="floatingSection" aria-label="Floating label select example">
                      <option disabled selected>NO REPORTS CONFIGURED ON SYTSTEM</option>
                    </select>
                    <label for="floatingSection">Section Name</label>            
                    <button type="submit" disabled class="btn btn-primary">GO</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#sectionModal" data-bs-whatever="test for creating">New Section</button>
                </div> 

            </form>
        </div>
        <form id="newquestion">

        <div class="row">
            
            <div class="form-floating col-1">
                <input class="form-control" type="number" id="serialno">
                <label for="serialno">S/N #</label>  
            </div>
            <div class="form-floating col-7" >
                <textarea class="form-control" type="textarea" id="question" placeholder="Enter Question">
                </textarea>
                <label for="question">Question</label>  
            </div>
            <div class="form-floating col-2" >
                <input class="form-control" type="textarea" id="questiontype" placeholder="Select Question Type">
                <label for="question">Type</label>  
            </div>
            <div class="form-floating col-2" >
                <button class="btn btn-primary">+ </button>
            </div>
        </div>
        </form>
        <div>
            <table class="table table-striped">
                <tbody>
                    <thead>
                        <th>#</th>
                        <th>Question </th>
                        <th>Type</th>
                        <th>Active</th>
                    </thead>
                    <tr>
                        <td>

                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>


    </div>
<!-- REPORT Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create New Report</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="newreport" method="post" action='/report/new'>
          @csrf
        <div class="modal-body">
          
            <div class="mb-3">
              <label for="farmcode" class="col-form-label">Report Name</label>
              <input type="text" class="form-control fcode" id="reportname" name="reportname">
            </div>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
      </div>
    </div>
  </div>
  
  <!-- SECTION Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1" aria-labelledby="sectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sectionModalLabel">Create New Section</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="newsection" method="post" action='/report/newsection'>
          @csrf
        <div class="modal-body">
            <div class="mb-3">
                <label for="farmcode" class="col-form-label">Seq No</label>
                <input type="number" class="form-control fcode" id="section_seq" name="section_seq">
              </div>  
          
            <div class="mb-3">
              <label for="farmcode" class="col-form-label">Section Name</label>
              <input type="text" class="form-control fcode" id="sectionname" name="sectionname">
            </div>  
            <div class="mb-3">
                <label for="farmcode" class="col-form-label">Report Name</label>

                <select class="form-select fcode" id="reportid" name="reportid">
                    @forelse ($reports as $report)
                    <option value="{{$report->id}}">{{$report->reportname}}</option>   
                    @empty
                    <option disabled selected>NO REPORTS CONFIGURED ON SYTSTEM</option>
                    @endforelse
                  </select>    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
      </div>
    </div>
  </div>


<script>

function getSection(){
    var reportid=document.getElementById("floatingSelect").value;
    var selectSection = document.getElementById("test2");
    var i=0;
    var y='';
    data ={
        reportid: reportid,
    };
    fetch('/report/getsection', {
    method: 'POST', // Use the POST method
    headers: {
        'Content-Type': 'application/json',
         'X-CSRF-TOKEN': document.querySelector('meta[name="_token"]').getAttribute('content') // Include the CSRF token
    },
    body: JSON.stringify(data) // Convert the data to JSON
})
.then(response => response.json())
.then(data => {
    data.forEach(function(element) {
        let sectionData=data[i];
        i++;
       y=y + "<option value=" + sectionData.id + ">"+ sectionData.sectionname + "</option>";
        //s= document.createElement("option");
       // s.value=sectionData.id;
       // s.text=sectionData.sectionname;
        console.log(sectionData);
});
    selectSection.innerHtml=`
                <option value="4">Option 4</option>
                <option value="5">Option 5</option>
                <option value="6">Option 6</option>
            `;
    console.log('Success:', );
    console.log(y);
})
.catch(error => {
    console.error('Error:', error);
});
}
</script>
</x-layouts.app>