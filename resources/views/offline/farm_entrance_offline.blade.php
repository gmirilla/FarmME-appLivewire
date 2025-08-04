<x-layouts.offline>
  <script src="https://cdn.jsdelivr.net/npm/dexie@3.2.2/dist/dexie.min.js"></script>

  <div class="card">
    <div class="card-header"><h4>Offline Farm Entrance Form</h4></div>
    <div class="" id="farm-details">
        
    </div>
    <div id="farm-details"></div>
    <div class="card-body">
      <form id="offline-formFE">
        <div id="sectiona">
                        <div class="card-body">
            <h5>A. FIELD OPERATOR BIO-DATA</h5>
            <div class="row my-3 gx-5">
                <div class="col-3 p-3">
                    <label for="surname" class="form-label">Farmer name</label>
                    <input type="text" disabled class="form-control" name="farmname">
                </div>
                <div class="col-3 p-3">
                    <label for="farmcode" class="form-label">Farmer Code</label>
                    <input type="text" name="farmcode" disabled class="form-control" value=>
                </div>
                <div class="col-3 p-3">
                    <label for="yearofbirth" class="form-label">Year of Birth</label>
                     <input type="number" value="" class="form-control" name=yob/>  
                </div>
                <div class="col-3 p-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address"  id="address" class="form-control" ></textarea>
                </div>
               
                <div class="col-3 p-3">
                    <label for="dateoflastinspection" class="form-label">Date of Last Inspection</label>
                        <input type="text" name="dateoflastinspection" id="dateoflastinspection" class="form-control" value=""/>
                        </div>
                <div class="col-3 p-3">
                    <label for="dateoflastinspection" class="form-label">Outcome of Last Inspection</label>
                    <input type="text"  name="outcomeoflastinspection" id="dateoflastinspection" class="form-control" value=""/>
                </div>
                <div class="col-3 p-3">
                    <label for="sourceofcrop" class="form-label">Source of Crop</label>
                    <input type="text"  name="sourceofcrop" id="sourceofcrop" class="form-control" value=""/>
                </div> 
                 <div class="col-3 p-3">
                    <label for="signature" class="form-label">Farmers Signature</label>
                    <input type="file" name="signature" accept="image/*" class="form-control">                  
                </div>
                <div class="col-3 p-3">
                    <label for="signature" class="form-label">Farmer's Picture</label>
                    <input type="file" name="farmerpicture" accept="image/*" class="form-control">                  
                </div>
                          
            </div>
        </div>

        </div>
        <div id="sectionb">
             <div class="card my-3">
    <div class="card-body responsive">
            <h5>B. Historical Production: Hibiscus Production</h5><span style="font-size:0.8rem;">Note: Maize (M), Sorghum (S), Cowpea (C), Millet (ML), Sesame (Se), Groundnut (Gr)</span>
            <table class="table">
                <thead>
                    <th>Year</th>
                    <th>Farm Size (Ha)</th>
                    <th>Harvest Volume (BAGS)</th>
                    <th>Intercrop <br/>Crop</th>
                    <th>Intercrop <br/>System</th>
                    <th>Intra-row Spacing (m)</th>
                </thead>
                <tbody>
                     <tr>
                        <td><input type="text" name="hpseason[]"    class="form-control"></td>
                        <td><input type="text" name="hpfarmsize[]"    class="form-control"></td>
                        <td><input type="text" name="hpharvestvol[]"    class="form-control"></td>
                        <td><input type="text" name="hpcrop[]"    class="form-control"></td>
                        <td><input type="text" name="hpsystem[]"    class="form-control"></td>
                        <td><input type="text" name="hpspacing[]"    class="form-control"></td>
                     </tr>
                     <tr>
                        <td><input type="text" name="hpseason[]"    class="form-control"></td>
                        <td><input type="text" name="hpfarmsize[]"    class="form-control"></td>
                        <td><input type="text" name="hpharvestvol[]"    class="form-control"></td>
                        <td><input type="text" name="hpcrop[]"    class="form-control"></td>
                        <td><input type="text" name="hpsystem[]"    class="form-control"></td>
                        <td><input type="text" name="hpspacing[]"    class="form-control"></td>
                     </tr>
                     <tr>
                        <td><input type="text" name="hpseason[]"    class="form-control"></td>
                        <td><input type="text" name="hpfarmsize[]"    class="form-control"></td>
                        <td><input type="text" name="hpharvestvol[]"    class="form-control"></td>
                        <td><input type="text" name="hpcrop[]"    class="form-control"></td>
                        <td><input type="text" name="hpsystem[]"    class="form-control"></td>
                        <td><input type="text" name="hpspacing[]"    class="form-control"></td>
                     </tr>
                </tbody>
            </table>

        </div>
        </div>
        </div>
        <div id="sectionc">
        <div class="card my-3">
            <div class="card-body">
            <h5>C. Historical Production: Agrochemicals Used : </h5>
            <table class="table">
                <thead>
                    <th>Farm Size (Ha)</th>
                    <th>Products<br>(Fertilizers, Pesticides, Herbicides)
                    </th>
                    <th>QUANTITY APPLIED (LITER'S/BAGS)</th>
                    <th>NAME OF PERSON WHO APPLIED</th>
                    <th>PPE Used (Y/N)</th>
                    <th></th>
                </thead>
                <tbody>
                        <tr>
                        <td><input type="text"  name="agrochemfarmsize[]" class="form-control" ></td>
                        <td><input type="text" name="agrochemherbicide[]" class="form-control"></td>
                        <td><input type="text"   name="agrochemherbicideqty[]" class="form-control"></td>
                        <td><input type="text" name="agrochemherbicideapplier[]" class="form-control"></td>
                        <td><input type="checkbox" name="agrochemppeused[]" class="form-check-input" >
                        </td>
                    </tr> 
                    <tr>
                        <td><input type="text"  name="agrochemfarmsize[]" class="form-control" ></td>
                        <td><input type="text" name="agrochemherbicide[]" class="form-control"></td>
                        <td><input type="text"   name="agrochemherbicideqty[]" class="form-control"></td>
                        <td><input type="text" name="agrochemherbicideapplier[]" class="form-control"></td>
                        <td><input type="checkbox" name="agrochemppeused[]" class="form-check-input" >
                        </td>
                    </tr> 
                    <tr>
                        <td><input type="text"  name="agrochemfarmsize[]" class="form-control" ></td>
                        <td><input type="text" name="agrochemherbicide[]" class="form-control"></td>
                        <td><input type="text"   name="agrochemherbicideqty[]" class="form-control"></td>
                        <td><input type="text" name="agrochemherbicideapplier[]" class="form-control"></td>
                        <td><input type="checkbox" name="agrochemppeused[]" class="form-check-input" >
                        </td>
                    </tr> 
                    <tr>
                        <td><input type="text"  name="agrochemfarmsize[]" class="form-control" ></td>
                        <td><input type="text" name="agrochemherbicide[]" class="form-control"></td>
                        <td><input type="text"   name="agrochemherbicideqty[]" class="form-control"></td>
                        <td><input type="text" name="agrochemherbicideapplier[]" class="form-control"></td>
                        <td><input type="checkbox" name="agrochemppeused[]" class="form-check-input" >
                        </td>
                    </tr> 

                </tbody>
            </table>
    </div>
    </div>

        </div>

        <div id="sectiond">

        </div>


    
   
    
        <button type="submit" class="btn btn-primary">Save Offline</button>
      </form>
    </div>
  </div>

  <script src="/js/offline-handler.js"></script>
  <script src="/js/db.js"></script>
  <script>
    document.getElementById("offline-formFE").addEventListener("submit", async function (e) {
  e.preventDefault();

  const formData = new FormData(e.target);
  const farmcode = formData.get("farmcode") || formData.getAll("farmcode[]")[0]; // fallback

  // Save base farm entry (can expand this)
  await window.db.farms.put({ farmcode });

  //  Volumes delivered
  const volumes = formData.getAll("volsold[]");
  ["seasonrange0", "seasonrange1", "seasonrange2"].forEach((id, i) => {
    const season = document.getElementById(id).value;
    const volume = volumes[i];
    if (volume) {
      window.db.volumes.put({ farmcode, season, volume: parseFloat(volume) });
    }
  });

  //Agrochemicals
  const herbicideNames = formData.getAll("herbicide[]");
  const herbicideQtys = formData.getAll("herbicideqty[]");
  const appliers = formData.getAll("herbicideapplier[]");
  const hectares = formData.getAll("hectareapplied[]");

  herbicideNames.forEach((name, i) => {
    if (name) {
      window.db.agrochemicals.put({
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
      window.db.otherCrops.put({
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
  <script>
    console.log(selectedFarm);
window.db.farms.where("farmcode").equals(selectedFarm).toArray().then(results => {
  if (results.length > 0) {
    const farm = results[0]; // define farm before use
    document.querySelector('[name="farmcode"]').value = farm.farmcode || "";
    document.querySelector('[name="farmname"]').value = farm.farmname || "";
  } else {
    console.warn("No farm data found for:", selectedFarm);
  }
}).catch(err => {
  console.error("Failed to load farm data", err);
});



    

  </script>

</x-layouts.offline>