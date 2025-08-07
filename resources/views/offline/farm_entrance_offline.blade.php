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
                    <label for="farmname" class="form-label">Farmer name</label>
                    <input type="text" disabled class="form-control" name="farmname">
                </div>
                <div class="col-3 p-3">
                    <label for="farmcode" class="form-label">Farmer Code</label>
                    <input type="text" name="farmcode" disabled class="form-control">
                </div>
                <div class="col-3 p-3">
                    <label for="yob" class="form-label">Year of Birth</label>
                     <input type="number" value="" class="form-control" name="yob"/>  
                </div>
                <div class="col-3 p-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address"  id="address" class="form-control" ></textarea>
                </div>
               
                <div class="col-3 p-3">
                    <label for="dateoflastinspection" class="form-label">Date of Last Inspection</label>
                        <input type="date" name="dateoflastinspection" id="dateoflastinspection" class="form-control" value=""/>
                        </div>
                <div class="col-3 p-3">
                    <label for="lastinspectionresult" class="form-label">Outcome of Last Inspection</label>
                    <input type="text"  name="lastinspectionresult" id="lastinspectionresult" class="form-control" value=""/>
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
            <table class="table" id="hpprod">
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
                        <td><input type="text" name="hpseason[0]"    class="form-control"></td>
                        <td><input type="text" name="hpfarmsize[]"    class="form-control"></td>
                        <td><input type="text" name="hpharvestvol[]"    class="form-control"></td>
                        <td><input type="text" name="hpcrop[]"    class="form-control"></td>
                        <td><input type="text" name="hpsystem[]"    class="form-control"></td>
                        <td><input type="text" name="hpspacing[]"    class="form-control"></td>
                     </tr>
                     <tr>
                        <td><input type="text" name="hpseason[1]"    class="form-control"></td>
                        <td><input type="text" name="hpfarmsize[]"    class="form-control"></td>
                        <td><input type="text" name="hpharvestvol[]"    class="form-control"></td>
                        <td><input type="text" name="hpcrop[]"    class="form-control"></td>
                        <td><input type="text" name="hpsystem[]"    class="form-control"></td>
                        <td><input type="text" name="hpspacing[]"    class="form-control"></td>
                     </tr>
                     <tr>
                        <td><input type="text" name="hpseason[2]"    class="form-control"></td>
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
            <table class="table" id="agrochems">
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
              <div class="card my-3">
    <div class="card-body">
            <h5>D. Current Production</h5><span style="font-size:0.8rem;">Note: Maize (M), Sorghum (S), Cowpea (C), Millet (ML), Sesame (Se), Groundnut (Gr)</span>
            <table class="table currentprod">
                <thead>
                    <th>Plot</th>
                    <th>Plot Size (Ha)</th>
                    <th>Yield Est (Bags)</th>
                    <th>Crop</th>
                    <th>System</th>
                    <th>Intra-row Spacing (m)</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Map Upload</th>
                </thead>
                <tbody><!--TO DO-->
                    <tr>
                        <td><input type="text"  name="cpplotname[]" class="form-control" ></td>
                        <td><input type="text"  name="cpplotsize[]" class="form-control" ></td>
                        <td><input type="text"  name="cpyield[]" class="form-control" ></td>
                        <td><input type="text"  name="cpcrop[]" class="form-control" ></td>
                        <td><input type="text"  name="cpsystem[]" class="form-control" ></td>
                        <td><input type="text"  name="cpspacing[]" class="form-control" ></td>
                        <td><input type="text"  name="cplatitude[]" class="form-control" ></td>
                        <td><input type="text"  name="cplongitude[]" class="form-control" ></td>
                        <td><input type="file" accept="image/*" name="cpplotimage[]" class="form-control"></td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        </div>
        </div>
        <button type="button" class="btn btn-primary" name="save" id="save">Save Offline</button>
      </form>
    </div>
  </div>

  <script src="/js/offline-handler.js"></script>
  <script src="/js/db.js"></script>
  <script>

  </script>
  <script>
    document.querySelectorAll('input[type="file"]').forEach(input => {
  input.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (!file) return;

    //  Validate file type (e.g., only images)
    const allowedTypes = ['image/jpeg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
      alert('Please upload a valid image file (JPEG or PNG).');
      event.target.value = ''; // Clear input
      return;
    }

    // Validate file size (e.g., max 2MB)
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    if (file.size > maxSize) {
      alert('File size must be less than 2MB.');
      event.target.value = ''; // Clear input
    }
  });
});

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
const currentYear = new Date().getFullYear();
const seasonrange = Array.from({ length: 3 }, (_, i) => `${currentYear - i - 1}/${currentYear - i}`);
  console.log("seasonrange");
  console.log("Current Year ",currentYear );
  console.log(seasonrange);
   document.querySelector('[name="hpseason[0]"]').value = seasonrange[0] || "";
   document.querySelector('[name="hpseason[1]"]').value = seasonrange[1] || "";
   document.querySelector('[name="hpseason[2]"]').value = seasonrange[2] || "";
  </script>
  <script>
    document.getElementById("save").addEventListener("click", async function () {
  // Grab text fields
  const farmcode = document.querySelector('[name="farmcode"]').value;
  const farmname = document.querySelector('[name="farmname"]').value;
  const yob = document.querySelector('[name="yob"]').value;
  const address = document.querySelector('[name="address"]').value;
  const lastInspection = document.querySelector('[name="dateoflastinspection"]').value;
  const lastInspectionResult = document.querySelector('[name="lastinspectionresult"]').value;
  const sourceofCrop = document.querySelector('[name="sourceofcrop"]').value;

  // File inputs
  const signatureInput = document.querySelector('[name="signature"]');
  const pictureInput = document.querySelector('[name="farmerpicture"]');
  const plotpictureInput =document.querySelector('[name="cpimage"]');

  // Validate and convert files
  const allowedTypes = ['image/jpeg', 'image/png'];
  const maxSize = 2 * 1024 * 1024; // 2MB

  const validateFile = (file) => {
    return file && allowedTypes.includes(file.type) && file.size <= maxSize;
  };

  const readFileAsBlob = (file) =>
    new Promise((resolve) => {
      const reader = new FileReader();
      reader.onload = () => {
        resolve(new Blob([reader.result], { type: file.type }));
      };
      reader.readAsArrayBuffer(file);
    });

  let signatureBlob = null;
  let pictureBlob = null;

  if (validateFile(signatureInput.files[0])) {
    signatureBlob = await readFileAsBlob(signatureInput.files[0]);
  }

  if (validateFile(pictureInput.files[0])) {
    pictureBlob = await readFileAsBlob(pictureInput.files[0]);
  }

  // Save to Dexie Farmentance
  await window.db.farmentrance.put({
    farmcode,
    yob,
    lastinspection: lastInspection,
    lastinspectionresult: lastInspectionResult,
    address,
    sourceofcrop: sourceofCrop,
    signature: signatureBlob,
    farmerpicture: pictureBlob
  }).then(() => {
  console.log("✅ Save complete");
}).catch(err => {
  console.error("❌ Save failed:", err);
});


//Section B HPPROD
const rows = document.querySelectorAll("#hpprod tbody tr");
const hibiscusRecords = [];

rows.forEach(row => {
  const inputs = row.querySelectorAll("input");
  hibiscusRecords.push({
    season: inputs[0]?.value.trim(),
    hpfarmsize: inputs[1]?.value.trim(),
    hpharvest: inputs[2]?.value.trim(),
    hpcrop: inputs[3]?.value.trim(),
    hpsystem: inputs[4]?.value.trim(),
    hpspacing: inputs[5]?.value.trim()
  });
});
// Then save each record to IndexedDB
for (const [index, record] of hibiscusRecords.entries()) {
  await window.db.hphibiscusprod.put({
    id: `${farmcode}-${index}`, // unique ID
    farmcode,
    ...record
  });
}
;

//Section C AGROCHEMS
const agrorows = document.querySelectorAll("#agrochems tbody tr");
const agrochemRecords = [];

agrorows.forEach(row => {
  const inputs = row.querySelectorAll("input");
  agrochemRecords.push({
    farmsize: inputs[0]?.value.trim(),
    herbicide: inputs[1]?.value.trim(),
    quantity: inputs[2]?.value.trim(),
    applier: inputs[3]?.value.trim(),
    ppeused: inputs[4]?.value.trim()
  });
});
// Then save each record to IndexedDB
for (const [index, record] of agrochemRecords.entries()) {
  await window.db.agrochemicals.put({
    farmcode,
    season: currentYear,
    ...record
  });
}
;

//SECTION D CURRENT PRODUCTION
const cprows = document.querySelectorAll("#currentprod tbody tr");
const cpRecords = [];

cprows.forEach(row => {
  const cpinputs = row.querySelectorAll("input");
  cpRecords.push({
    cpplotname: cpinputs[0]?.value.trim(),
    cpplotsize: cpinputs[1]?.value.trim(),
    cpyield: cpinputs[2]?.value.trim(),
    cpcrop: cpinputs[3]?.value.trim(),
    cpsystem: cpinputs[4]?.value.trim(),
    cpspacing: cpinputs[5]?.value.trim(),
    cplat: cpinputs[6]?.value.trim(),
    cplong: cpinputs[7]?.value.trim()
  });
});
// Then save each record to IndexedDB
for (const [index, record] of cpRecords.entries()) {
  let cpimageBlob=null;
if (plotpictureInput?.files[index] && validateFile(plotpictureInput.files[index])) {
  cpimageBlob = await readFileAsBlob(plotpictureInput.files[index]);
}


  await window.db.farmplots.put({
    farmcode,
    season: currentYear, cpplotimage: cpimageBlob,
    ...record
  });
}
;
window.location.href = "/offline-inspection";
});

  </script>
</x-layouts.offline>