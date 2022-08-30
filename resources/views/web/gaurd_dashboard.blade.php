@extends('web.layouts.inner-master')
@section('content')

<section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <a href="/"><img src="{!! asset('assets/images/home.png') !!}"></a>
            </div>
            <div class="col-md-11 seceen_two_right2">
                <div class="row">
                    <div class="col-md-3">
                        <h1>Look up <br>your status</h1>
                        <br>
                        <button id="visitor_list_show" class="active1">Upcoming visitors</button>
                        <button id="pre_invite_list_show">Pre-inviting guests</button>

                       <!--  <button class="btn btn-primary" id="visitor_list_show">Upcoming Visitor List</h3></button>
                        <button class="btn btn-primary" id="pre_invite_list_show">Pre-invitation List</h3></button> -->

                        <input type="text" class="cust_inpt" placeholder="Enter ID / Mobile No."><br>

                        <!--  <img src="assets/img/icons/mobile_friendly 57.png"> -->

                        <div class="bottm_text">Status Check</div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-7" id="visit_list">
                        
                        @if(!empty($datas))
                        @foreach($datas as $no => $data)

                        <div class="status_box">
                            <div class="header"></div>
                            <div class="row" style="padding: 15px 15px;">
                                <div class="col-md-3 col_cst text-center">
                                    <img src="{!! asset('uploads/img/'.$data->image) !!}" style="border-radius: 50%; width: 110px; height: 110px;">
                                    <br><br>
                                    @if(@$data->status =='0') 
                                        <button style="background-color: #FA931A !important;" type="button" class="bg-success text-white">Pending</button>
                                    @elseif(@$data->status =='1') 
                                        <button type="button" class="bg-success text-white">Approved</button>
                                    @elseif(@$data->status =='3') 
                                        <button style="background-color:rgba(var(--bs-danger-rgb),var(--bs-bg-opacity))!important" type="button" class="bg-success text-white">Blocked</button>
                                    @elseif(@$data->status =='5') 
                                        <button style="background-color:rgba(var(--bs-danger-rgb),var(--bs-bg-opacity))!important" type="button" class="bg-success text-white">Cancelled</button>
                                    @elseif(@$data->status =='2') 
                                        <button type="button" class="bg-success text-white">Approved</button>
                                    @endif 

                                </div>
                                <div class="col-md-4 col_cst">
                                    <div class="icon_text text-danger">#{{$data->refer_code}}</div>
                                    <div class="icon_text text-danger"><a style="color: rgba(var(--bs-danger-rgb),var(--bs-text-opacity))!important;" href="tel:{{$data->mobile}}">CALL{{$data->mobile}}</a></div>
                                    <div class="icon_text text-danger">{{ date('d M, Y H:i', strtotime($data->visite_time))}}</div>
                                    <div class="icon_text" style="font-size: 20px;">{{$data->name}}</div>
                                    <div class="icon_text">{{$data->gender}}</div>
                                </div>
                                <div class="col-md-5 col_cst">
                                   <div style="background: #e9e9e9; padding: 2px 15px; border-radius: 8px;">
                                        <div class="icon_text" style="font-size: 20px">@if(isset($data->OfficerDetail->name)) {{$data->OfficerDetail->name}} @endif</div>
                                        <b style="font-size: 14px">@if(isset($data->OfficerDetail->name)) {{$data->OfficerDetail->mobile}} @endif</b>
                                        <img src="{!! asset('assets/images/user33.png') !!}" class="custom_immg">
                                    </div>
                                    <br>
                                    <!-- <div style="background: #e9e9e9; padding: 2px 15px; border-radius: 8px;">
                                        <ul>
                                            <li>00 <br> Hours</li>
                                            <li>00 <br> Minutes</li>
                                            <li>00 <br> Seconds</li>
                                        </ul>
                                    </div> -->
                                  <!--   <br> -->
                                  <a style="background: #23AD8B; border: 0px; font-size: 12px; height: 38px; padding: 11px 16px;" href="{{route('generate.slip',Crypt::encryptString($data->id))}}" class="btn btn-primary" target="_blank">View Detail</a>
                                    @if($data->all_visit->in_status =='Yes') 
                                    <ul2>
                                            <li class="text-success">Check In : {{$data->all_visit->in_time}}</li>
                                            <!-- <li class="text-danger">Check Out : 02:51 PM </li> -->
                                    </ul2>
                                    @endif    
                                </div>
                            </div>
                        </div> 
                        @endforeach
                        <div class="pagination" style="margin: 30px auto;">
                            {{ $datas->links() }}
                        </div>
                        @endif
                    </div>


                    <div class="col-md-7" id="pre-invite">
                        
                        @if(!empty($pre_invitation_lists))
                        @foreach($pre_invitation_lists as $no => $data)

                        <div class="status_box">
                            <div class="header"></div>
                            <div class="row" style="padding: 15px 15px;">
                                <div class="col-md-3 col_cst text-center">
                                    <img src="{!! asset('uploads/img/'.$data->image) !!}" style="border-radius: 50%; width: 110px; height: 110px;">
                                    <br><br>
                                    @if(@$data->status =='0') 
                                        <button style="background-color: #FA931A !important;" type="button" class="bg-success text-white">Pending</button>
                                    @elseif(@$data->status =='1') 
                                        <button type="button" class="bg-success text-white">Approved</button>
                                    @elseif(@$data->status =='3') 
                                        <button style="background-color:rgba(var(--bs-danger-rgb),var(--bs-bg-opacity))!important" type="button" class="bg-success text-white">Blocked</button>
                                    @elseif(@$data->status =='5') 
                                        <button style="background-color:rgba(var(--bs-danger-rgb),var(--bs-bg-opacity))!important" type="button" class="bg-success text-white">Cancelled</button>
                                    @elseif(@$data->status =='2') 
                                        <button type="button" class="bg-success text-white">Approved</button>
                                    @endif 

                                </div>
                                <div class="col-md-4 col_cst">
                                    <div class="icon_text text-danger">#{{$data->refer_code}}</div>
                                    <div class="icon_text text-danger"><a style="color: rgba(var(--bs-danger-rgb),var(--bs-text-opacity))!important;" href="tel:{{$data->mobile}}">CALL{{$data->mobile}}</a></div>
                                    <div class="icon_text text-danger">{{ date('d M, Y H:i', strtotime($data->visite_time))}}</div>
                                    <div class="icon_text" style="font-size: 20px;">{{$data->name}}</div>
                                    <div class="icon_text">{{$data->gender}}</div>
                                </div>
                                <div class="col-md-5 col_cst">
                                   <div style="background: #e9e9e9; padding: 2px 15px; border-radius: 8px;">
                                        <div class="icon_text" style="font-size: 20px">@if(isset($data->OfficerDetail->name)) {{$data->OfficerDetail->name}} @endif</div>
                                        <b style="font-size: 14px">@if(isset($data->OfficerDetail->name)) {{$data->OfficerDetail->mobile}} @endif</b>
                                        <img src="{!! asset('assets/images/user33.png') !!}" class="custom_immg">
                                    </div>
                                    <br>
                                    

                                    <!-- <div style="background: #e9e9e9; padding: 2px 15px; border-radius: 8px;">
                                        <ul>
                                            <li>00 <br> Hours</li>
                                            <li>00 <br> Minutes</li>
                                            <li>00 <br> Seconds</li>
                                        </ul>
                                    </div> -->
                                  <!--   <br> -->
                                  <a style="background: #23AD8B; border: 0px; font-size: 12px; height: 38px; padding: 11px 16px;" href="{{route('generate.slip',Crypt::encryptString($data->id))}}" class="btn btn-primary" target="_blank">View Detail</a>
                                    @if($data->all_visit->in_status =='Yes') 
                                    <ul2>
                                            <li class="text-success">Check In : {{$data->all_visit->in_time}}</li>
                                            <!-- <li class="text-danger">Check Out : 02:51 PM </li> -->
                                    </ul2>
                                    @endif    
                                </div>
                            </div>
                        </div> 
                        
                        @endforeach
                        @endif
                      

                    </div>



                </div>
            </div>
        </div>
    </section>


<!-- <style>
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
                    </div> -->
                    <!-- <div style="width: 35%; display: inline-block; padding: 15px;">
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
    </div>  -->
@endsection

@push('scripts')
<script>
    $("#pre-invite").hide();
    $("#visitor_list_show").on('click',function(){
        $("#visit_list").show();
        $("#pre-invite").hide();
        document.getElementById("visitor_list_show").classList.add('active1');
        document.getElementById("pre_invite_list_show").classList.remove('active1');
    });

    $("#pre_invite_list_show").on('click',function(){
        $("#visit_list").hide();
        $("#pre-invite").show();
        document.getElementById("pre_invite_list_show").classList.add('active1');
        document.getElementById("visitor_list_show").classList.remove('active1');
    });
</script>
@endpush
