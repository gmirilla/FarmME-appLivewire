<x-layouts.offline>
  <script src="https://cdn.jsdelivr.net/npm/dexie@3.2.2/dist/dexie.min.js"></script>

  <div class="card">
    <div class="card-header"><h4>Offline Inspection Sheet </h4></div>
    <div id="farm-detail"></div>
    <div class="card-body">
      <form id="offline-forminspection">
        <div id="inspectionsheet">
            <table>
                <tbody id="question-rows">
                </tbody>
            </table>
        </div>
      </form>
    </div>

  </div>
  <script src="/js/offline-handler.js"></script>
  <script src="/js/db.js"></script>
 <script>
    //Read stored reports from local DB
    db.reports.toArray().then(reports => {
     const report = document.getElementById("report");
    const tbody = document.getElementById("question-rows");
  let rowsHTML = "";
    inspectionreport= reports.find(report => report.reportname.includes(selectedMode)) ;// Find based on selected match

    //call Section Builder
if (inspectionreport) {
  tbody.innerHTML = "<tr><td colspan='3'>Loading...</td></tr>";
buildSection(inspectionreport.reportid).then(rowsHTML => {
  tbody.innerHTML = rowsHTML;
});

} else {
  tbody.innerHTML = "<tr><td colspan='3'>No matching report found</td></tr>";
}



    });

 </script>
 <script>
function buildSection(reportid) {
  let SectionHTML = "";

  return db.reportSections.toArray().then(allreportSections => {
    const matchingSections = allreportSections.filter(reportSection => reportSection.reportid === reportid);

    const htmlPromises = matchingSections.map(reportSection => {
      return buildQuestion(reportSection.sectionid);
    });

    return Promise.all(htmlPromises).then(sectionChunks => {
      SectionHTML = sectionChunks.join(""); // Combine all question HTML
      return SectionHTML;
    });
  });
}

   function buildQuestion(sectionid) {
  let qcounter = 0;
  let questionHTML = "";
  let farmcode=selectFarm;


  return db.reportQuestions.toArray().then(allSectionquestion => {
    allSectionquestion
      .filter(reportQuestion => reportQuestion.sectionid === sectionid)
      .forEach(reportQuestion => {
        switch (reportQuestion.questiontype) {
          case "TYPEA":
            questionHTML += `<tr><td>${qcounter}</td>
                <td>${reportQuestion.question}
                      <input type="text" name="farmcode" value="${farmcode}" hidden>
    <input type="text" name="inspectionsheetid" value="${inspectionsheetid}" hidden>
    <input type="text" name="questionid" value="${reportQuestion.questionid}" hidden>
                  </td>
                <td>
                    <div class="form-check">
                <input class="form-check-input" type="radio" id="yes" name="answers[${qcounter}]" value="1" required>
                <label class="form-check-label" for="yes">YES</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" id="no" name="answers[${qcounter}]" value="2">
                <label class="form-check-label" for="no">NO</label>
              </div></td><td>
                        <textarea class="form-control" name="comments[]" placeholder="Enter any Remarks/Justification"></textarea></td></tr>
            `;
            break;

          case "TYPEB":
            questionHTML += `<tr><td>${qcounter}</td>
                <td>${reportQuestion.question}
                      <input type="text" name="farmcode" value="${farmcode}" hidden>
    <input type="text" name="inspectionsheetid" value="${inspectionsheetid}" hidden>
    <input type="text" name="questionid" value="${reportQuestion.questionid}" hidden></td>
                <td>
              <div class="form-check">
                <input class="form-check-input" type="radio" id="poor" name="answers[${qcounter}]" value="1" required>
                <label class="form-check-label" for="poor">Poor</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" id="fair" name="answers[${qcounter}]" value="2">
                <label class="form-check-label" for="fair">Fair</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" id="good" name="answers[${qcounter}]" value="3">
                <label class="form-check-label" for="good">Good</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" id="vgood" name="answers[${qcounter}]" value="4">
                <label class="form-check-label" for="vgood">Very Good</label>
              </div></td><td>
                        <textarea class="form-control" name="comments[]" placeholder="Enter any Remarks/Justification"></textarea></td></tr>
            `;
            break;

          case "TYPEC":
            questionHTML += `<tr><td>${qcounter}</td>
                <td>${reportQuestion.question}
                      <input type="text" name="farmcode" value="${farmcode}" hidden>
    <input type="text" name="inspectionsheetid" value="${inspectionsheetid}" hidden>
    <input type="text" name="questionid" value="${reportQuestion.questionid}" hidden></td>
                <td>
              <div class="form-check">
                <input class="form-check-input" checked type="radio" id="commentonly" name="answers[${qcounter}]" value="1" required>
                <label class="form-check-label" for="commentonly">Comment Only</label>
              </div></td> <td>
                        <textarea class="form-control" name="comments[]" placeholder="Enter any Remarks/Justification"></textarea></td></tr>
            `;
            break;

          default:
            console.warn("Unknown question type:", reportQuestion.questiontype);
        }

        qcounter++; // Increment for the next question
      });

    // Optionally insert into the DOM here or return the HTML
    return questionHTML;
  });
}
 </script>
</x-layouts.offline>
