@extends('web.layouts.inner-master')
@section('content')
<section>
        <div class="row">
            <div class="col-md-1 seceen_two_left">
                <a href="/"><img src="{!! asset('assets/images/home.png') !!}"></a>
            </div>
            <div class="col-md-11 seceen_two_right2">
                <div class="row">
                    <div class="col-md-4">
                        <h1>Final Step</h1>
                        <p>Snippet of your<br>records</p>
                        <br>
                       <!--  <div id="qrdisplay" style="width: 50%; float: right;"> -->
                        {!! QrCode::size(180)->generate($qr_url); !!}
                        <!-- </div> -->
                        <br>
                        <br>
                        @if(@$visitor_detail->status =='0') 
                            <button type="button" class="bg-danger text-white"><i class="bi bi-clock"></i> Status is Pending</button>
                        @elseif(@$visitor_detail->status =='1') 
                            <button type="button" style="background: green;" class="bg-danger text-white"><i class="bi bi-clock"></i> Approved</button>
                        @elseif(@$visitor_detail->status =='3') 
                            <button type="button" class="bg-danger text-white"><i class="bi bi-clock"></i> Blocked</button>
                        @elseif(@$visitor_detail->status =='5') 
                           <button type="button" class="bg-danger text-white"><i class="bi bi-clock"></i> Cancelled</button>
                        @elseif(@$visitor_detail->status =='2') 
                            <button type="button" class="bg-danger text-white"><i class="bi bi-clock"></i> Approved</button>
                        @endif
                        
                        <br>
                        <i class="bi bi-printer" style="font-size: 35px; color: #9e9e9e;"></i> &nbsp;&nbsp; <i class="bi bi-person-video2" style="font-size: 35px; color: #9e9e9e;"></i>
                        
                        <div class="bottm_text">Preview</div>
                    </div>
                    <div class="col-md-7">
                        <div class="detsils_title">Person Details &nbsp;&nbsp;&nbsp; <!-- <i class="bi bi-pencil-square"></i> --></div>
                        <div class="row">
                            <div class="col-md-8">
                                <div style="font-size: 31px; margin-bottom: 20px">{{@$visitor_detail->name}}</div>
                                <div class="icon_text"><i class="bi bi-envelope"></i> &nbsp;{{@$visitor_detail->email}}</div>
                                <div class="icon_text"><i class="bi bi-tablet"></i> &nbsp;{{@$visitor_detail->mobile}}</div>
                            </div>
                            <div class="col-md-4">
                              <!--   <img src="assets/img/icons/user33.png" style="width: 50%;"> -->
                                @php
                                        $image = str_replace('/public','',URL::to('/')).'/storage/app/public/'.$visitor_detail->image;
                                        @endphp
                                        @if($visitor_detail->image)
                                        <img src="{!! asset('uploads/img/'.$visitor_detail->image) !!}" class="img-responsive" style="width: 170px; height: 170px;
                                         border-radius: 50%;">
                                        @else
                                        <img src="https://vztor.in/superadmin/public/assets/images/user_avtar.png" class="img-responsive" style="width: 50%;">
                                @endif

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="icon_text"><i class="bi bi-building"></i> &nbsp;{{@$visitor_detail->city->name}}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="icon_text"><i class="bi bi-pin-map"></i> &nbsp;{{@$visitor_detail->pincode}}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="icon_text"><i class="bi bi-flag"></i> &nbsp;{{@$visitor_detail->country->name}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="icon_text"><i class="bi bi-geo-alt"></i> &nbsp;{{@$visitor_detail->address_1}}</div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="detsils_title">Person to be met &nbsp;&nbsp;&nbsp; <!-- <i class="bi bi-pencil-square"></i> --></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="icon_text"><i class="bi bi-person"></i> &nbsp;{{@$visitor_detail->OfficerDetail->name?@$visitor_detail->OfficerDetail->name:'N/A'}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="icon_text"><i class="bi bi-tablet"></i> &nbsp;{{@$visitor_detail->OfficerDetail->mobile?@$visitor_detail->OfficerDetail->mobile:'N/A'}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="icon_text"><i class="bi bi-briefcase"></i> &nbsp;{{@$visitor_detail->OfficerDepartment->name?$visitor_detail->OfficerDepartment->name:'N/A'}}</div> 
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="icon_text"><i class="bi bi-geo-alt"></i> &nbsp;New friend, Gali No - 3, Block - J, Sanjay Nagar, Sector- 23, Ghaziabad, U.P, India</div>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="icon_text"><i class="bi bi-pin-map"></i> &nbsp;{{@$visitor_detail->location->name?$visitor_detail->location->name:'N/A'}}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="icon_text"><i class="bi bi-building"></i> &nbsp;{{@$visitor_detail->building->name?$visitor_detail->building->name:'N/A'}}</div>
                            </div>
                           <!--  <div class="col-md-4">
                                <div class="icon_text"><i class="bi bi-pin-map"></i> &nbsp;302020</div>
                            </div> -->
                        </div>
                        
                        <br>
                        <br>
                        <div class="detsils_title">Visit purpose &amp; timing &nbsp;&nbsp;&nbsp; <!-- <i class="bi bi-pencil-square"></i> --></div>
                        <div class="row">
                            <div class="col-md-6">
                                
                                @if($visitor_detail->services == 'Personal')
                                <img src="{!! asset('assets/images/icons-y.png') !!}">
                                @elseif($visitor_detail->services == 'Official')
                                <img src="{!! asset('assets/images/icons-2-y.png') !!}">   
                                @elseif($visitor_detail->services == 'Service')
                                <img src="{!! asset('assets/images/icons-5-y.png') !!}">
                                @elseif($visitor_detail->services == 'Interview')
                                <img src="{!! asset('assets/images/icons-1-y.png') !!}">
                                @elseif($visitor_detail->services == 'Meeting')
                                <img src="{!! asset('assets/images/icons-3-y.png') !!}"> 
                                @elseif($visitor_detail->services == 'Deliver')
                                <img src="{!! asset('assets/images/icons-6-y.png') !!}"> 
                                @endif  &nbsp;&nbsp; 

                                <img src="{!! asset('assets/images/Rectangle-50.png') !!}">
                            </div>
                            <div class="col-md-6">
                                <div class="icon_text"><i class="bi bi-hash"></i> &nbsp;{{@$visitor_detail->topic}}</div>
                                <div class="icon_text"><i class="bi bi-clock"></i> &nbsp;{{@$visitor_detail->visite_duration}} Minuts</div>
                                <div class="row">
                                    <div class="col-md-6"><div class="icon_text"><i class="bi bi-calendar"></i> &nbsp;{{ date('d M, Y', strtotime($visitor_detail->visite_time)) }} </div></div>
                                    <div class="col-md-6"><div class="icon_text"><i class="bi bi-clock"></i> &nbsp;{{ date('h:i A', strtotime($visitor_detail->visite_time)) }}</div></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detsils_title">Firm Detail &nbsp;&nbsp;&nbsp; <!-- <i class="bi bi-pencil-square"></i> --></div>
                                <div class="icon_text"><i class="bi bi-briefcase"></i> &nbsp;{{@$visitor_detail->organization_name?@$visitor_detail->organization_name:'N/A'}} </div>
                                <div class="icon_text"><i class="bi bi-geo-alt"></i> &nbsp; {{@$visitor_detail->firm_address?@$visitor_detail->firm_address:'N/A'}}</div>
                                <div class="icon_text"><i class="bi bi-envelope"></i> &nbsp; {{@$visitor_detail->firm_email?@$visitor_detail->firm_email:'N/A'}}</div>
                                <div class="icon_text"><i class="bi bi-tablet"></i> &nbsp; {{@$visitor_detail->firm_contact?@$visitor_detail->firm_contact:'N/A'}}</div>
                                <div class="icon_text"><i class="bi bi-pin-map"></i> &nbsp; {{@$visitor_detail->orga_pincode?@$visitor_detail->orga_pincode:'N/A'}}</div>
                                <div class="icon_text"><i class="bi bi-person-badge"></i> &nbsp;{{@$visitor_detail->firm_id?@$visitor_detail->firm_id:'N/A'}} </div>
                                @if($visitor_detail->signature)
                                <div class="icon_text"><i class="bi bi-pen"></i> &nbsp;<img src="{!! asset('uploads/img/'.$visitor_detail->signature) !!}" class="img-responsive"></div>
                                @endif
                            </div>
                             <div class="col-md-6">
                                <div class="detsils_title">Identity Proof &nbsp;&nbsp;&nbsp;<!--  <i class="bi bi-pencil-square"></i> --></div>
                                 <div class="icon_text"><i class="bi bi-person-badge"></i> &nbsp;{{ $visitor_detail->adhar_no }}</div>

                                      @if(@$visitor_detail->attachmant)
                                        <a target="_blank" href="{!! asset('uploads/img/'.$visitor_detail->attachmant) !!}"><img src="{!! asset('uploads/img/'.$visitor_detail->attachmant) !!}" class="img-responsive"></a>
                                        @else
                                        <img src="https://vztor.in/superadmin/public/assets/images/demo_doc.jpg" class="img-responsive">
                                        @endif

                                
                            </div>
                        </div>
                       <!--  <br>
                        <br>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button"><i class="bi bi-check"></i> Submit</button>
                                <button type="button" class="bg-success"><i class="bi bi-check"></i> Done</button>
                            </div>
                        </div> -->
                    </div>
               <!--      <div class="col-md-1"></div> -->
                </div>
            </div>
        </div>
    </section>
@endsection

<!-- <!doctype html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Visitor Management System | Visit Slip</title>

        <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet'>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

        <link href='' rel='stylesheet'>
        <style>
            .card {
                margin-bottom: 1.5rem
            }

            .card {
                position: relative;
                display: -ms-flexbox;
                display: flex;
                -ms-flex-direction: column;
                flex-direction: column;
                min-width: 0;
                word-wrap: break-word;
                background-color: #fff;
                background-clip: border-box;
                border: 1px solid #c8ced3;
                border-radius: .25rem
            }

            .card-header:first-child {
                border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0
            }

            .card-header {
                padding: .75rem 1.25rem;
                margin-bottom: 0;
                background-color: #f0f3f5;
                border-bottom: 1px solid #c8ced3
            }
			#qrdisplay svg
			{
				width:100%;
				height:100%;
			}
			.text-title
			{
				text-transform:capitalize;
			}
			.text-uppercase
			{
				text-transform: uppercase;
			}
			.title-main{background:black;color:white; padding:5px; border-radius:5px}
        </style>
        <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
        <script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js'></script>
    </head>
    <body oncontextmenu='return false' class='snippet-body'> -->
     <!--    <div class="container">
            <div id="ui-view" data-select2-id="ui-view">
                <div>
                    <div class="card">
                        <div class="card-header"><i class="fas fa-id-card"></i> Visitor Info Slip
							@auth
								 <span class="btn btn-sm btn-info float-right mr-1 d-print-none"  data-toggle="modal" data-target="#exampleModal"style="background:green"><i class="fa fa-sign-in"></i> Mark In</span>
								@if(@$visitor_detail->all_visit->in_status=="No"  && $visitor_detail->status !='5' &&  $visitor_detail->status !='3' && $visitor_detail->status !='0' )
                                   
									{{--<a class="btn btn-sm btn-info float-right mr-1 d-print-none" href="{{route('web.visitor.in',$visitor_detail->id)}}" data-abc="true" style="background:green">
										<i class="fa fa-sign-in"></i> Mark In
									</a>--}}
                                    <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" href="#" onclick="javascript:window.print();" data-abc="true"><i class="fa fa-print"></i> Print</a>
								@endif
								@if(@$visitor_detail->all_visit->in_status=="Yes" && $visitor_detail->all_visit->out_status=="No")

									<a class="btn btn-sm btn-info float-right mr-1 d-print-none" href="{{route('web.visitor.out',$visitor_detail->id)}}" data-abc="true" style="background:red">
										  Mark Out <i class="fa fa-sign-out"></i>
									</a>
                                    <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" href="#" onclick="javascript:window.print();" data-abc="true"><i class="fa fa-print"></i> Print</a>
                                    <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" target="_blank" href="{{route('generate.slip.small',base64_encode($visitor_detail->id))}}">
                                        <i class="fa fa-print"></i> Print Card</a> 
								@endif
                               
							
                         <a class="btn btn-sm btn-info float-right mr-1 d-print-none" href="{{route('web.home')}}" data-abc="true"><i class="fa fa-user-plus"></i> Add New Visitor</a>
							@elseguest
						
								{{-- <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" href="#" onclick="javascript:window.print();" data-abc="true">
                                <i class="fa fa-print"></i> Print </a> --}}
                              
                                @if($visitor_detail->status =='1')
                                    {{-- <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" target="_blank" href="{{route('generate.slip.small',base64_encode($visitor_detail->id))}}">
                                        <i class="fa fa-print"></i> Print Card</a> --}}
                                @endif
								
                                
                         <a class="btn btn-sm btn-info float-right mr-1 d-print-none" href="{{route('web.home')}}" data-abc="true">
                                <i class="fa fa-user-plus"></i> Add New Visitor
							</a>
							@endauth
                            
                        </div>
                        <div class="card-body"> -->
                  
                                     
                                        
                         <!--             
                            <div class="row mb-4">
                                <div class="col-sm-4">
                                    <h6 class="mb-3 title-main" >Visitor Details:</h6>
                                    <div class="text-title"><i class="fa fa-user"></i> <strong>{{@$visitor_detail->name}}</strong></div>
                                    <div class="text-title"><i class="fas fa-hashtag"></i> <strong>{{@$visitor_detail->refer_code}}</strong></div>
                                    <div class="text-title"><i class="fas fa-handshake"></i> {{@$visitor_detail->services}}</div>
                                    <div><i class="fa fa-envelope"></i> {{@$visitor_detail->email?@$visitor_detail->email:'N/A'}}</div>
                                    <div><i class="fa fa-phone-square"></i> {{@$visitor_detail->mobile?@$visitor_detail->mobile:'N/A'}}</div>
                                    <div class="text-title"><i class="fas fa-restroom"></i> {{@$visitor_detail->gender?@$visitor_detail->gender:'N/A'}}</div>
									
                                    <div><i class="fas fa-id-card-alt"></i> Document ID # {{@$visitor_detail->adhar_no?@$visitor_detail->adhar_no:'N/A'}}</div>
                                    <h6 class="mt-3 title-main"> <i class="fas fa-home"></i> Visitor Address:</h6>
                                   <div class="text-title"><b>Organization Name:</b> {{@$visitor_detail->organization_name?$visitor_detail->organization_name:'N/A'}}</div> 
                                    
                                    <div><b>Address:</b> {{@$visitor_detail->address_1?@$visitor_detail->address_1:'N/A'}}</div>
                                    <div><b>City:</b> {{@$visitor_detail->City->name?@$visitor_detail->City->name:'N/A'}}</div>
                                    <div><b>State:</b> {{@$visitor_detail->State->name?@$visitor_detail->State->name:'N/A'}}</div>
                                    <div><b>Country:</b> {{@$visitor_detail->Country->name?@$visitor_detail->Country->name:'N/A'}}</div>
                                    <div><b>Pincode:</b> {{@$visitor_detail->pincode?@$visitor_detail->pincode:'N/A'}} </div>
                  		        </div>
                                <div class="col-sm-5" style="border-left:1px solid #999">
                                    <h6 class="mb-3 title-main"> Appointment With:</h6>
                                    <div class="text-title"><i class="fas fa-user-tie"></i> <strong> </strong></div>
                                    <div><i class="fas fa-hashtag"></i> <strong>ST00{{@$visitor_detail->OfficerDetail->id}}</strong></div>
                                    <div><i class="fas fa-sign"></i> {{@$visitor_detail->OfficerDepartment->name?@$visitor_detail->OfficerDepartment->name:'N/A'}}</div>
                                    <div><i class="fas fa-envelope"></i> N/A {{--@$visitor_detail->OfficerDetail->email--}}</div>
                                    <div><i class="fas fa-phone-square"></i> N/A {{--@$visitor_detail->OfficerDetail->mobile--}}</div>
									<h6 class="mt-3 title-main"> <i class="fas fa-building"></i> Visiting Office Address:</h6>
									<div class="text-title"><b>Location:</b> </div>
									<div class="text-title"><b>Building:</b> {{@$visitor_detail->building->name?@$visitor_detail->building->name:'N/A'}}</div>							
									<div><b>City:</b> {{@$visitor_detail->OrgaCity->name?@$visitor_detail->OrgaCity->name:'N/A'}}</div>
									<div><b>State:</b> {{@$visitor_detail->OrgaState->name?$visitor_detail->OrgaState->name:'N/A'}} </div> -->
									<!--<div><b>Country:</b> </div>-->
									<!--<div><b>Pincode:</b> </div>-->
							<!-- 	</div> -->
<!-- 
                                 @if(!empty($visitor_detail->vehical_type))
                                <div class="col-sm-12">
                                  <h5 class="mb-4 mt-4"> <i class="fas fa-info-circle"></i> Other Details</h5>
                                </div>
                                    <div class="col-sm-3">
                                        Vehicle Type: {{@$visitor_detail->vehical_type}}
                                    </div>
                                @endif
                                @if(!empty($visitor_detail->vehical_reg_num))
                                    <div class="col-sm-4">
                                        Vehicle Registration Number:  <span class="text-uppercase">{{@$visitor_detail->vehical_reg_num}}</span>
                                    </div>
                                @endif
                                @if($visitor_detail->carrying_device =='on')
                                    <div class="col-sm-2">
                                        No. of Pen Drives: {{@$visitor_detail->pan_drive}}
                                    </div>
                                    <div class="col-sm-2">
                                       No. of Hard Disks: {{@$visitor_detail->hard_disk}}
                                    </div>
                                @endif
                            </div>
                            @if($visitor_detail->visit_type =='group')
                                <h5 class="mb-4 mt-4"> <i class="fas fa-users"></i> Group Details</h5>
                                <div class="table-responsive-sm">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="center">Sr.No.</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Gender</th>
                                                <th>ID Proof No.</th>
                                                <th>Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($visitor_detail->visitorGroup as $key_group=>$grou_data)
                                                <tr>
                                                    <td class="center">{{$key_group+1}}</td>
                                                    <td>{{$grou_data->group_name}}</td>
                                                    <td>{{$grou_data->group_mobile}}</td>
                                                    <td>{{$grou_data->group_gender}}</td>
                                                    <td>{{$grou_data->group_id_proof}}</td>
                                                    <td><img src="{{str_replace('/public','',URL::to('/'))}}/storage/app/public/{{$grou_data->group_image}}" class="img-responsive" style="width:100px;"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif -->
<!-- 
                            @if(!empty($visitor_detail->assets_name))
                                <h5 class="mb-4 mt-4"> <i class="fas fa-briefcase"></i> Visitor's Assets Details</h5>
                                <div class="table-responsive-sm">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="center">Sr.No.</th>
                                                <th>Name</th>
                                                <th>Serial No.</th>
                                                <th>Brand</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $ass = explode(",",$visitor_detail->assets_name);
                                                $ass_num = explode(",",$visitor_detail->assets_number);
                                                $ass_brand = explode(",",$visitor_detail->assets_brand);
                                            @endphp
                                            @foreach($ass as $keys=>$val_ass)
                                                <tr>
                                                    <td class="center">{{$keys+1}}</td>
                                                    <td>{{$val_ass}}</td>
                                                    <td>{{$ass_num[$keys]}}</td>
                                                    <td>{{$ass_brand[$keys]}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif -->
                       <!--      <div class="row">
                                <div class="col-lg-12 col-sm-12 ml-auto"> -->
                                  <!--  <h5 class="mb-4 mt-4"><i class="fa fa-medkit"></i> Covid Declaration</h5> -->
                         <!--            <div class="row mb-4"> -->
                                        <!-- <div  class="col-md-6">
                                            <div class="col-sm-12">
                                                Have you taken covid vaccine? : <strong class="text-title">{{@$visitor_detail->vaccine?@$visitor_detail->vaccine:'N/A'}}</strong>
                                            </div>
                                            <div class="col-sm-12">
                                                Are you currently experiencing any symptoms? : <strong class="text-title">{{@$visitor_detail->symptoms?@$visitor_detail->symptoms:'N/A'}} </strong>
                                            </div>
                                            <div class="col-sm-12">
                                                Have you traveled in the past 14 days to other states? : <strong class="text-title">{{@$visitor_detail->travelled_states?@$visitor_detail->travelled_states:'N/A'}}</strong>
                                            </div>
                                            <div class="col-sm-12">
                                                Did you get in touch of any covid positive patient? : <strong class="text-title">{{@$visitor_detail->patient?@$visitor_detail->patient:'N/A'}}</strong>
                                            </div>
                                            <div class="col-sm-12">
                                                Current body temperature: <strong>{{@$visitor_detail->temprature?@$visitor_detail->temprature:'It will be entred by guard'}}</strong>
                                            </div>
                                        </div> -->
                                        
         <!--                                <div  class="col-md-6">
                                            <div id="qrdisplay" class="col-sm-12" style="width: 178px; float: right;">
                                            {!! QrCode::size(300)->generate($qr_url); !!}
                                            </div>
                                        </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

     <!--    <div class="modal" id="exampleModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Enter Visitor's Current Body Temperature</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                {{Form::open(['route'=>['web.visitor.in',$visitor_detail->id],  'method' => 'get', 'enctype'=>'multipart/form-data'])}}
                <div class="modal-body">
                  <input type="text" value="" name="temprature" id="temprature_m" onkeyup="checkTem()" class="form-control" required>
                  <p class="text-danger" id="msg"></p>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                {{Form::close()}}

              </div>
            </div>
        </div> -->
        
  <!--       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            function checkTem(){
                var max_limit = parseInt("{{$body_temp->name}}");  //99.99
                var temp = parseInt($("#temprature_m").val());     
                if(!Number.isInteger(temp)){
                    $("#msg").text("please enter valid temperature");
                    $("#temprature_m").val('');
                }     
                if(temp > max_limit){
                    $("#msg").show();
                    $("#msg").text("please enter valid temperature");
                    $("#temprature_m").val('');
                }else{                    
                    $("#msg").hide();
                }
            };
        </script> -->
<!--     </body>
</html> --> 

