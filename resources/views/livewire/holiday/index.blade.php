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
            @include('livewire.holiday.create')              
          </div>
      </div>
      @endif
        
        

      <div class="row">
        <div class="col-md-12">
          <button wire:click="add()" class="btn btn-block bg-gradient-primary btn-lg">Add Holiday</button>
        </div>
        <!-- /.col -->
        
      </div>
      <!-- /.row -->

      <div class="row">
        <table class="table table-bordered mt-5">
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Date</th>
                  <th width="150px">Action</th>
              </tr>
          </thead>
          <tbody>
          
            @foreach($holiday as $hd)
              <tr>
                <td>{{ $hd->name }}</td>
                <td>{{ $hd->date }}</td>
                <td>
                    <button wire:click="delete({{ $hd->id }})" class="btn btn-danger btn-sm">Delete</button>                    
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