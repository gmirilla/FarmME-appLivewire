
const selectedFarm = localStorage.getItem("selectedFarm");

function handleFileUploads(formSelector, callback) {
  const form = document.querySelector(formSelector);
  const fileInputs = form.querySelectorAll('input[type="file"]');
  const filesData = {};

  // Helper to convert file to base64
  function readFileAsBase64(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = () => resolve(reader.result); // base64 string
      reader.onerror = reject;
      reader.readAsDataURL(file);
    });
  }

  // Read all files
  Promise.all(
    Array.from(fileInputs).map(async (input) => {
      const file = input.files[0];
      if (file) {
        const fieldName = input.name;
        const base64 = await readFileAsBase64(file);
        filesData[fieldName] = base64;
      }
    })
  )
    .then(() => {
      callback(filesData); // pass object with field names and base64 values
    })
    .catch((err) => {
      console.error("Error reading files:", err);
      alert("Something went wrong while reading file uploads.");
    });
}
