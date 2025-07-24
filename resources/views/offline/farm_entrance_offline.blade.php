<x-layouts.offline>
    <script src="https://cdn.jsdelivr.net/npm/dexie@3.2.2/dist/dexie.min.js"></script>
<script>
  const db = new Dexie("OfflineOnboarding");
  db.version(1).stores({
    forms: "++id,farmcode,sync_status"
  });
</script>

  <div class="card">
    <div class="card-header"><h4>Offline Farm Entrance Form</h4></div>
    <div class="card-body">
      <form id="offline-form">
        <input type="text" name="farmcode" placeholder="Farm Code" required />
        <input type="text" name="farmname" placeholder="Farm Name" required />
        <input type="text" name="community" placeholder="Community" />
        <input type="text" name="crop" placeholder="Crop" />
        <input type="text" name="cropvariety" placeholder="Crop Variety" />
        <input type="date" name="regdate" placeholder="Reg Date" />
        <textarea name="address" placeholder="Address"></textarea>
        <button type="submit">Save Offline</button>
      </form>
    </div>
  </div>
  <script>
    document.getElementById("offline-form").addEventListener("submit", e => {
  e.preventDefault();
  const form = e.target;
  const data = {
    farmcode: form.farmcode.value,
    farmname: form.farmname.value,
    community: form.community.value,
    crop: form.crop.value,
    cropvariety: form.cropvariety.value,
    regdate: form.regdate.value,
    address: form.address.value,
    sync_status: "pending"
  };
  db.forms.add(data).then(() => {
    alert("Saved offline. Will sync when online.");
    form.reset();
  });
});

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
    </script>
</x-layouts.offline>