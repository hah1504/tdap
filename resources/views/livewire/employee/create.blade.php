<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Details</h3>
    </div>

    <form>
        <div class="card-body">
            <div class="form-group">
                <label for="full_name">Emp Name : </label>
                <input type="text" class="form-control" id="full_name" placeholder="Enter Name" wire:model="full_name">
                @error('full_name') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="attendance_machine_id">Att Machine ID : </label>
                <input type="Number" step="1" class="form-control" id="attendance_machine_id" placeholder="Enter Att Machine Id" wire:model="attendance_machine_id">
            </div>
            @error('attendance_machine_id') <span class="text-danger">{{ $message }}</span>@enderror
            <div class="form-group">
                <label for="desgination">Desgination</label>
                <select class="custom-select rounded-0" id="desgination" wire:model="desgination">
                <option value="">Select</option>
                    @foreach($emp_designations as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach  
                </select>
                @error('desgination') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="custom-select rounded-0" id="status" wire:model="status">
                    @foreach($emp_status as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach                
                </select>
                @error('status') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
                <button type="submit" wire:click.prevent="store()" class="btn btn-primary">Create</button>
                <button type="submit" wire:click.prevent="cancel()" class="btn btn-primary">Cancel</button>
        </div>
    </form>
</div>