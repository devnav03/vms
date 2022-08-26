@extends('web.layouts.inner-master')
@section('content')
<style>
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

    <div id="content">      
        <div id="otp_check">
            <div class="" align="center">
            <div class="heading_dtl">
                <span>Update Visit Status</span>
            </div>
                <p>This action is directly reflect in your records.</p>
                <p class="text-success text-center" style="font-size: 20px;">{{@$message}}</p>
            </div>

            <hr/>
            <div class="login-box col-md-6 col-sm-12 col-xs-12">
                <h2 style="font-size: 24px;">Visitor ID: {{$users_data->refer_code}}</h2>
                <h2 style="font-size: 24px;">Currect Status: @if(@$users_data->status =='0') <strong style="color:red;">Pending</strong> @elseif(@$users_data->status =='1') <strong style="color:green;">Approved</strong> @elseif(@$users_data->status =='2') <strong style="color:yellow;">Blocked</strong>@elseif(@$users_data->status =='3') <strong style="color:red;">Reject</strong> @endif</h2>
                {{-- {{route('doctor.profile',encrypt($doctor->id))}} --}}
                @if($users_data->status =='0')
                <a href="{{route('visitor.approve.success',[$user_ids,@$officer_ids])}}" class="btn btn-success" style="padding: 6px 8px;font-size: 36px;font-weight: 600;">Approve</a><br/>
                <a href="{{route('visitor.reject').'/'.$user_ids.'/'.@$officer_ids}}" class="btn btn-danger" style="padding: 6px 26px;font-size: 36px;font-weight: 600;margin-top: 10px;">Reject</a>
                @endif
            </div>
        </div>     
    </div>
    </div>

@endsection

@push('scripts')
    <script language="JavaScript">
        $('#otp_check').show();
    </script>
@endpush
