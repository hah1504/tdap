@extends('layouts.main')

@section('title') Report @endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Report</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Report</li>
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

        <form action="{{ route('report.searchSingleAttendanceReport') }}" method="POST">
            @csrf
            <div class="card-body">
              <div class="form-group">
                      <label for="emp_id">Emp ID : </label>
                      <div class="input-group">
                          <div class="input-group-prepend">
                          <span class="input-group-text">
                              <i class="far fa-user"></i>
                          </span>
                          </div>
                          <input type="text" class="form-control float-right" id="emp_id" name="emp_id">
                      </div>
                  </div>
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