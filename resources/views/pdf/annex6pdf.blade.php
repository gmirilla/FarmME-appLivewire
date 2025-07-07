<style>
    th, td {
  border: 1px solid;
}
td{
    padding: 2px;
    textalign:justify;}
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 12px;
}
input[type="radio"] {
    
    font-size: 0.75rem;
}
.form-check {
    margin-bottom: 0.5rem;
}
</style>

<div class="card">
    <div class="card-header" style="text-align: center">
        <h2>ANNEX 6: CONTRACT FORM</h2>    
        <h3><b> B&R SPICES/ZLM SUSTAINABLE GINGER/TURMERIC PROJECT</b> <br>
        {{strtoupper($farmer->farmname)}}</h3>

    <h4>@if (!empty($farmentrance->farm_period))
         {{$farmentrance->farm_period}} Season 
    @endif</h4>
    </div>
    <div>Date: {{$farmentrance->regdate}} </div>
    <div class="card-body">
        <table>
            <tr>
                <td><b>Name of Field Operator: </b><br/>
                {{$farmer->farmname}}</td>
                <td>Farmer Code: <br/>{{$farmer->farmcode}}</td>
            </tr>
            <tr>
                <td><b>Community of Residence:</b></td>
                <td>{{$farmer->community}}</td>
            </tr>
            <tr>
                <td><b>Number of Plots:</b></td>
                <td>{{$farmer->nooffarmunits}}</td>
            </tr>
            <tr>
                <td><b>Name of the Crop:</b></td>
                <td>{{$farmer->crop}}  [ {{$farmer->cropvariety}} ]</td>
            </tr>
        </table>

        <div style="margin-top: 10px; margin-bottom: 10px; font-size:12px;" class="text-justify">
            <ol>
                <li>I am an active participant in the B&R SPICES Sustainable Ginger/Turmeric Project.</li>
<li>I declare that I will always work to make the farm(s) more fertile and productive.</li>
<li>I will also work to conserve biodiversity; ensure that soils, waterways and wildlife are protected and enrich the soil to prevent it from being damaged by bush burning, wind and water erosion.</li>
<li>I will take acceptable measures to manage all forms of waste generated on the farm and in the house.</li>
<li>I will not farm in forest reserves nor use burning for land preparation and introduce some minimal number of shade trees where possible.</li>
<li>I will refrain from the use of banned chemicals like for weed/disease management.</li>
<li>I will protect the rights and welfare of my family, workers and the community and work to ensure that they enjoy decent lives whiles I make sure that my children attend school and refrain from the use of children for any hazardous farming activity.</li>
<li>I declare that this farm plot(s) will be cultivated under best management practices of the standard of sustainable agriculture. I am aware that this standard is intended to maintain natural biodiversity and the health of the environment and its inhabitants as well as ensure lasting social benefits to my family, workers and the community.</li>
<li>I declare that I will apply integrated pest management to control pests and where appropriate, apply agrochemical in a rational and reducing manner. In doing so, I will protect my family by applying it responsibly and store it safely.</li>
<li>I will attend and be actively involved in meetings and training/extension and communal works and events organized by the IMS team.</li>
<li>I will allow inspections by persons authorized by B&R SPICES Livelihood Charter Ginger/turmeric Certification Project and give access to the fields, stores and documents, etc.</li>
<li>I will allow investigative audits to be conducted where necessary</li>
<li>I am aware and will comply with B&R SPICES Sustainability Project to support the entire project, provide support services to the farmer and offer advices for best management practices and co-ordinate the internal and the external management system and inspections.</li>
<li>I will notify B&R SPICES on introduction of any new variety into the UEBT/RA/MABA for GMO verification and I will further alert B&R SPICES of any program planting GMO materials by my farm.</li>
<li>I shall not resort to any form of fumigation during the drying and temporary storage in case of pest infestation.</li>
<li>I will grant access to drying and storage areas and documentation to auditor appointed by B&R SPICES</li>
<li>If non-conformities are detected in my operation, I commit to work with B&R SPICES to implement corrective actions.</li>
<li>I will notify B&R SPICES to conduct risk assessment on new plots before cultivation begins.</li>
<li>I will not be involved in double registration ( register with other company doing similar certification)</li>
<li>I will support all social control, self-assessment, peer reviews and all initiatives geared towards enriching the sustainability project and avoiding fraud.</li>
<li>I commit to ensuring that cutting and drying of produce is carried out in a hygienic way to achieve best quality ginger/turmeric for onward sales on the agreed price basis to B&R SPICES/ZLM Nigeria.</li>
<li>Note: It is not compulsory to sell your ginger/turmeric to B&R SPICES /ZLM Nig. Ltd and I am free to resign from the programme at any time.</li>
<li>The price of the certified Ginger/Turmeric will depend on the forces of demand and supply in the local market.</li>
<li>The company is not obliged to buy all certified materials produced by the farmer.</li>
<li>The company guarantees to pay a premium which will be determined by written agreement between the company and the registered farmers as represented by the community price committee chosen by the registered members.</li>
<li>I finally declare that this contract has been duly explained to me and I undertake that I do understand my responsibilities and duties to the project</li>
<li>Prior to possible exit from the business the company shall inform FO at least three (3) months before the start of the new cultivation season.</li>
<li>This contract is valid for three (3) years.</li>
            </ol>
        </div>
        <table>
            <tr><td>Date:</td><td>Date:</td></tr>
            <tr><td><b>Field operator’s name: </b><br/>{{$farmer->farmname}}</td><td>On behalf of B&R SPICES Sustainability Project: <br/>Name:</td></tr>
             <tr><td>Signature/Thumbprint: <br/></td><td>Signature: <br/></td></tr>
        </table>
    </div>
</div>