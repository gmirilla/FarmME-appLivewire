<x-layouts.app>
    <div>
        <div class="row">
            <form action="/inspection/start" method="post">
                @csrf
                <select name="farmid" id="farmselect" class="form-control">
                    @forelse ( $farms as $farm )
                        <option value="{{$farm->id}}">{{$farm->farmcode}}| {{$farm->farmname}}</option>  
                    @empty
                    <option value="0">No Farms have been assigned to you</option>
                    @endforelse
                </select>
                <select name="reportid" id="reportselect" class="form-control">
                    @forelse ( $reports as $report )
                        <option value="{{$report->id}}">{{$report->reportname}}</option>  
                    @empty
                    <option value="0">No Reports configured on the system</option>
                    @endforelse
                </select>
                <button type="submit" class="btn btn-primary">Next</button>
                </form>
        </div>
    
    </div>
</x-layouts.app>
