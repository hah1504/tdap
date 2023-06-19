<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Details</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form>
        <div class="card-body">
            <div class="form-group">
            <label for="name">Name : </label>
            <input type="text" class="form-control" id="name" placeholder="Enter Name" wire:model="name">
            @error('name') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
            <label for="ip">IP : </label>
            <input type="text" class="form-control" id="ip" placeholder="Enter Name" wire:model="ip">
            @error('ip') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
            <label for="port">Port</label>
            <input type="Number" step="1" class="form-control" id="port" placeholder="Enter Att Machine Id" wire:model="port">
            @error('port') <span class="text-danger">{{ $message }}</span>@enderror
            </div>
            
            
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" wire:click.prevent="store()" class="btn btn-primary">Create</button>
            <button type="submit" wire:click.prevent="cancel()" class="btn btn-primary">Cancel</button>
        </div>
    </form>
</div>