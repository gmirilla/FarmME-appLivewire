<x-layouts.offline>
  <script src="https://cdn.jsdelivr.net/npm/dexie@3.2.2/dist/dexie.min.js"></script>

  <div class="card">
    <div class="card-header"><h4>Offline Farm Entrance Form</h4></div>
    <div id="farm-details"></div>
    <div class="card-body">
      <form id="offline-form">
        <!-- Section A -->
        <div class="card my-3">
          <div class="card-body">
            <h5>A. PRODUCTION HISTORY</h5>
            NOT AVAILABLE OFFLINE
          </div>
        </div>

        <!-- Section B -->
        <div class="card my-3">
          <div class="card-body">
            <h5>B. VOLUME OF CERTIFIED CROPS SOLD/DELIVERED TO THE GROUP IN PREVIOUS YEARS (KGS)</h5>
            <table class="table">
              <thead>
                <tr>
                  <th>SEASON</th>
                  <th>VOLUME SOLD/DELIVERED (KGS)</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <input name="volseason" id="seasonrange0"  disabled class="form-control">
                  </td>
                  <td>
                    <input type="number" step="any" name="volsold"  class="form-control">
                  </td>
                  <td>
                    <input type="text" name="farmcode" hidden class="form-control" id="farmcode-hidden">
                  </td>
                </tr>
                 <tr>
                  <td>
                    <input name="volseason" id="seasonrange1"  disabled class="form-control">
                  </td>
                  <td>
                    <input type="number" step="any" name="volsold" class="form-control">
                  </td>
                  <td>
                    <input type="text" name="farmcode" hidden class="form-control" id="farmcode-hidden">
                  </td>
                </tr>
                 <tr>
                  <td>
                    <input name="volseason" id="seasonrange2" disabled class="form-control">
                  </td>
                  <td>
                    <input type="number" step="any" name="volsold" class="form-control">
                  </td>
                  <td>
                    <input type="text" name="farmcode" hidden class="form-control" id="farmcode-hidden">
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card my-3">
    <div class="card-body">
            <h5>C. </h5>
            <table class="table">
                <thead>
                    <th>SEASON</th>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY IN KGS</th>
                    <th></th>
                </thead>
                <tbody>

                    <tr>
                        <td><input type="text" id="prevseason_id1" disabled class="form-control"></td>
                            <td>Previous year’s </b> harvest of certified crop delivered to the group</td>
                            <td><input type="number" step=any name="cropdelivered" id="cropdelivered" class="form-control"></td>
                            <td>
                                <input type="text" hidden name='prevseason' >
                            </td>
                            </form>

                    </tr>
                    <tr>
                        <td><input type="text" id="prevseason_id2" disabled class="form-control"></td>
                            <td>Previous year’s estimated total production </td>
                            <td><input type="number" step=any name="cropproduced" class="form-control"></td>
                                  <td>

                            </td>
                    </tr>

                </tbody>
            </table>
    </div>
    </div>
    <div class="card my-3">
    <div class="card-body">
            <h5>D. AGROCHEMICALS USED ON THE FARM LAND  </h5>
            <table class="table">
                <thead>
                    <th>NAME  OF HERBICIDE & FERTILIZERS USED</th>
                    <th>QUANTITY APPLIED (LITER'S/BAGS)</th>
                    <th>NAME OF PERSON WHO APPLIED</th>
                    <th>Ha OF GINGER APPLIED ON</th>
                    <th></th>
                </thead>
                <tbody>

                      <tr>
                        <td><input type="text" name="herbicide" class="form-control" ></td>
                        <td><input type="text"  name="herbicideqty" class="form-control" ></td>
                        <td><input type="text"  name="herbicideapplier" class="form-control"></td>
                        <td><input type="number" step=any name="hectareapplied" class="form-control"></td>
                        <td></td>
                    </tr> 
                    <tr>
                        <td><input type="text" name="herbicide" class="form-control" ></td>
                        <td><input type="text"  name="herbicideqty" class="form-control" ></td>
                        <td><input type="text"  name="herbicideapplier" class="form-control"></td>
                        <td><input type="number" step=any name="hectareapplied" class="form-control"></td>
                        <td></td>
                    </tr>
                                          <tr>
                        <td><input type="text" name="herbicide" class="form-control" ></td>
                        <td><input type="text"  name="herbicideqty" class="form-control" ></td>
                        <td><input type="text"  name="herbicideapplier" class="form-control"></td>
                        <td><input type="number" step=any name="hectareapplied" class="form-control"></td>
                        <td></td>
                    </tr>  

                    <tr>
                        <td><input type="text" required name="herbicide" class="form-control" placeholder="Enter name of Chemical.."></td>
                        <td><input type="text" required name="herbicideqty" class="form-control"></td>
                        <td><input type="text" required name="herbicideapplier" class="form-control"></td>
                        <td><input type="text" required name="hectareapplied" class="form-control"></td>
                        <td>
                            <input type="text" name="farmcode" hidden class="form-control" value="{{$farmerdetail->farmcode}}">
                            <input type="text" name="farmid" hidden class="form-control" value="{{$farmerdetail->id}}">
                            <input type="text" name="farmentrance" hidden class="form-control" value="{{$farmentrance->id}}">
                            <input type="text" name="season" hidden class="form-control" value="{{$currentseason}}">
                            <button type="submit" name="addherbicide" class="btn btn-primary">ADD</a></td>
                            </form>
                    </tr>
                </tbody>

        <button type="submit">Save Offline</button>
      </form>
    </div>
  </div>

  <script src="/js/offline-handler.js"></script>
</x-layouts.offline>