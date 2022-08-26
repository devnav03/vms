<!-- Add icon library -->
<style>
body
{
  font-family: Arial, Helvetica, sans-serif;
  margin: 0 auto;
}
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
.card-holder{

  max-width: 375px;
  margin: auto;
  

}
.card {
  width: 182px;
  height:295px;
  margin: auto;
  text-align: center;
  float:left;
  border-bottom:1px dashed #999;
  padding-top:10px;
  
}

.title {
  color: grey;
  font-size: 18px;
}

button {
  border: none;
  outline: 0;
  display: inline-block;
  padding: 8px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}

a {
    text-decoration: none;
    /* font-size: 22px; */
    color: #fff;
    background: #445fe7;
    padding: 9px;
    border-radius: 20px;
    font-size: 19px;
}

button:hover, a:hover {
  opacity: 0.7;
}
.vztorimage
{
width:140px;
height:140px;
border:1px solid #333;
border-radius:10px
}
h1{
font-size:18px;
margin:0 auto;
}
h2{
font-size: 14px;
margin:0 auto;
padding:2px;
}
h3{
font-size: 12px;
margin:0 auto;
padding:2px;
border-bottom:1px solid #333;
}
h4{
font-size: 12px;
margin:0 auto;
padding:2px;
	text-transform:capitalize;
}
.status
{

padding:2px;
color:black;
font-size:20px;
}
p
{
padding:5px;
}
</style>

<body>
<div class="card-holder">
	<div class="card" style="border-right:1px dashed #999">
	  <img src="{{str_replace('','',URL::to('/'))}}/uploads/img/{{$visitor_detail->image?$visitor_detail->image:'/user_avtar.png'}}" class="vztorimage">
	  <h1>Visitor: #{{@$visitor_detail->refer_code}}</h1>
	   <h2>Name: {{@$visitor_detail->name}}</h2>
	  <h3>{{@$visitor_detail->organization_name}}</h3>
	  <h4>Visiting Office: {{@$company_name}}</h4>
	 <h4>Person to meet: {{@$visitor_detail->OfficerDetail->name}}</h4>
	  <h4>Building: {{@$visitor_detail->building->name}}</h4>
	  	@if(@$visitor_detail->vaccine=="yes")
		<div class="status" style="border:1px solid #000;padding:2px;color:black;font-size:20px;">Vaccinated</div>
		@elseif(@$visitor_detail->vaccine=="no")
		<div class="status"  style="border:1px solid #000;padding:2px;color:black;font-size:20px;">Not Vaccinated</div>
		@else
		<div class="status"  style="border:1px solid #000;padding:2px;color:black;font-size:20px;">N/A</div>
		@endif
	  
	</div>
	<div class="card">
	  <h2 style="border:1px solid #000; color:#000; border-bottom:1px solid #333">Covid Declaration</h2>
	 <h4>Vaccinated: {{@$visitor_detail->vaccine?@$visitor_detail->vaccine:'N/A'}}</h4>
			@if($visitor_detail->vaccine=="yes")
				<h4>Dose: {{@$visitor_detail->vaccine_count}}</h4>
	 			<h4>Vaccine Name: {{@$visitor_detail->vaccine_name}}</h4>
			@endif
	 
	 <h4>Current Temparature: {{@$visitor_detail->temprature?@$visitor_detail->temprature:'N/A'}}</h4>
	
		<ul style="font-size:12px; text-align:left; padding-left:20px; text-transform:capitalize">
			<li> Travelled in past 14 days out side India: {{@$visitor_detail->travelled_states?@$visitor_detail->travelled_states:'N/A'}} </li>
			<li> Got in touch of any Covid +ve paitent: {{@$visitor_detail->patient?@$visitor_detail->patient:'N/A'}} </li>
			<li> Any health issue: {{@$visitor_detail->symptoms?@$visitor_detail->symptoms:'N/A'}} </li>
		</ul>
	
	 
	{!! QrCode::size(80)->generate($qr_url); !!}
		
		<div style="font-size:10px; margin-top:10px;">Powered by EI Networks Pvt. Ltd.</div>
	</div>
	<div style="margin-left: 136px;">
		<a type="" class="btn btn-sm btn-secondary float-right mr-1 no-print" href="#" onclick="javascript:window.print();" data-abc="true">
			<i class="fa fa-print"></i> Print Card
		</a>
	</div>
</div>
</body>
