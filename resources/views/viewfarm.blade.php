<x-layouts.app>
<div>
   
<div>
    <div class="container-sm" >
        <div class="row">
            <div class="col">
                <h4 class="h4" style="text-align: center; background-color:cornflowerblue">FARM DETAILS</h4>
                <div class="mb-3">
                <label>FARM NAME</label>
                <input type="text" readonly class="form-control" value="{{$farm->farmname}}">
                </div>
                <div class="mb-3">
                    <label>FARM CODE</label>
                    <input type="text" readonly class="form-control" value="{{$farm->farmcode}}">
                </div>
                <div class="mb-3">
                    <label>COMMUNITY</label>
                    <input type="text" readonly class="form-control" value="{{$farm->farmcode}}">
                </div>
                <div class="mb-3">
                    <label>FARM AREA {{$farm->measurement}}</label>
                    <input type="text" readonly class="form-control" value="{{$farm->farmarea }}">
                </div>
                <div class="mb-3">
                    <label>ASSIGNED STAFF</label>
                    <input type="text" readonly class="form-control" value="Victor Irenuma">
                </div>
            </div>
            <div class="col">
                <h4 class="h4" style="text-align: center; background-color:cornflowerblue">INSPECTION SUMMARY</h4>
                <div class="mb-3">
                    <label>STATUS</label>
                    <input type="text" readonly class="form-control" value="{{$farm->farmstate}}">
                </div>
                <div class="mb-3">
                    <label>LAST INSPECTION</label>
                    <input type="text" readonly class="form-control" value="DATE: {{$farm->lastinspection}} | Score : N/A'">
                </div>
                <div class="mb-3">
                    <label>NEXT INSPECTION</label>
                    <input type="text" readonly class="form-control" value="{{$farm->nextinspection}}">
                    <form action="" method="POST">
                        <input type="number" name="farmid" hidden value="{{$farm->id}}">
                        <button class="btn btn-primary"> >> </button>

                    </form>
                </div>
                <h4 class="h4" style="text-align: center; background-color:cornflowerblue">INSPECTION RECORDS</h4>
                TO DO: Show list of all Farm Inspection Records
            </div>
          </div>

    </div>
</div>

</div>
</x-layouts.app>