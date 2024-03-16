<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Details</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form>
        <div class="card-body">
            <div class="form-group">
            <label for="emp_id">Emp ID : </label>
            <input type="text" class="form-control" id="emp_id" placeholder="Enter ID" wire:model="emp_id">
            @error('emp_id') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="l_type">Leave Type</label>
                <select class="custom-select rounded-0" id="l_type" wire:model="l_type">
                    @foreach($l_types as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach                
                </select>
                @error('l_type') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
            <label for="date">Date : </label>
            <input type="date" class="form-control" id="date" placeholder="Enter Date" wire:model="date">
            @error('date') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
           
            
            
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" wire:click.prevent="store()" class="btn btn-primary">Create</button>
            <button type="submit" wire:click.prevent="cancel()" class="btn btn-primary">Cancel</button>
        </div>
    </form>
</div>