<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                <div class="card" style="background-color:green; color:white">
                    <i class="fa-solid fa-user"></i> 
                    <div class="card-body">
                        <h3 class="card-title">{{$usercount}}</h3>
                      <p class="card-title" style="padding: 16px 0px 16px 0px">Number of Users</p>
                      <a href="#" style="background-color:rgba(0,0,0,.1); padding:0px 0px 0px 0px">
                      <div  class="card-footer" style="text-align: center; ">
                        <h5  style="color: white">More info</h5>
                      </div>
                      </a>
                    </div>
                  </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
              <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
              <div class="card" style="background-color:green; color:white">
                  <i class="fa-solid fa-user"></i> 
                  <div class="card-body">
                      <h3 class="card-title">{{$farmpendingcount}} farm(s)</h3>
                    <p class="card-title" style="padding: 16px 0px 16px 0px">Onboarding Pending</p>
                    <a href="#" style="background-color:rgba(0,0,0,.1); padding:0px 0px 0px 0px">
                    <div  class="card-footer" style="text-align: center; ">
                      <h5  style="color: white">More info</h5>
                    </div>
                    </a>
                  </div>
                </div>
          </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                
                <div class="card" style="background-color:#17a2b8; color:white">
                    <i class="fa-solid fa-user"></i> 
                    <div class="card-body">
                        <h3 class="card-title">{{$farmcount}}</h3>
                      <p class="card-title" style="padding: 16px 0px 16px 0px">Number of Farms</p>
                        <a href="#" style="background-color:rgba(0,0,0,.1); padding:0px 0px 0px 0px">
                      <div class="card-footer" style="text-align: center; opacity: 0.5;">
                        <h5 style="color: white">More info</h5>
                      </div>
                      </a>
                    </div>
                  </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                
                <div class="card" style="background-color:#dc3545; color:white">
                    <i class="fa-solid fa-user"></i> 
                    <div class="card-body">
                        <h3 class="card-title">{{$inspectioncount}}</h3>
                      <p class="card-title" style="padding: 16px 0px 16px 0px">Inspections Awaiting Approval</p>
                        <a href="#" style="background-color:rgba(0,0,0,.1); padding:0px 0px 0px 0px">
                      <div class="card-footer" style="text-align: center; opacity: 0.5;">
                        <h5 style="color: white">More info</h5>
                      </div>
                      </a>
                    </div>
                  </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
          <div class="card" style="background-color:#dc3545; color:white">
            <i class="fa-solid fa-user"></i> 
            <div class="card-body">
                <h5 class="card-title">10 Approved    5 Rejected</h5>
              <p class="card-title" style="padding: 16px 0px 16px 0px">Inspections Completed</p>
                <a href="#" style="background-color:rgba(0,0,0,.1); padding:0px 0px 0px 0px">
              <div class="card-footer" style="text-align: center; opacity: 0.5;">
                <h5 style="color: white">More info</h5>
              </div>
              </a>
            </div>
          </div>
    </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
          <div class="card" style="background-color:#dc3545; color:white">
            <i class="fa-solid fa-user"></i> 
            <div class="card-body">
                <h3 class="card-title">400ha</h3>
              <p class="card-title" style="padding: 16px 0px 16px 0px">Total Acreage</p>
                <a href="#" style="background-color:rgba(0,0,0,.1); padding:0px 0px 0px 0px">
              <div class="card-footer" style="text-align: center; opacity: 0.5;">
                <h5 style="color: white">More info</h5>
              </div>
              </a>
            </div>
          </div>
    </div>
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
      <div class="card" style="background-color:#dc3545; color:white">
        <i class="fa-solid fa-user"></i> 
        <div class="card-body">
            <h3 class="card-title">45000Kg</h3>
          <p class="card-title" style="padding: 16px 0px 16px 0px">Estimated Total Yeild</p>
            <a href="#" style="background-color:rgba(0,0,0,.1); padding:0px 0px 0px 0px">
          <div class="card-footer" style="text-align: center; opacity: 0.5;">
            <h5 style="color: white">More info</h5>
          </div>
          </a>
        </div>
      </div>
</div>
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
