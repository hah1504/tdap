@extends('layouts.main')

@section('title') Attendance Log @endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Attendance Log</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Attendance Log</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-12">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            </div>
        </div>
        
        <div class="row">
            <table class="table table-bordered mt-5">
            <thead>
                <tr>
                    <th>Att Machine UUID</th>
                    <th>User Id</th>
                    <th>State</th>
                    <th>Time</th>
                    <th>Punch Type</th>
                </tr>
            </thead>
            <tbody>
            
                @foreach($atts as $row)
                <tr>
                    <td>{{ $row['uid'] }}</td>
                    <td>{{ $row['id'] }}</td>
                    <td>{{ $row['state'] }}</td>
                    <td>{{ $row['timestamp'] }}</td>
                    <td>{{ $row['type'] }}</td>                    
                </tr>
                @endforeach

            </tbody>
            </table>
        </div>

        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
    </div>

</div>
@endsection