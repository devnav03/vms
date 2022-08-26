
<!doctype html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Visitor Management System | Visit Slip</title>

        <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet'>
        <link href='' rel='stylesheet'>
        <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
        <script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js'></script>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css"/>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
              
		<style>
			
			.card-registration .select-input.form-control[readonly]:not([disabled]) {
			  font-size: 1rem;
			  line-height: 2.15;
			  padding-left: .75em;
			  padding-right: .75em;
			}
			.card-registration .select-arrow {
			  top: 13px;
			}
			select.form-control:not([size]):not([multiple]) {
				height: calc(2.25rem + 9px);
			}
		</style>
    </head>
    <body oncontextmenu='return false' class='snippet-body'>
        <div class="container">
            <div id="ui-view" data-select2-id="ui-view">
               <div class="row d-flex justify-content-center align-items-center h-100">
				  <div class="col">
					<div class="card card-registration my-4">
					  <div class="row g-0">
						<div class="col-xl-6 d-none d-xl-block">
						  <img
							src="https://mdbootstrap.com/img/Photos/new-templates/bootstrap-registration/img4.jpg"
							alt="Sample photo"
							class="img-fluid"
							style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;"
						  />
						</div>
						<div class="col-xl-6">
						  <div class="card-body p-md-5 text-black">
							<h3 class="mb-5 text-uppercase">Visitor registration form</h3>
							  <form action="{{url('visitot/registration/building/store')}}" method="post" >
								  @csrf
									<div class="row">
									  <div class="col-md-6">
										<div class="form-outline">
											<label class="form-label" for="form3Example1m">Name</label>
											<input type="text" name="name" class="form-control form-control-lg" value="{{old('name')}}" required />
											@if($errors->has('name'))<p class="text-danger">{{ $errors->first('name') }}</p> @endif
										</div>
									  </div>
									  <div class="col-md-6">
										<div class="form-outline">
											<label class="form-label" for="form3Example1n">Email</label>
											<input type="email" name="email" value="{{old('email')}}" class="form-control form-control-lg" />
											@if($errors->has('email'))<p class="text-danger">{{ $errors->first('email') }}</p> @endif
										</div>
									  </div>

									  <div class="col-md-6">
										<div class="form-outline">
										  <label class="form-label" for="form3Example1m1">Mobile</label>
										  <input type="tel" name="mobile" id="mobile" value="{{old('mobile')}}" class="form-control form-control-lg" required />
											@if($errors->has('mobile'))<p class="text-danger">{{ $errors->first('mobile') }}</p> @endif
										 </div>
									  </div>
									  <div class="col-md-6">
										  <label class="form-label" for="form3Example1m1">Gender</label>
											<select class="select form-control"  name="gender" required> 
											  <option value="Male">Male</option>
											  <option value="Female">Female</option>
											  <option value="Other">Other</option>
											</select>
										  @if($errors->has('gender'))<p class="text-danger">{{ $errors->first('gender') }}</p> @endif
									  </div>

									  <div class="col-md-6">
										<label class="form-label" for="form3Example1m1">Visit Purpose</label>
										<select class="form-control" name="services" required>
										  <option value="Official">Official</option>
										  <option value="Personal">Personal</option>
										</select>
										  @if($errors->has('services'))<p class="text-danger">{{ $errors->first('services') }}</p> @endif
	
									  </div>
									  <div class="col-md-6">
										<label class="form-label" for="form3Example1m1">Building</label>
										<select class="form-control" value="{{old('building_id')}}" name="building_id" required>
											<option value="{{$buildings['id']}}">{{$buildings['name']}}</option>
										</select>
                     						@if($errors->has('building_id'))<p class="text-danger">{{ $errors->first('building_id') }}</p> @endif
									  </div>
									  <div class="col-md-6">
										<label class="form-label" for="form3Example1m1">Department</label>
										<select class="form-control" name="department_id" id="department_id" required>
											<option value="">Select Department</option>
											  @foreach($departments as $department)
												<option value="{{$department['id']}}">{{$department['name']}}</option>
											  @endforeach
										</select>
										@if($errors->has('department_id'))<p class="text-danger">{{ $errors->first('department_id') }}</p> @endif
									  </div>
									  <div class="col-md-6">
										<label class="form-label" for="form3Example1m1">Officer</label>
										<select class="form-control" name="officer" id="officer_id" required>
										  <option value="">Select Office</option>
										 
										</select>
											@if($errors->has('officer'))<p class="text-danger">{{ $errors->first('officer') }}</p> @endif
									  </div>
									  <div class="col-md-6">
										<div class="form-outline">
											<label class="form-label" for="form3Example1n">Organization Name</label>
											<input type="text" name="organization_name" class="form-control form-control-lg" required />
											@if($errors->has('organization_name'))<p class="text-danger">{{ $errors->first('organization_name') }}</p> @endif
										</div>
									  </div>
									  <div class="col-md-6">
										<label class="form-label" for="form3Example90">DOB</label>
										<input type="date" name="dob" value="{{old('dob')}}" class="form-control form-control-lg" required />
											@if($errors->has('dob'))<p class="text-danger">{{ $errors->first('dob') }}</p> @endif
									 </div>
								   </div>
								  <div class="row">
									  <div class="col-md-6 col-xs-6">
										  <label>Image:</label>
										 
										  <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#exampleModalCenter">
											  Take Snapshot
										  </button>
										  <input type="hidden" name="image" value="{{old('image')}}" class="image-tag" required>
										  @if($errors->has('image'))
										  <p class="text-danger">{{ $errors->first('image') }}</p>
										  @endif
									  </div>
									  <div class="col-md-6 col-xs-6">
										  <div id="results" style="color:#107cab; font-size: 14px; margin-top:15px;">Your captured image will appear here...</div>
									  </div>
									  
								  </div>
								  <div class="row">
									  <div class="col-md-12">
										  <h3 class="mb-5 text-uppercase">Covid Declaration Form</h3>
									  </div>
								  </div>
								  <div class="row">
										<div class="col-md-6">
											<label for="vaccine">Have you taken the vaccine? :<span class="text-danger">*</span> </label>
											<select name="vaccine" class="form-control" required onchange="vaccineCheck(this.value);">
												<option value="{{old('vaccine')}}">Select Option</option>
												<option value="yes">Yes </option>
												<option value="no">No</option>
											</select>
											@if($errors->has('vaccine'))<p class="text-danger">{{ $errors->first('vaccine') }}</p> @endif
									  </div>
									  <div class="col-md-6">
										<label for="symptoms">Are You Currently Experiencing Any Following Symptoms? :</label>
										  <select name="symptoms" class="form-control nowarn" required>
											  <option value="{{old('symptoms')}}">Select Option</option>
											  @foreach($symptoms as $symptom)
											  	<option value="{{$symptom->name}}">{{$symptom->name}}</option>
											  @endforeach
										 </select>
										  @if($errors->has('symptoms'))<p class="text-danger">{{ $errors->first('symptoms') }}</p> @endif
									  </div>
									  <div id="vaccine_details">
										  <div class="col-md-6">
											<label for="vaccine">Which dose of vaccine have you taken? :<span class="text-danger">*</span> </label>
											<select name="vaccine_count" class="form-control nowarn" required>
												<option value="1">First Dose</option>
												<option value="2">Second Dose </option>
											</select>
											@if($errors->has('vaccine'))<p class="text-danger">{{ $errors->first('vaccine') }}</p> @endif
										  </div>
											<div class="col-md-6">
											  <label for="vaccine">Have you taken the vaccine company? :<span class="text-danger">*</span> </label>
											  <select name="vaccine_name" class="form-control nowarn" required>
												  <option value="Covishield">Covishield</option>
												  <option value="Covaxin">Covaxin</option>
												  <option value="Sputnik V">Sputnik V</option>
												  <option value="ZyCoV-D">ZyCoV-D</option>
												  <option value="mRNA-1273">mRNA-1273</option>
											  </select>
											  @if($errors->has('vaccine'))<p class="text-danger">{{ $errors->first('vaccine') }}</p> @endif
										  </div>
									  </div>
									   <div class="col-md-6">
										  <label for="states">Have you traveled to any covid affected State in the past 14 days? :</label>
										  <select name="states" class="form-control nowarn" required>
											  <option value="{{old('states')}}">Select Option</option>
											  <option value="yes">Yes </option>
											  <option value="no">No</option>
										  </select>
										  @if($errors->has('states'))<p class="text-danger">{{ $errors->first('states') }}</p> @endif
									  </div>

									  <div class="col-md-6">
										  <label for="patient">Did you get in touch with any COVID positive patient in the last 15 days?:</label>
										  <select name="patient" class="form-control nowarn" required>
											  <option value="{{old('patient')}}">Select Option</option>
											  <option value="yes">Yes </option>
											  <option value="no">No</option>
										  </select>
										  @if($errors->has('patient'))<p class="text-danger">{{ $errors->first('patient') }}</p> @endif
									  </div>

									  <div class="col-md-6">
										  <label for="temprature">Current Body Temperature:<span class="text-danger">*</span></label>
										  <input placeholder="Enter Current Body Temprature" type="number" name="temprature" size="30" value="{{old('temprature')}}" class="form-control nowarn" maxlength="5" required>
										  @if($errors->has('temprature'))<p class="text-danger">{{ $errors->first('temprature') }}</p> @endif
									  </div>
									</div>
									
								 <div class="d-flex justify-content-end pt-3">
								  <input type="submit" class="btn btn-success btn-sm ms-2" value="Submit form">
								</div>
							  </form>
						  </div>
						</div>
					  </div>
					</div>
				  </div>
				</div>

            </div>
        </div>
		<div>
			<!-- Button trigger modal -->
			

			<!-- Modal -->
			<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="background: rgb(0 0 0);" data-backdrop="static" data-keyboard="false">
			  <div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
				 <div class="modal-body">
					<div id="my_camera"></div>
					 <input class="btn btn-primary btn-sm" type="button" value="Take Snapshot" onClick="take_snapshot()" style="width: 100%;background-color: #107cab; margin-top:15px;">
				  </div>
				  
				</div>
			  </div>
			</div>
			
		<script type='text/javascript'>
			$('#department_id').on('change', function() {
            $("#loader-p").show();
                var depart_id = this.value;
                $("#officer_id").html('');
                $.ajax({
                    url:"{{route('web.get.officer')}}",
                    type: "POST",
                    data: {
                    department_id: depart_id,
                    _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result){
                      $("#loader-p").hide();
                      $("#officer_id").append('<option value="">Select Officer</option>');
                        $.each(result.states,function(key,value){
                            $("#officer_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                        $('#city-dropdown').html('<option value="">Select State First</option>');
                    }
                });
            });
			$('#vaccine_details').hide();
			function vaccineCheck(val){
			  if(val=="yes"){
				$('#vaccine_details').show();
			  }else{
				$('#vaccine_details').hide();
			  }
			}
			Webcam.set({
				width: 300,
				height: 300,
				image_format: 'jpeg',
				jpeg_quality: 500
			});
			 Webcam.attach( '#my_camera' );

			function take_snapshot() {
				Webcam.snap( function(data_uri) {
					$(".image-tag").val(data_uri);
					document.getElementById('results').innerHTML = '<img src="'+data_uri+'" width = "100px" height= "100px"/>';
					$('#exampleModalCenter').modal('hide');
				} );
			}
			$("#mobile").focusout(function(){
			   var mobile = $("#mobile").val();
			   $("#loader-p").show();

			   $.ajax({
				   url:"{{route('web.get.mobiledetails')}}",
				   type: "POST",
				   data: {
				   mobile: mobile,
				   _token: '{{csrf_token()}}'
				   },
				   dataType : 'json',
				   success: function(result){
					 $("#loader-p").hide();
					 if(result.status=="success"){
					   $('#mobile_error').html('');
					 }else{
					   $('#mobile_error').html(result.message);
					 }
				   }
			   });
			 });
		</script>
			
			@if (Session::has('message'))
				<script type="text/javascript">
					Command: toastr["{{Session::get('class')}}"](" {{Session::get('message')}}")
				</script>
			@endif
    </body>
</html>

