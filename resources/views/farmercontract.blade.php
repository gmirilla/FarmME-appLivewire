<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
<x-layouts.app>
    
    
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
        <div class="mx-5">
        <table class="table table-sm table-striped table-bordered">
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
        </div>
        <div  class="text-justify mx-5" style="font-size: 1.2rem; line-height:1.5rem" >
            <ol class="list-group list-group-numbered">
                <li class="list-group-item">I am an active participant in the B&R SPICES Sustainable Ginger/Turmeric Project.</li>
<li class="list-group-item">I declare that I will always work to make the farm(s) more fertile and productive.</li>
<li class="list-group-item">I will also work to conserve biodiversity; ensure that soils, waterways and wildlife are protected and enrich the soil to prevent it from being damaged by bush burning, wind and water erosion.</li>
<li class="list-group-item">I will take acceptable measures to manage all forms of waste generated on the farm and in the house.</li>
<li class="list-group-item">I will not farm in forest reserves nor use burning for land preparation and introduce some minimal number of shade trees where possible.</li>
<li class="list-group-item">I will refrain from the use of banned chemicals like for weed/disease management.</li>
<li class="list-group-item">I will protect the rights and welfare of my family, workers and the community and work to ensure that they enjoy decent lives whiles I make sure that my children attend school and refrain from the use of children for any hazardous farming activity.</li>
<li class="list-group-item">I declare that this farm plot(s) will be cultivated under best management practices of the standard of sustainable agriculture. I am aware that this standard is intended to maintain natural biodiversity and the health of the environment and its inhabitants as well as ensure lasting social benefits to my family, workers and the community.</li>
<li class="list-group-item">I declare that I will apply integrated pest management to control pests and where appropriate, apply agrochemical in a rational and reducing manner. In doing so, I will protect my family by applying it responsibly and store it safely.</li>
<li class="list-group-item">I will attend and be actively involved in meetings and training/extension and communal works and events organized by the IMS team.</li>
<li class="list-group-item">I will allow inspections by persons authorized by B&R SPICES Livelihood Charter Ginger/turmeric Certification Project and give access to the fields, stores and documents, etc.</li>
<li class="list-group-item">I will allow investigative audits to be conducted where necessary</li>
<li class="list-group-item">I am aware and will comply with B&R SPICES Sustainability Project to support the entire project, provide support services to the farmer and offer advices for best management practices and co-ordinate the internal and the external management system and inspections.</li>
<li class="list-group-item">I will notify B&R SPICES on introduction of any new variety into the UEBT/RA/MABA for GMO verification and I will further alert B&R SPICES of any program planting GMO materials by my farm.</li>
<li class="list-group-item">I shall not resort to any form of fumigation during the drying and temporary storage in case of pest infestation.</li>
<li class="list-group-item">I will grant access to drying and storage areas and documentation to auditor appointed by B&R SPICES</li>
<li class="list-group-item">If non-conformities are detected in my operation, I commit to work with B&R SPICES to implement corrective actions.</li>
<li class="list-group-item">I will notify B&R SPICES to conduct risk assessment on new plots before cultivation begins.</li>
<li class="list-group-item">I will not be involved in double registration ( register with other company doing similar certification)</li>
<li class="list-group-item">I will support all social control, self-assessment, peer reviews and all initiatives geared towards enriching the sustainability project and avoiding fraud.</li>
<li class="list-group-item">I commit to ensuring that cutting and drying of produce is carried out in a hygienic way to achieve best quality ginger/turmeric for onward sales on the agreed price basis to B&R SPICES/ZLM Nigeria.</li>
<li class="list-group-item">Note: It is not compulsory to sell your ginger/turmeric to B&R SPICES /ZLM Nig. Ltd and I am free to resign from the programme at any time.</li>
<li class="list-group-item">The price of the certified Ginger/Turmeric will depend on the forces of demand and supply in the local market.</li>
<li class="list-group-item">The company is not obliged to buy all certified materials produced by the farmer.</li>
<li class="list-group-item">The company guarantees to pay a premium which will be determined by written agreement between the company and the registered farmers as represented by the community price committee chosen by the registered members.</li>
<li class="list-group-item">I finally declare that this contract has been duly explained to me and I undertake that I do understand my responsibilities and duties to the project</li>
<li class="list-group-item">Prior to possible exit from the business the company shall inform FO at least three (3) months before the start of the new cultivation season.</li>
<li class="list-group-item">This contract is valid for three (3) years.</li>
            </ol>
        </div>
        <table class="table table-sm table-striped table-bordered mx-5">
            <tr><td>Date:</td><td>Date:</td></tr>
            <tr><td><b>Field operator’s name: </b><br/>{{$farmer->farmname}}</td><td>On behalf of B&R SPICES Sustainability Project: <br/>Name:</td></tr>
             <tr><td>Signature/Thumbprint: <br/>
                             @if (!empty($farm->signaturepath))
                     <img src="{{public_path('/storage/'.$farmer->signaturepath)}}" alt="" width="70px"><br>
                @endif
           </td>
                <td>Signature: <br/></td></tr>
        </table>
    </div>
</div>
</x-layouts.app>