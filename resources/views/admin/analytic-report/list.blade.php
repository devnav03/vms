@extends('admin.layout.master')
@section('title','Admin :: Analytic Report')
@section('head')
<div class="page-head">
    <div class="row">
        <div class="col-md-4">
            <h3 class="m-b-less">
            Analytic Report
            </h3>
        </div>
       {{Form::open(['route'=>'admin.'.request()->segment(2).'.index', 'class'=>'form-inline'])}}
        <div class="col-md-8">
            <div class="col-md-3">
                <label>Date From</label>
                <div class="form-group">
                    <input type="date" id="from_date" value="{{@$from_date}}" name="date_from" class="form-control" />
                </div>
            </div>
            <div class="col-md-3">
                <label>Date To</label>
                <div class="form-group">
                    <input autocomplete="off" value="{{@$to_date}}" type="date" name="date_to" class="form-control"/>
                </div>
            </div>
            <div class="col-md-2" style="margin-top: 20px">
                <div class="clearfix">
                    <button type="submit" name="search" id="searchBtn" value="Search" class="btn btn-primary pull-right">Search</button>
                </div>
            </div>
        </div>
        {{Form::close()}}
    </div>
</div>
@endsection
@section('content')
<div class="col-md-12 mt-3">
    <h3>Receiving Detail</h3>
    <table class="table bio-table table-bordered">
        <thead>
            <th>S. No.</th>
            <th>Name</th>
            <th>Plan Amount</th>
            <th>Total Ids</th>
            <th>Total Amount</th>
        </thead>
        <tbody>
            @foreach(@$activation_detail as $key=>$activation_plan)
            <tr>                
                <td>{{++$key}}</td>
                <td>{{@$activation_plan->name}}</td>
                <td>{{@$activation_plan->amount}}</td>
                <td>{{@$activation_plan->total_ids}}</td>
                <td>{{@$activation_plan->total_amount}}</td>
            </tr>
            @endforeach
           
            @if(count(@$activation_detail) == 0)
            <tr>
                <td colspan="4">
                    <p class="text-center">No Record Found!</p>
                </td>
            </tr>
            @endif
        </tbody>
        <tfoot>
        <tr>
            
            <th colspan="4">Net Turnover: <i class="fa fa-inr"></i></th>
            <th colspan="1"> {{@$total_turnover}}</th>
        </tr>
        </tfoot>
    </table>
</div>

<div class="col-md-12 mt-3">
    <div class="col-md-12">
        <h3>Distribution Detail</h3>        
    </div>
    <table class="table bio-table table-bordered">
        <thead>
            <th>S. No.</th>
            <th>Distribution Name</th>
            <th>Distribution Amount</th>
            <th>Distribution/Turnover Ratio</th>
        </thead>
        <tbody>
            @foreach(@$all_distribution as $key=>$distribution)
            <tr>                
                <td>{{++$key}}</td>
                <td>{{@$distribution->distribution_name}}</td>
                <td>{{round(@$distribution->distribution_amount, 2)}}</td>
                <td>{{@$distribution->distribution_percent}}</td>
            </tr>
            @endforeach
           
            @if(count(@$all_distribution) == 0)
            <tr>
                <td colspan="4">
                    <p class="text-center">No Record Found!</p>
                </td>
            </tr>
            @endif
        </tbody>
        <tfoot>
        <tr>
            
            <th colspan="2">Net Distribution : <i class="fa fa-inr"></i></th>
            <th colspan="1"></i> {{@$total_distribution}}</th>
        </tr>
        </tfoot>
    </table>
</div>

@endsection
@push('scripts')
    <script type="text/javascript">
    </script>
@endpush