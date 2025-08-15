<x-layouts.offline>
  <script src="https://cdn.jsdelivr.net/npm/dexie@3.2.2/dist/dexie.min.js"></script>

  <div class="card">
    <div class="card-header"><h4>Offline Farm Entrance Form</h4></div>
    <div id="farm-details"></div>
    <div class="card-body">
      <form id="offline-formFE">
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
                    <input type="number" step="any" name="volsold[]"  class="form-control">
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
                    <input type="number" step="any" name="volsold[]" class="form-control">
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
                    <input type="number" step="any" name="volsold[]" class="form-control">
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
                            <td><input type="number" step=any name="cropdelivered[]" id="cropdelivered" class="form-control"></td>
                            <td>
                                <input type="text" hidden name='prevseason' >
                            </td>
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
                        <td><input type="text" name="herbicide[]" class="form-control" ></td>
                        <td><input type="text"  name="herbicideqty[]" class="form-control" ></td>
                        <td><input type="text"  name="herbicideapplier[]" class="form-control"></td>
                        <td><input type="number" step=any name="hectareapplied[]" class="form-control"></td>
                        <td></td>
                    </tr> 
                                          <tr>
                        <td><input type="text" name="herbicide[]" class="form-control" ></td>
                        <td><input type="text"  name="herbicideqty[]" class="form-control" ></td>
                        <td><input type="text"  name="herbicideapplier[]" class="form-control"></td>
                        <td><input type="number" step=any name="hectareapplied[]" class="form-control"></td>
                        <td></td>
                    </tr> 
                                          <tr>
                        <td><input type="text" name="herbicide[]" class="form-control" ></td>
                        <td><input type="text"  name="herbicideqty[]" class="form-control" ></td>
                        <td><input type="text"  name="herbicideapplier[]" class="form-control"></td>
                        <td><input type="number" step=any name="hectareapplied[]" class="form-control"></td>
                        <td></td>
                    </tr> 
                </tbody>
            </table>
    </div>
    </div>
    <div class="card my-3">
    <div class="card-body">
            <h5>E. OTHER CULTIVATED CROPS  </h5>
            <table class="table">
                <thead>
                    <th>PLOT NAME</th>
                    <th>CROP CULTIVATED</th>
                    <th>ESTIMATED HECTARES</th>
                    <th>LOCATION</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="otherplotname[]"    class="form-control"></td>
                        <td><input type="text" name="otherplotcrop[]"  class="form-control"></td>
                        <td><input type="text" name="otherplotarea[]" class="form-control"></td>
                        <td><input type="text" name="otherplotlocation[]" class="form-control"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="otherplotname[]"    class="form-control"></td>
                        <td><input type="text" name="otherplotcrop[]"  class="form-control"></td>
                        <td><input type="text" name="otherplotarea[]" class="form-control"></td>
                        <td><input type="text" name="otherplotlocation[]" class="form-control"></td>
                        <td></td>
                    </tr> 
                    <tr>
                        <td><input type="text" name="otherplotname[]"    class="form-control"></td>
                        <td><input type="text" name="otherplotcrop[]"  class="form-control"></td>
                        <td><input type="text" name="otherplotarea[]" class="form-control"></td>
                        <td><input type="text" name="otherplotlocation[]" class="form-control"></td>
                        <td></td>
                    </tr> 
                </tbody>
            </table>
    </div>
    </div>


        <button type="submit" class="btn btn-primary">Save Offline</button>
      </form>
    </div>
  </div>

  <script src="/js/offline-handler.js"></script>
  <script>
    document.getElementById("offline-formFE").addEventListener("submit", async function (e) {
  e.preventDefault();

  const formData = new FormData(e.target);
  const farmcode = formData.get("farmcode") || formData.getAll("farmcode[]")[0]; // fallback

  // Save base farm entry (can expand this)
  await db.farms.put({ farmcode });

  //  Volumes delivered
  const volumes = formData.getAll("volsold[]");
  ["seasonrange0", "seasonrange1", "seasonrange2"].forEach((id, i) => {
    const season = document.getElementById(id).value;
    const volume = volumes[i];
    if (volume) {
      db.volumes.put({ farmcode, season, volume: parseFloat(volume) });
    }
  });

  //Agrochemicals
  const herbicideNames = formData.getAll("herbicide[]");
  const herbicideQtys = formData.getAll("herbicideqty[]");
  const appliers = formData.getAll("herbicideapplier[]");
  const hectares = formData.getAll("hectareapplied[]");

  herbicideNames.forEach((name, i) => {
    if (name) {
      db.agrochemicals.put({
        farmcode,
        herbicide: name,
        quantity: herbicideQtys[i],
        applier: appliers[i],
        hectare: parseFloat(hectares[i])
      });
    }
  });

  // ther Crops
  const plotNames = formData.getAll("otherplotname[]");
  const crops = formData.getAll("otherplotcrop[]");
  const areas = formData.getAll("otherplotarea[]");
  const locations = formData.getAll("otherplotlocation[]");

  plotNames.forEach((name, i) => {
    if (name) {
      db.otherCrops.put({
        farmcode,
        plotName: name,
        crop: crops[i],
        area: areas[i],
        location: locations[i]
      });
    }
  });

  alert("Farm data saved offline successfully 🌍💾");
});
  </script>
</x-layouts.offline>