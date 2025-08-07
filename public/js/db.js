if (!window.db) {
window.db = new Dexie("FarmEntranceskano");
window.db.version(10).stores({
  // Basic Farm Info
  farms: "farmcode,community,farmname,farmstate,inspectorid",

  farmentrance: "farmcode,yob,lastinspection,lastinspectionresult,address,sourceofcrop,signature,farmerpicture",

  // HIBISCUS PRODUCTION Use (Section B)
  hphibiscusprod:"id,farmcode,season,hpfarmsize,hpharvest, hpcrop,hpsystem,hpspacing",

  // Agrochemical Use (Section C)
  agrochemicals: "++id,farmcode,herbicide,quantity,applier,hectare,season,ppeused",

  //Farm Plot Current Production (Section D)
  farmplots: "++id,farmcode,season,cpplotsize,cpyield,cpcrop,cpsystem,cpspacing,cplat,cplong,cparea,cpplotimage,cpplotname",

  //Main Form Submission
  forms: "++id,farmcode,farmname,community,crop,cropvariety,regdate,address,sync_status",


  // Report Details
  reports: "reportid,reportname,reportstate", 

  // Report Sections
  reportSections: "++id,sectionid,reportid,sectionname,sectionseq,sectionstate",

  // Report Questions
  reportQuestions: "questionid,sectionid,reportid,questionseq,question,questiontype,questionstate",

  // Inspection Sheet Header
  inspectionSheet: "++id,farmcode,reportid,inspectorid,season",

  // Inspection Sheet Answers
  inspectionAnswers: "++id,farmcode,inspectionsheetid,questionid,answer,comment"
});

  window.db = db;
}

