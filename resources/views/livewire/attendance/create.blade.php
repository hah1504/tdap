<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Details</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form>
        <div class="card-body">
            <div class="form-group">
            <label for="user_id">User ID : </label>
            <input type="text" class="form-control" id="user_id" placeholder="Enter User ID" wire:model="user_id">
            @error('user_id') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
            <label for="punch">Date Time : </label>
            <input type="text" class="form-control" id="punch" placeholder="Enter Date Time (Format YYYY-MM-DD H:M:S)" wire:model="punch">
            @error('punch') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="punch_type">Status</label>
                <select class="custom-select rounded-0" id="punch_type" wire:model="punch_type">
                    @foreach($att_type as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach                
                </select>
                @error('punch_type') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            
            
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" wire:click.prevent="store()" class="btn btn-primary">Create</button>
            <button type="submit" wire:click.prevent="cancel()" class="btn btn-primary">Cancel</button>
        </div>
    </form>
</div>



