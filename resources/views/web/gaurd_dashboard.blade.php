@extends('web.layouts.inner-master')
@section('content')
<style>
    label {
         color: #000;
    }
	@media (max-width: 701px){
.logo_image {
	display: inline-block;
    padding-top: 7px;
    margin-left: 14px;
    width: 34px;
	}
	.h1, h1 {
		font-size: 19px !important;
	}
	}
@media (max-width: 701px){
.navbar-inverse .navbar-toggle {
    border-color: #2e98c5 !important;
    margin-top: -78px !important;
    margin-bottom: 31px !important;
    position: absolute;
    top: 39px;
    margin-left: 288px;
	}}
</style>

		<nav class="navbar navbar-inverse">
		  <div class="container-fluid">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
			  <ul class="nav navbar-nav row col-lg-12" align="center">
				<li class="nav-item " ><a class="nav-link" href="{{route('web.home')}}">Home</a></li>
				<li class="nav-item  "><a class="nav-link" href="{{route('web.self-registration')}}">New Visit</a></li>
				<li class="nav-item  "><a class="nav-link" href="{{route('web.re-visit')}}">Re-Visit</a></li>
				<li class="nav-item  "><a class="nav-link" href="{{route('web.status')}}">Know Visit Status</a></li>
				<li class="nav-item "><a class="nav-link" href="{{route('web.qr-code')}}">Scan QR Code</a></li>
				  @auth
				  <li class="nav-item active"><a class="nav-link" href="{{url('guard/dashboard')}}">Guard Dashboard</a></li>				  
				  <li class="nav-item "><a class="nav-link" href="{{route('web.gaurd.logout')}}">Logout</a></li>	
				  
				  @elseguest
					<li class="nav-item"><a class="nav-link" href="{{route('web.gaurd.login')}}">Guard Login</a></li>
				  @endauth
				</ul>
			</div>
		  </div>
		</nav>
        <div id="content">
            <hr/>
            <button class="btn btn-primary" id="visitor_list_show">Upcoming Visitor List</h3></button>
            <button class="btn btn-primary" id="pre_invite_list_show">Pre-invitation List</h3></button>
			<div id="visit_list">
                @if(!empty($datas))
                <center><h3>Upcoming Visitor List</h3></center>
                @foreach($datas as $no => $data)
                <div class="row" >
                    <div style="width: 16%; display: inline-block; padding: 15px;">
                        <div>
                            <label for="officer">S.No. : {{$no+1}}</label><br>
                            <label for="vaccine">Visitor Id : #{{$data->refer_code}}</label>
                            <label for="patient">Status : 
                                @if(@$data->status =='0') 
                                    <strong style="color:red;">Pending</strong>
                                @elseif(@$data->status =='1') 
                                    <strong style="color:green;">Approved</strong>
                                @elseif(@$data->status =='3') 
                                    <strong style="color:yellow;">Blocked</strong>
                                @elseif(@$data->status =='5') 
                                    <strong style="color:red;">Cancelled</strong>
                                @elseif(@$data->status =='2') 
                                    <strong style="color:green;">Approved</strong>
                                @endif 
                            </label>
                            <label>
                                @if($data->all_visit->in_status =='Yes')                                
                                    <strong style="color:red;">Checked IN </strong><br/>
                                    <strong style="color:red;">({{$data->all_visit->in_time}} )</strong>
                                @endif
                            </label>
                        </div>
                    </div>
                    <div style="width: 23%; display: inline-block; padding: 15px;">
                        <div>
                            
                            <label for="vaccine">Name : {{$data->name}}</label><br/>
                            <label for="states">Mobile : {{$data->mobile}}</label><br/>
                            <label for="states">Gender : {{$data->gender}}</label><br/>
                            <label for="officer">Officer : @if(isset($data->OfficerDetail->name)) {{$data->OfficerDetail->name}} @endif</label>
                        </div>
                    </div>
                    <div style="width: 35%; display: inline-block; padding: 15px;">
                        <div>
                            
                            <label for="symptoms">Email : {{$data->email}}</label><br/>
                            <label for="patient">Document Id : {{@$data->adhar_no?$data->adhar_no:'N/A'}}</label><br/>
                            <label for="patient">Visit Date Time : {{$data->visite_time}}</label>   <br/>                         
                            <label for="patient">Purpose Of Visit : {{$data->services}}</label>
                        </div>
                    </div>
                    <div style="width: 10%; display: inline-block;">
                        <a href="{{route('generate.slip',Crypt::encryptString($data->id))}}" class="btn btn-primary" target="_blank">View Detail</a>
                    </div>
                </div>
                <hr>
                @endforeach
                @else
                <div class="row" >
                    <b>Oops No Visitor Found !!!</b>
                </div>
                <hr>
                @endif
            </div>
            <div id="pre-invite">
                <center><h3>Pre-Invitation List</h3></center>
                @if(count($pre_invitation_lists)>0)
                
                @foreach($pre_invitation_lists as $nos => $datas)
                <div class="row">
                    <div style="width: 16%; display: inline-block; padding: 15px;">
                        <div>
                            <label for="officer">S.No. : {{$nos+1}}</label><br>
                            <label for="vaccine">Visitor Id : #{{$datas->refer_code}}</label>
                            <label for="patient">Status : <strong style="color:green;">Approved</strong>
                            <label>
                                @if($datas->all_visit->in_status =='Yes')                                
                                    <strong style="color:red;">Checked IN </strong><br/>
                                    <strong style="color:red;">({{$datas->all_visit->in_time}} )</strong>
                                @endif
                            </label>
                        </div>
                    </div>
                    <div style="width: 18%; display: inline-block; padding: 15px;">
                        <div>
                           
                            <label for="vaccine">Name : {{$datas->name}}</label>
                            <label for="states">Mobile : {{$datas->mobile}}</label>
                             <label for="officer">Officer : {{$datas->OfficerDetail->name}}</label>
                        </div>
                    </div>
                    <div style="width: 40%; display: inline-block; padding: 15px;">
                        <div>
                           
                            <label for="patient">Visit Date Time : {{$datas->pre_visit_date_time}}</label>
                            <label for="patient"></label>
                             <label for="symptoms">Email : {{$datas->email}} </label>
                        </div>
                    </div>
                    <div style="width: 10%; display: inline-block;">
                        <a href="{{route('generate.slip',Crypt::encryptString($datas->id))}}" class="btn btn-primary" target="_blank">In/Out</a>
                        @if(empty($datas->image_base) || empty($datas->image) )
                        <a href="{{url("/pre-invitations/verify-details/".$datas->email."/".$datas->id)}}" class="btn btn-primary" target="_blank"  style="margin-top: 3px;">Verify details</a>                        
                        @endif
                        <a href="{{route('generate.slip.small',base64_encode($datas->id))}}" class="btn btn-primary" target="_blank" style="margin-top: 3px;">Generate E-pass</a>
                      {{--  <a href="{{url("/pre-invitations/join/".$datas->email."/".$datas->id)}}" class="btn btn-primary" target="_blank">Complete details</a>--}}
                    </div>
                </div>
                <hr>
                @endforeach
                @else
                    <div class="row">
                    <b>Oops No Pre-Invitation found !!!</b>
                    </div>
                    <hr>            
                @endif
            </div>
            
           
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $("#pre-invite").hide();
    $("#visitor_list_show").on('click',function(){
        $("#visit_list").show();
        $("#pre-invite").hide();
    });

    $("#pre_invite_list_show").on('click',function(){
        $("#visit_list").hide();
        $("#pre-invite").show();
    });
</script>
@endpush
