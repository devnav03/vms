@extends('admin.layout.master')

@section('title','Admin :: Building List')

@section('head')

<div class="page-head">

    <div class="row">

        <div class="col-md-4">

            <h3 class="m-b-less">Building List</h3>

        </div>

        <div class="col-md-4"></div>

        <div class="col-md-4"></div>

    </div>

</div>

@endsection

@section('content')

    <section id="content">

        <!--breadcrumbs start-->

        <div id="breadcrumbs-wrapper" class=" grey lighten-3">

          <div class="container">

            <div class="row">

              <div class="col s12 m12 l12">

                <h5 class="breadcrumbs-title">Building List</h5>

                <ol class="breadcrumb">

                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>

                  </li>

                  <li><a>Buildings</a>

                  </li>

                </ol>

              </div>

            </div>

          </div>

        </div>

    </section>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

    <div class='col-md-12'>

    </div>

    <div class="clearfix"></div>

    <div class="col-md-12" style="padding: 15px">

        <a class="btn btn-primary btn-sm" href="{{ adminRoute('create')}}" style="float: right; margin-top: 10px;">Add New</a>

        <div class="table-responsive">

            <table class="display dataTableAjax user-list-table" id="data-table-simple_wrapper" width="100%">
                <thead>
                <tr>
                    <th>S.no.</th>
                    <th>Field Name</th>
					<th>Status</th>
                </tr>
                </thead>

                <tbody>  

                    <tr>
                    <td>1</td>
                    <td>Name</td>
      				<td> @if($data->name == 1) <a class="active_f" href="{{ route('input-field', 'name') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'name') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>2</td>
                    <td>Email</td>
                    <td> @if($data->email == 1) <a class="active_f" href="{{ route('input-field', 'email') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'email') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>3</td>
                    <td>Gender</td>
                    <td> @if($data->gender == 1) <a class="active_f" href="{{ route('input-field', 'gender') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'gender') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>4</td>
                    <td>Mobile</td>
                    <td> @if($data->mobile == 1) <a class="active_f" href="{{ route('input-field', 'mobile') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'mobile') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>5</td>
                    <td>Profile Image</td>
                    <td> @if($data->profile_image == 1) <a class="active_f" href="{{ route('input-field', 'profile_image') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'profile_image') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>6</td>
                    <td>Date Of Birth</td>
                    <td> @if($data->dob == 1) <a class="active_f" href="{{ route('input-field', 'dob') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'dob') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>7</td>
                    <td>Nationality</td>
                    <td> @if($data->nationality == 1) <a class="active_f" href="{{ route('input-field', 'nationality') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'nationality') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>8</td>
                    <td>State</td>
                    <td> @if($data->state == 1) <a class="active_f" href="{{ route('input-field', 'state') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'state') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>9</td>
                    <td>City</td>
                    <td> @if($data->city == 1) <a class="active_f" href="{{ route('input-field', 'city') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'city') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>10</td>
                    <td>Address</td>
                    <td> @if($data->address == 1) <a class="active_f" href="{{ route('input-field', 'address') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'address') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>11</td>
                    <td>Pincode</td>
                    <td> @if($data->pincode == 1) <a class="active_f" href="{{ route('input-field', 'pincode') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'pincode') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>12</td>
                    <td>Business Name</td>
                    <td> @if($data->business_name == 1) <a class="active_f" href="{{ route('input-field', 'business_name') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'business_name') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>13</td>
                    <td>Firm Address</td>
                    <td> @if($data->firm_address == 1) <a class="active_f" href="{{ route('input-field', 'firm_address') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'firm_address') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>14</td>
                    <td>Firm Email</td>
                    <td> @if($data->name == 1) <a class="active_f" href="{{ route('input-field', 'firm_email') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'firm_email') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>15</td>
                    <td>Firm Contact No</td>
                    <td> @if($data->firm_contact == 1) <a class="active_f" href="{{ route('input-field', 'firm_contact') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'firm_contact') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>16</td>
                    <td>Firm Pincode</td>
                    <td> @if($data->firm_pincode == 1) <a class="active_f" href="{{ route('input-field', 'firm_pincode') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'firm_pincode') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>17</td>
                    <td>Firm ID</td>
                    <td> @if($data->firm_id == 1) <a class="active_f" href="{{ route('input-field', 'firm_id') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'firm_id') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>18</td>
                    <td>Signature Image</td>
                    <td> @if($data->signature_id == 1) <a class="active_f" href="{{ route('input-field', 'signature_id') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'signature_id') }}"> Inactive </a> @endif </td>
                    </tr> 

                    <tr>
                    <td>19</td>
                    <td>Document Type</td>
                    <td> @if($data->document_type == 1) <a class="active_f" href="{{ route('input-field', 'document_type') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'document_type') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>20</td>
                    <td>Document Image</td>
                    <td> @if($data->document_id == 1) <a class="active_f" href="{{ route('input-field', 'document_id') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'document_id') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>21</td>
                    <td>Document Number</td>
                    <td> @if($data->document_number == 1) <a class="active_f" href="{{ route('input-field', 'document_number') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'document_number') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>22</td>
                    <td>Location</td>
                    <td> @if($data->meet_region == 1) <a class="active_f" href="{{ route('input-field', 'meet_region') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'meet_region') }}"> Inactive </a> @endif </td>
                    </tr>

       <!--              <tr>
                    <td>23</td>
                    <td>State</td>
                    <td> @if($data->meet_state == 1) <a class="active_f" href="{{ route('input-field', 'meet_state') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'meet_state') }}"> Inactive </a> @endif </td>
                    </tr> -->

        <!--             <tr>
                    <td>24</td>
                    <td>City or postal code</td>
                    <td> @if($data->meet_city == 1) <a class="active_f" href="{{ route('input-field', 'meet_city') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'meet_city') }}"> Inactive </a> @endif </td>
                    </tr> -->

                    <tr>
                    <td>23</td>
                    <td>Address / Building number</td>
                    <td> @if($data->meet_address == 1) <a class="active_f" href="{{ route('input-field', 'meet_address') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'meet_address') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>24</td>
                    <td>Person / Officer name</td>
                    <td> @if($data->meet_person == 1) <a class="active_f" href="{{ route('input-field', 'meet_person') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'meet_person') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>25</td>
                    <td>Department</td>
                    <td> @if($data->meet_department == 1) <a class="active_f" href="{{ route('input-field', 'meet_department') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'meet_department') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>26</td>
                    <td>Purpose</td>
                    <td> @if($data->visit_purpose == 1) <a class="active_f" href="{{ route('input-field', 'visit_purpose') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'visit_purpose') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>27</td>
                    <td>Meeting Time / Duration</td>
                    <td> @if($data->duration == 1) <a class="active_f" href="{{ route('input-field', 'duration') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'duration') }}"> Inactive </a> @endif </td>
                    </tr>

                    <tr>
                    <td>28</td>
                    <td>Date time schduler</td>
                    <td> @if($data->schduler == 1) <a class="active_f" href="{{ route('input-field', 'schduler') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'schduler') }}"> Inactive </a> @endif </td>
                    </tr>
          
                    <tr>
                    <td>29</td>
                    <td>Topic</td>
                    <td> @if($data->topic == 1) <a class="active_f" href="{{ route('input-field', 'topic') }}"> Active </a> @else <a class="inactive_f" href="{{ route('input-field', 'topic') }}"> Inactive </a> @endif </td>
                    </tr>
           
                </tbody>

            </table>

            </div>

        </div>

@endsection

<style type="text/css">
    
.active_f{
    background: green;
    color: #fff;
    padding: 5px 17px;
    font-size: 12px;
    border-radius: 6px;
}
.inactive_f{
    background: red;
    color: #fff;
    padding: 5px 17px;
    font-size: 12px;
    border-radius: 6px;
}

</style>


@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script> -->

    <script type="text/javascript">

       $(document).ready(function() {

            $('#data-table-simple_wrapper').DataTable();

    } );

    var doc = new jsPDF();
        var specialElementHandlers = {
            '#editor': function (element, renderer) {
                return true;
            }
        };

        $('#cmd13').click(function () {
            doc.fromHTML($('#qr_model_13').html(), 15, 15, {
                'width': 170,
                    'elementHandlers': specialElementHandlers
            });
            doc.save('sample-file.pdf');
        });

    </script>

@endpush
