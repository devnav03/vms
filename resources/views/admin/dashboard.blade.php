@extends('admin.layout.master')
@section('content')
<style>
    input.input-mini.form-control {
    width: 174px !important;
    }
</style>
<style>

#wrapper
{
 margin:0 auto;
 padding:0px;
 text-align:center;
 width:995px;
}
#wrapper h1
{
 margin-top:50px;
 font-size:45px;
 color:#585858;
}
#wrapper h1 p
{
 font-size:18px;
}
#employee_piechart
{
 padding:0px;
 width:600px;
 height:400px;
 margin-left:190px;
}
</style>
@php
  
  if($all_checkin_visitor !=0 && $all_upcoming_visitor !=0 && $all_overstaying_visitor !=0 && $all_checkout_visitor !=0){
    $encoded_data ='';
  }else{
    
    $rating_data = array(
    array('Vistor', 'Report'),
    array('Check-in Visitor',$all_checkin_visitor),
    array('Upcoming Visitor',$all_upcoming_visitor),
    array('Overstaying Visitor',$all_overstaying_visitor),
    array('Check-out Visitor',$all_checkout_visitor),
    );
    $encoded_data = json_encode($rating_data);
  }



@endphp
<!-- START CONTENT -->
<section id="content">
    <!--start container-->
    <div class="container">
        <!--Button dashboard start-->
        @if(auth('admin')->user()->role_id =='5')
        <div id="chart-dashboard">
            <div class="row">
                <div class="col s12 m8 l6" style="text-align: center;">
                    <a class="btn" href="{{url('admin-panel/users/create')}}" role="button">Add New Visitor</a>
                </div>
                <div class="col s12 m4 l4" style="text-align: center;">
                    <a class="btn" href="{{route('user-revisit')}}" role="button">Revisit</a>
                </div>
            </div>
        </div>
        @endif
        <!--card stats end-->
       
        @if(auth('admin')->user()->role_id =='1' || auth('admin')->user()->role_id =='4'|| auth('admin')->user()->role_id =='6')
        <div id="card-stats">
            <div class="row">
                <div class="col s12 m6 l3">
                    <div class="card">
                        <div class="card-content  blue white-text">
                            <p class="card-stats-title">Check-in Visitor</p>
                            <h4 class="card-stats-number">{{$all_checkin_visitor}}</h4>
                            </p>
                        </div>
                        <div class="card-action  blue darken-2">
                            <div id="clients-bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l3">
                    <div class="card">
                        <div class="card-content red white-text">
                            <p class="card-stats-title">Upcoming Visitor</p>
                            <h4 class="card-stats-number">{{$all_upcoming_visitor}}</h4>
                            </p>
                        </div>
                        <div class="card-action red darken-2">
                            <div id="sales-compositebar"></div>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l3">
                    <div class="card">
                        <div class="card-content orange white-text">
                            <p class="card-stats-title">Overstaying Visitor</p>
                            <h4 class="card-stats-number">{{$all_overstaying_visitor}}</h4>
                            </p>
                        </div>
                        <div class="card-action orange darken-2">
                            <div id="profit-tristate"></div>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l3">
                    <div class="card">
                        <div class="card-content green white-text">
                            <p class="card-stats-title">Check-out Visitor</p>
                            <h4 class="card-stats-number">{{$all_checkout_visitor}}</h4>
                            </p>
                        </div>
                        <div class="card-action  green darken-2">
                            <div id="invoice-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="work-collections">
            
            <div id="employee_piechart" style="width: 900px; height: 500px;"></div>

            <form action="{{url('admin-panel/dashboard')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col s3 m3 l3">
                        <select name="date_range" id="date_range" onchange="showDateRange(this.value);" required>
                            <option value="{{@$last_input}}" selected disabled>{{@$last_input?$last_input:'Please Select'}}</option>
                            <option value="Today">Today</option>
                            <option value="1 Week"> Last 7 Days</option> 
                            <option value="1 Month"> Last 30 Days</option>
                            <option value="2 Month"> Last 60 Days</option>
                            <option value="3 Month"> Last 90 Days</option>
                            <option value="custom">custom</option>
                        </select>
                    </div>
                    <div id="rangerange">
                        <div class="col s3 m3 l3">
                            <input type="date" name="from_date" class="form-control">
                        </div>
                        <div class="col s3 m3 l3">
                            <input type="date" name="till_date" class="form-control">
                        </div>
                    </div>
                    <div class="col s3 m3 l3">
                        <input type="submit" class="btn btn-primary" value="filter">
                    </div>
                </div>
            </form>
          
            <div class="row" style="display:none">
                <div class="col s12 m12 l6">
                    <ul id="issues-collection" class="collection">
                        <li class="collection-item avatar">
                            <!-- <i class="mdi-action-bug-report circle red darken-2"></i> -->
                            <span class="collection-header">{{@$last_input}} Appointment </span>
                            
                        </li>
                        <li class="collection-item">
                            <div class="row">
                                <div class="col s3">
                                    <p class="collections-title">Total Visitor</p>
                                </div>
                                <div class="col s3">
                                    <p class="collections-title">In Visitor</p>
                                </div>
                                <div class="col s3">
                                    <p class="collections-title">Out Visitor</p>
                                </div>
                                <div class="col s3">
                                    <p class="collections-title">Action</p>
                                </div>

                        </li>
                        <li class="collection-item">
                            <div class="row">
                                <div class="col s3">
                                    <a href="{{url('/admin-panel/visitor-report')}}/?report=total">
                                        <p class="collections-content"><b>{{$reports['total']}}</b></p>
                                    </a>
                                </div>
                                <div class="col s3">
                                    <a href="{{url('/admin-panel/visitor-report')}}/?report=in">
                                        <p class="collections-content"><b>{{$reports['today_in']}}</b></p>
                                    </a>
                                </div>
                                <div class="col s3">
                                    <a href="{{url('/admin-panel/visitor-report')}}/?report=out">
                                        <p class="collections-content"><b>{{$reports['today_out']}}</b></p>
                                    </a>
                                </div>
                                <div class="col s3">
                                    @if(@$reports['today_in']>0)
                                    <button class="btn" id="sos-btn"  style="padding: 0px 9px;">Send SOS</button>
                                    @else
                                    <button class="btn" id="sos-btn" disabled style="padding: 0px 9px;">Send SOS</button>
                                    @endif

                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="col s12 m12 l6">

                    <ul id="issues-collection" class="collection">

                        <li class="collection-item avatar">

                            <i class="mdi-action-bug-report circle red darken-2"></i>

                            <span class="collection-header">Alert</span>

                            <p>Assigned to you</p>

                            

                        </li>

                        <li class="collection-item">

                            <div class="row">

                                <div class="col s3">
                                    <p class="collections-title">Officer Id</p>

                                </div>

                                <div class="col s3">

                                    <p class="collections-title">Officer Name</p>

                                </div>

                                <div class="col s3">

                                    <span class="collections-title">Mobile</span>

                                </div>

                                <div class="col s3">

                                    <span class="collections-title">Email</span>

                                </div>

                            </div>

                        </li>

                        <li class="collection-item">

                            <div class="row">

                                <div class="col s3">

                                    <p class="collections-content">Officer Id</p>

                                </div>

                                <div class="col s3">

                                    <p class="collections-content">Officer Name</p>

                                </div>

                                <div class="col s3">

                                    <span class="collections-content">Mobile</span>

                                </div>

                                <div class="col s3">

                                    <span class="collections-content">Email</span>

                                </div>

                            </div>

                        </li>

                    </ul>

                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <ul id="projects-collection" class="collection">
                        <li class="collection-item avatar">
                            <i class="mdi-file-folder circle light-blue darken-2"></i>
                            <span class="collection-header">Appointment Request</span>
                            <p>Latest Appointment List</p>
                            
                        </li>
                        <li class="collection-item">
                            <div class="row">
                                <div class="col s3">
                                    <p class="collections-title">Visitor Id</p>
                                </div>
                                <div class="col s4">
                                    <p class="collections-title">Name</p>
                                    <p class="collections-title">Email</p>
                                    <p class="collections-title">Mobile No</p>
                                </div>
                                <div class="col s3">
                                    <p class="collections-title">Date Time</p>
                                    <!-- <p class="collections-title">Time</p> -->
                                    <!--
                                                <p class="collections-title">Added By</p>
                                                <p class="collections-title">Officer</p>
                                              -->
                                </div>
                                <div class="col s2">
                                    <p class="collections-title">Status</p>
                                </div>
                            </div>
                        </li>
                        @if(count($appointments)>0)
                        <li class="collection-item">
                            @foreach($appointments as $appoint)
                            @if($appoint->app_status =='Pending' && $appoint->app_status =='' )
                            <?php
                            $temp = explode('T', $appoint->visite_time);
                            ?>
                            <div class="row">
                                <div class="col s3">
                                    <p class="collections-content" style="font-size: 18px;">#{{@$appoint->refer_code}}</p>
                                </div>
                                <div class="col s4">
                                    <p class="collections-content" style="font-size: 18px;">{{@$appoint->name}}</p>
                                    <p class="collections-content">{{@$appoint->email}}</p>
                                    <p class="collections-content">{{$appoint->mobile}}</p>
                                </div>
                                <div class="col s3">
                                    <!-- <p class="collections-content">{{@$appoint->parentDetail->name}}</p>
                                                  <p class="collections-content">{{@@$appoint->OfficerDetail->name}}</p> -->
                                    <p class="collections-content">{{@$appoint->created_at}}</p>
                                </div>
                                <div class="col s2">
                                    @if(@$appoint->app_status =='Pending' || @$appoint->app_status =='' )
                                    <strong style="color:red;">Pending</strong>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif
                            @endforeach
                        </li>
                        @else
                        <p> No appointment Available !!</p>
                        @endif
                    </ul>
                </div>
                <div class="col s12 m12 l6">
                    <ul id="projects-collection" class="collection">
                        <li class="collection-item avatar">
                            <i class="mdi-file-folder circle light-blue darken-2"></i>
                            <span class="collection-header">Appointment Complete</span>
                            <p>Latest Appointment List</p>
                            
                        </li>
                        <li class="collection-item">
                            <div class="row">
                                <div class="col s3">
                                    <p class="collections-title">Visitor Id</p>
                                </div>
                                <div class="col s4">
                                    <p class="collections-title">Name</p>
                                    <p class="collections-title">Email</p>
                                    <p class="collections-title">Mobile No</p>
                                </div>
                                <div class="col s3">
                                    <p class="collections-title">Date Time</p>
                                    <!-- <p class="collections-title">Time</p> -->
                                    <!--
                                                <p class="collections-title">Added By</p>
                                                <p class="collections-title">Officer</p>
                                              -->
                                </div>
                                <div class="col s2">
                                    <p class="collections-title">Status</p>
                                </div>
                            </div>
                        </li>
                        @if(count($appointments)>0)
                        <li class="collection-item">
                            @foreach($appointments as $appoint)
                            @if($appoint->app_status !='Pending' && $appoint->app_status !='' )
                            <?php
                            $temp = explode('T', $appoint->visite_time);
                            ?>
                            <div class="row">
                                <div class="col s3">
                                    <p class="collections-content" style="font-size: 18px;">#{{@$appoint->refer_code}}</p>
                                </div>
                                <div class="col s4">
                                    <p class="collections-content" style="font-size: 18px;">{{@$appoint->name}}</p>
                                    <p class="collections-content">{{@$appoint->email}}</p>
                                    <p class="collections-content">{{$appoint->mobile}}</p>
                                </div>
                                <div class="col s3">
                                    <!-- <p class="collections-content">{{@$appoint->parentDetail->name}}</p>
                                                <p class="collections-content">{{@@$appoint->OfficerDetail->name}}</p> -->
                                    <p class="collections-content">{{@$appoint->created_at}}</p>
                                </div>
                                <div class="col s2">
                                    @if(@$appoint->app_status =='Approve' || @$appoint->app_status =='Approval')
                                    <strong style="color:green;">Approved</strong>
                                    @elseif(@$appoint->app_status =='Blocked')
                                    <strong style="color:#9c27b0;">Blocked</strong>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif

                            @endforeach
                        </li>
                        @else
                        <p> No appointment Available !!</p>
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>
    
    @endif
    <!--work collections end-->
    </div>
    <!--end container-->
</section>
</div>
<!-- END WRAPPER -->
</div>
<!-- START FOOTER -->
<footer class="page-footer">
    
    <div class="footer-copyright">
        <div class="container"> Copyright © 2021. All rights reserved.
        </div>
    </div>
</footer>
<!-- END FOOTER -->

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>


<script type="text/javascript">
    $('#sos-btn').click(function() {
        $.ajax({
            url: "{{route('admin.send.sos.alert')}}",
            type: "post",
            data: {
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                if (result.status == "failed") {
                    $('#mobile_error').html(result.message);
                }
            }
        });
    });
    $(function() {
        $('input[name="daterange"]').daterangepicker();
    });
    $('#rangerange').hide();
    function showDateRange(val){
        if(val=="custom"){
            $('#rangerange').show();
        }else{
            $('#rangerange').hide();
        }
    }

    google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart);
        function drawChart() 
        {
        var data = google.visualization.arrayToDataTable(
        <?php  echo $encoded_data; ?>
        );
        var options = {
        title: "Visitor Report"
        };
        var chart = new google.visualization.PieChart(document.getElementById("employee_piechart"));
        chart.draw(data, options);
        }
</script>
@endsection