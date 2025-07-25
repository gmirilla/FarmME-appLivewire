<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
@php
  $datayear=date('Y');
@endphp
<x-layouts.app>
  <div class="bg-transparent col-sm-9 mb-3">
    <div class="class-body"><h3 style="color: #388E3C"><b>Dashboard: Welcome {{$user->fname}}</b></h3> </div>
  </div>
  <div>
  <div class="card  bg-transparent col-sm-10">
    <div class="card-body">
      <div class="row gy-2 gx-3 align-items-center mb-3" >
        <div class="card col-auto mr-3" style="background-color:white; color:#388E3C; width:18rem ; margin-right:5px">
          <div class="card-body">
            <div style="font-size: 30px"><i class="fa fa-user fa-lg"></i> </div>
            
              <h3 class="card-title text-center">{{$usercount}}</h3>
            <div  class="card-footer" style="text-align: center; ">
              <h5  style="color: #388E3C">Active Users</h5>
            </div>
          </div>
        </div>
        <div class="card col-auto mr-3" style="background-color:white; color:#388E3C; width:18rem; margin-right:5px">
          <div class="card-body">
            <div style="font-size: 30px"><i class="fa fa-leaf" aria-hidden="true"></i></div>
              <h3 class="card-title text-center">{{$farmpendingcount}}</h3>
            <div  class="card-footer" style="text-align: center; ">
              <h5  style="color: #388E3C">Farm Entrance Pending</h5>
            </div>
          </div>
        </div>
        <div class="card col-auto mr-3" style="background-color:white; color:#388E3C; width:18rem ; margin-right:5px">
          <div class="card-body">
            <div style="font-size: 30px"><i class="fa fa-pagelines" aria-hidden="true"></i></div>
              <h3 class="card-title text-center">{{$farmcount}}</h3>
            <div  class="card-footer" style="text-align: center; ">
              <h5  style="color: #388E3C">Active Farmers</h5>
            </div>
          </div>
        </div>
                <div class="card col-auto mr-3" style="background-color:white; color:#388E3C; width:18rem ; margin-right:5px">
          <div class="card-body">
            <div style="font-size: 30px"><i class="fa fa-pagelines" aria-hidden="true"></i></div>
              <h3 class="card-title text-center">{{$farmcount+$farmpendingcount}}</h3>
            <div  class="card-footer" style="text-align: center; ">
              <h5  style="color: #388E3C">Total Farmers Registered </h5>
            </div>
          </div>
        </div>
                <div class="card col-auto mr-3" style="background-color:white; color:#388E3C ; width:18rem ; margin-right:5px">
          <div class="card-body">
            <div style="font-size: 30px"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i></div>
              <h3 class="card-title text-center">{{$inspectioncount}}</h3>
            <div  class="card-footer" style="text-align: center; ">
              <h5  style="color: #388E3C">REPORT(S) SUBMITTED</h5>
            </div>
          </div>
        </div>
        <div class="card col-auto mr-3" style="background-color:white; color:#388E3C; width:18rem ; margin-right:5px">
          <div class="card-body">
            <div style="font-size: 30px"><i class="fa fa-check" aria-hidden="true"></i></div>
              <h3 class="card-title text-center">{{$inspectionapprovedcount}}</h3>
            <div  class="card-footer" style="text-align: center; ">
              <h5  style="color: #388E3C">REPORT(S) APPROVED</h5>
            </div>
          </div>
        </div>
        <div class="card col-auto mr-3" style="background-color:white; color:#388E3C; width:18rem ; margin-right:5px">
          <div class="card-body"><div style="font-size: 30px">
            <i class="fa fa-times" aria-hidden="true"></i></div>
              <h3 class="card-title text-center">{{$inspectionrejectedcount}}</h3>
            <div  class="card-footer" style="text-align: center; ">
              <h5  style="color: #388E3C">REPORT(S) REJECTED</h5>
            </div>
          </div>
        </div>
        <div class="card col-auto mr-3" style="background-color:white; color:#388E3C ; width:18rem ; margin-right:5px">
          <div class="card-body"><div style="font-size: 30px">
            <i class="fa fa-briefcase" aria-hidden="true"></i></div>
              <h3 class="card-title text-center">{{number_format($farmarea,2)}}  ha</h3>
            <div  class="card-footer" style="text-align: center; ">
              <h5  style="color: #388E3C">Total Acreage</h5>
            </div>
          </div>
        </div>
        
        <div class="card col-auto mr-3" style="background-color:white; color:#388E3C; width:18rem ; margin-right:5px">
          <div class="card-body"><div style="font-size: 30px">
            <i class="fa fa-balance-scale" aria-hidden="true"></i></div>
             @if (is_numeric($actualyield))
             <h3 class="card-title text-center">{{number_format($estyield,2)}} Kgs</h3>       
            @else
               <h3 class="card-title text-center">{{($estyield)}}</h3>
            @endif
            <div  class="card-footer" style="text-align: center; ">
              <h5  style="color: #388E3C">Est. Yield [ {{$datayear}}]</h5>
            </div>
          </div>
        </div>
          <div class="card col-auto mr-3" style="background-color:white; color:#388E3C ; width:18rem ; margin-right:5px">
          <div class="card-body"><div style="font-size: 30px">
            <i class="fa fa-check-circle" aria-hidden="true"></i></div>
            @if (is_numeric($actualyield))
             <h3 class="card-title text-center">{{number_format($actualyield,2)}} Kgs</h3>       
            @else
               <h3 class="card-title text-center">{{($actualyield)}}</h3>
            @endif
            <div  class="card-footer" style="text-align: center; ">
              <h5  style="color: #388E3C">Actual Yield [ {{$datayear}}]</h5>
            </div>
          </div>
        </div>
      </div>  <!-- CARD ROW-->
  </div>

  </div>
</x-layouts.app>
