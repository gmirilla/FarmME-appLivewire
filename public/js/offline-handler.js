
const selectedFarm = localStorage.getItem("selectedFarm");

if (selectedFarm) {
  window.db.farms.where("farmcode").equals(selectedFarm).toArray().then(results => {
    const farm = results[0];
    document.getElementById("farm-details").innerHTML = `
      <h4>${farm.farmname}</h4>
      <p>Community: ${farm.community}</p>
      <p>Farm Code: ${farm.farmcode}</p>
      <p>State: ${farm.farmstate}</p>
    `;
    document.getElementById("farmcode-hidden").value = farm.farmcode;
  });
}

// Generate season range
const currentYear = new Date().getFullYear();
const seasonrange = Array.from({ length: 3 }, (_, i) => `${currentYear - i - 1}/${currentYear - i}`);

//const seasonSelect0 = document.getElementById("seasonrange0");
//const seasonSelect1 = document.getElementById("seasonrange1");
//const seasonSelect2 = document.getElementById("seasonrange2");
//seasonSelect0.value=seasonrange[0];
//seasonSelect1.value=seasonrange[1];
//seasonSelect2.value=seasonrange[2];

//const preseasonid1 = document.getElementById("prevseason_id1");
//const preseasonid2 = document.getElementById("prevseason_id2");
//preseasonid1.value=seasonrange[0];
//preseasonid2.value=seasonrange[0];


// Handle form submission offline FArm Entrance
document.getElementById("offline-formFE").addEventListener("submit", async function (e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  const farmcode = formData.get("farmcode") || formData.getAll("farmcode[]")[0];

  // 🔎 Check if this farm already has a saved form
  const exists = await window.db.forms.where("farmcode").equals(farmcode).count();
  if (exists > 0) {
    alert("A form for this farm already exists offline.");
    return;
  }

  // 📝 Save form header (Section A)
  const header = {
    farmcode,
    sync_status: "pending"
  };
  await window.db.forms.add(header);

  // 🌽 Section B – Volumes Sold
  const volumes = formData.getAll("volsold[]");
  ["seasonrange0", "seasonrange1", "seasonrange2"].forEach((id, i) => {
    const season = document.getElementById(id).value;
    const volume = volumes[i];
    if (volume) {
      window.db.volumes.put({ farmcode, season, volume: parseFloat(volume) });
    }
  });

  // 📦 Section C – Crop Delivery & Production
  const cropDelivered = formData.getAll("cropdelivered[]");
  const cropProduced = formData.getAll("cropproduced");
  const prevSeason = document.getElementById("prevseason_id1").value;

  if (cropDelivered.length > 0) {
    window.db.volumes.put({
      farmcode,
      season: prevSeason,
      volume: parseFloat(cropDelivered[0])
    });
  }

  if (cropProduced.length > 0) {
    window.db.volumes.put({
      farmcode,
      season: prevSeason,
      volume: parseFloat(cropProduced[0]) // You could store separately if needed
    });
  }

  // 🧪 Section D – Agrochemicals
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

  // 🌾 Section E – Other Cultivated Crops
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

  alert("Data saved offline successfully! 💾 Will sync when online. Proceeding to Questionnaire");
  localStorage.setItem("selectedfarm", farmcode);
  localStorage.setItem("selectedmode", "Entrance");
  window.location.href = "/offline-inspection";
});

// Sync when online
window.addEventListener("online", () => {
  window.db.forms.where("sync_status").equals("pending").toArray().then(entries => {
    entries.forEach(entry => {
      fetch("/api/sync-onboarding", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(entry)
      }).then(res => {
        if (res.ok) {
          window.db.forms.update(entry.id, { sync_status: "synced" });
        }
      });
    });
  });
});
