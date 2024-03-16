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
      @if($insertMode)
      <div class="row">
          <div class="col-md-12">        
            @include('livewire.attendance.create')              
          </div>
      </div>
      @endif

      @if($updateMode)
      <div class="row">
          <div class="col-md-12">        
            @include('livewire.attendance.update')         
          </div>
      </div>
      @endif

        
        

      <div class="row">
        <div class="col-md-12">
          <button wire:click="add()" class="btn btn-block bg-gradient-primary btn-lg">Add Attendance</button>
        </div>
        <!-- /.col -->
        
      </div>
      <!-- /.row -->

      <div class="row">
        <table class="table table-bordered mt-5">
          <thead>
              <tr>
                  <th>User Id</th>
                  <th>Punch</th>
                  <th>punch_type</th>
                  <th width="150px">Action</th>
              </tr>
          </thead>
          <tbody>
          
          @foreach($attendances as $att)
            <tr>
              <td>{{ $att->user_id }}</td>
              <td>{{ $att->punch }}</td>
              <td>{{ ($att->punch_type == 0)? 'IN':'OUT' }}</td>
              <td>
                  <button wire:click="edit({{ $att->id }})" class="btn btn-primary btn-sm">Edit</button>
                  
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        {{ $attendances->links() }}
      </div>

    </div><!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>