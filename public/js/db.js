if (!window.db) {
window.db = new Dexie("FarmEntranceskano");
window.db.version(5).stores({
  // Basic Farm Info
  farms: "farmcode,community,farmname,farmstate,inspectorid",

  //Main Form Submission
  forms: "++id,farmcode,farmname,community,crop,cropvariety,regdate,address,sync_status",

  //Volumes Sold (Section B)
  volumes: "++id,farmcode,season,volume", 

  // Agrochemical Use (Section D)
  agrochemicals: "++id,farmcode,herbicide,quantity,applier,hectare,season",

  // Other Cultivated Crops (Section E)
  otherCrops: "++id,farmcode,plotName,crop,area,location,season",

  // Report Details
  reports: "reportid,reportname,reportstate", 

  // Report Sections
  reportSections: "sectionid,reportid,sectionname,sectionseq,sectionstate",

  // Report Questions
  reportQuestions: "questionid,sectionid,reportid,questionseq,question,questiontype,questionstate",

  // Inspection Sheet Header
  inspectionSheet: "++id,farmcode,reportid,inspectorid,season",

  // Inspection Sheet Answers
  inspectionAnswers: "++id,farmcode,inspectionsheetid,questionid,answer,comment"
});

  window.db = db;
}

