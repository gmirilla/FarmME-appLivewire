const db = new Dexie("FarmEntrances");
db.version(1).stores({
  farms: "++id,farmcode,community,farmname,farmstate",
  forms: "++id,farmcode,farmname,community,crop,cropvariety,regdate,address,sync_status"
});

const selectedFarm = localStorage.getItem("selectedFarm");

if (selectedFarm) {
  db.farms.where("farmcode").equals(selectedFarm).toArray().then(results => {
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

const seasonSelect0 = document.getElementById("seasonrange0");
const seasonSelect1 = document.getElementById("seasonrange1");
const seasonSelect2 = document.getElementById("seasonrange2");
seasonSelect0.value=seasonrange[0];
seasonSelect1.value=seasonrange[1];
seasonSelect2.value=seasonrange[2];

const preseasonid1 = document.getElementById("prevseason_id1");
const preseasonid2 = document.getElementById("prevseason_id2");
preseasonid1.value=seasonrange[0];
preseasonid2.value=seasonrange[0];


// Handle form submission offline
document.getElementById("offline-form").addEventListener("submit", e => {
  e.preventDefault();
  const form = e.target;
  const data = {
    farmcode: form.farmcode.value,
    farmname: form.farmname?.value || "", // optional fallback
    community: form.community?.value || "",
    crop: form.crop?.value || "",
    cropvariety: form.cropvariety?.value || "",
    regdate: form.regdate?.value || "",
    address: form.address?.value || "",
    sync_status: "pending"
  };

  db.forms.add(data).then(() => {
    alert("Saved offline. Will sync when online.");
    form.reset();
  });
});

// Sync when online
window.addEventListener("online", () => {
  db.forms.where("sync_status").equals("pending").toArray().then(entries => {
    entries.forEach(entry => {
      fetch("/api/sync-onboarding", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(entry)
      }).then(res => {
        if (res.ok) {
          db.forms.update(entry.id, { sync_status: "synced" });
        }
      });
    });
  });
});