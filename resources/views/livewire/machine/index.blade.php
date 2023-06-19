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
            @include('livewire.machine.create')              
          </div>
      </div>
      @endif

      @if($updateMode)
      <div class="row">
          <div class="col-md-12">        
            @include('livewire.machine.update')         
          </div>
      </div>
      @endif

        
        

      <div class="row">
        <div class="col-md-12">
          <button wire:click="add()" class="btn btn-block bg-gradient-primary btn-lg">Add Machine</button>
        </div>
        <!-- /.col -->
        
      </div>
      <!-- /.row -->

      <div class="row">
        <table class="table table-bordered mt-5">
          <thead>
              <tr>
                  <th>Att Machine ID</th>
                  <th>Name</th>
                  <th>IP</th>
                  <th>Port</th>
                  <th width="150px">Action</th>
              </tr>
          </thead>
          <tbody>
          
            @foreach($machines as $mach)
              <tr>
                <td>{{ $mach->id }}</td>
                <td>{{ $mach->name }}</td>
                <td>{{ $mach->ip }}</td>
                <td>{{ $mach->port }}</td>
                <td>
                    <button wire:click="edit({{ $mach->id }})" class="btn btn-primary btn-sm">Edit</button>
                    <!-- <button class="btn btn-warning btn-sm"> -->
                      <a target="_blank" class="btn btn-warning btn-sm" href="{{route('machine.extractAttendance',['machine_id'=>$mach->id])}}">Extract Attendance</a>
                    <!-- </button> -->
                </td>
              </tr>
              @endforeach

          </tbody>
        </table>
      </div>

    </div><!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>