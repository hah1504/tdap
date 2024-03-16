@extends('layouts.main')

@section('title') Single Summary Report @endsection

@section('content')
@php
  $emp_designations = App\Models\Designation::all();  
@endphp

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Single Summary Report</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Single Summary Report</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Filters</h3>
        </div>

        <form action="{{ route('report.searchSingleSummaryReport') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="date_range">Date Range : </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                        </div>
                        <input type="text" class="form-control float-right" id="date_range" name="date_range">
                    </div>
                </div>
                <div class="form-group">
                  <label for="desgination">Desgination</label>
                  <select class="custom-select rounded-0" id="desgination" name="desgination">
                  <option value="">Select</option>
                      @foreach($emp_designations as $row)
                          <option value="{{$row->id}}">{{$row->name}}</option>
                      @endforeach  
                  </select>
              </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Search</button>                
        </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>

$(document).ready(function(){
    $('#date_range').daterangepicker({
        // startDate:new Date(), // after open picker you'll see this dates as picked
        // endDate:new Date(),
        locale: {
        format: 'YYYY-MM-DD',
        },
       
    });
});


</script>
@endsection