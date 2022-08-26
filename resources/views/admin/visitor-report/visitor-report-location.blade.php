@extends('admin.layout.master')
@section('title','Admin :: Visitor History')
@section('head')
@endsection
@section('content')
<style media="screen">
    span.badge.new:after {
      content: " " !important;
    }
  .tree, .tree ul {
    margin:0;
    padding:0;
    list-style:none
  }
  .tree ul {
    margin-left:1em;
    position:relative
  }
  .tree ul ul {
    margin-left:.5em
  }
  .tree ul:before {
    content:"";
    display:block;
    width:0;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    border-left:1px solid
  }
  .tree li {
    margin:0;
    padding:0 1em;
    line-height:2em;
    color:#369;
    font-weight:700;
    position:relative
  }
  .tree ul li:before {
    content:"";
    display:block;
    width:10px;
    height:0;
    border-top:1px solid;
    margin-top:-1px;
    position:absolute;
    top:1em;
    left:0
  }
  .tree ul li:last-child:before {
    background:#fff;
    height:auto;
    top:1em;
    bottom:0
  }
  .indicator {
    margin-right:5px;
  }
  .tree li a {
    text-decoration: none;
    color:#369;
  }
  .tree li button, .tree li button:active, .tree li button:focus {
    text-decoration: none;
    color:#369;
    border:none;
    background:transparent;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    outline: 0;
  }
</style>
    <section id="content">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> Real Time Visitor</h5>
                <ol class="breadcrumb">
                  <li><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                  </li>
                  <li><a href="#">Real Time Visitor</a>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
    </section>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <div class="col s12">
      <div class="transaction_summary">
        <div class="container" style="margin-top:30px;">
          <div class="row">
            <div class="col s6">
                <ul id="tree1">
                  <li ><a href="#">{{json_decode(Session::get('CIDATA'))->name}}</a>
                    <ul>
                      @foreach($datas as $data)
                          <li style="display: none;">{{$data['name']}}
                            <ul>
                            @foreach($data['building'] as $building)
                                <li style="display: none;">{{$building['name']}}
                                  <ul>
                                  @foreach($building['department'] as $department)
                                  <li style="display: none;">{{$department['name']}} &nbsp;&nbsp;&nbsp;&nbsp; <span style="color:red;">{{count($department['user'])}} Visitor</span></li>
                                  @endforeach
                                  </ul>
                                </li>
                            @endforeach
                            </ul>
                          </li>
                      @endforeach
                    </ul>
                  </li>
                </ul>
            </div>
            <div class="col s6">
              <span>Mark Out Visitor Select Date</span>
              <input type="date" name="pickdate" id="pickdate" class="form-control">
              <button class="btn btn-primary" onclick="chackMarkVisitor();">Check Visitor Mark In</button>
              <span id="markin_total"></span>
              <button class="btn btn-primary" onclick="markoutOnDate();">Mark Out</button>
            </div>
        </div>
      </div>
    </div>
  </div>
    {{-- <div class="clearfix"></div> --}}
    <div class="col s12" style="padding: 0px 15px 0px 15px">

    </div>
@endsection
@push('scripts')




<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script> -->

   <script type="text/javascript">

   $(document).ready(function() {
    $('#example').DataTable();
} );
$.fn.extend({
  treed: function (o) {

    var  closedClass= 'mdi-action-subject';
    var openedClass = 'mdi-image-grid-on';

    if (typeof o != 'undefined'){
      if (typeof o.openedClass != 'undefined'){
      openedClass = o.openedClass;
      }
      if (typeof o.closedClass != 'undefined'){
      closedClass = o.closedClass;
      }
    };

      //initialize each of the top levels
      var tree = $(this);
      tree.addClass("tree");
      tree.find('li').has("ul").each(function () {
          var branch = $(this); //li with children ul
          branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
          branch.addClass('branch');
          branch.on('click', function (e) {
              if (this == e.target) {
                  var icon = $(this).children('i:first');
                  icon.toggleClass(openedClass + " " + closedClass);
                  $(this).children().children().toggle();
              }
          })
          branch.children().children().toggle();
      });
      //fire event from the dynamically added icon
    tree.find('.branch .indicator').each(function(){
      $(this).on('click', function () {
          $(this).closest('li').click();
      });
    });
      //fire event to open branch if the li contains an anchor instead of text
      tree.find('.branch>a').each(function () {
          $(this).on('click', function (e) {
              $(this).closest('li').click();
              e.preventDefault();
          });
      });
      //fire event to open branch if the li contains a button instead of text
      tree.find('.branch>button').each(function () {
          $(this).on('click', function (e) {
              $(this).closest('li').click();
              e.preventDefault();
          });
      });
  }
});

//Initialization of treeviews

$('#tree1').treed();
function chackMarkVisitor(){
  var date=$('#pickdate').val();
  $.ajax({
         url:"{{route('admin.get.markInVisitor')}}",
         type: "POST",
         data: {
          date: date,
         _token: '{{csrf_token()}}'
         },
         dataType : 'json',
         success: function(result){
           $('#markin_total').html("Total Mark in visitor  "+result);
         }
     });
}

function markoutOnDate(){ 
  var date=$('#pickdate').val();
  $.ajax({
      url:"{{route('admin.markoutvisitor')}}",
      type: "POST",
      data: {
        date: date,
      _token: '{{csrf_token()}}'
      },
      dataType : 'json',
      success: function(result){
        if(result.status=="false"){
          alert('No visitor mark in please select another date')
        }else{
          $('#markin_total').html("Total Mark in visitor  0");
        alert('All visitor mark out successfully')
        }
        
      }
  });
}
</script>

@endpush
