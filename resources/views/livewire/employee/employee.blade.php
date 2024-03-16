<div>
<div class="row">
    <div class="col-md-12">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
</div>

@if($insertMode)
<div class="row">
    <div class="col-md-12">        
        @include('livewire.employee.create')                    
    </div>
</div>
@endif

@if($updateMode)
<div class="row">
    <div class="col-md-12">        
        @include('livewire.employee.update')                    
    </div>
</div>
@endif

@if(!$insertMode)
<div class="row">
    <div class="col-md-12">
        <button wire:click="add()" class="btn btn-block bg-gradient-primary btn-lg">Add Employee</button>
    </div>
</div>
@endif

<div class="row">
    <table class="table table-bordered mt-5">
        <thead>
            <tr>
                <th>Att Machine ID</th>
                <th>Emp Name</th>
                <th>Designation</th>
                <th width="150px">Action</th>
            </tr>
        </thead>
        <tbody>
        
            @foreach($employees as $emp)
                <tr>
                    <td>{{ $emp->attendance_machine_id }}</td>
                    <td>{{ $emp->full_name }}</td>
                    <td>{{ $emp->empDesignation }}</td>
                    <td>{{ $emp_status[$emp->status] }}</td>
                    <td>
                        <button  wire:click="edit({{ $emp->id }})"  class="btn btn-primary btn-sm">Edit</button>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
</div>
