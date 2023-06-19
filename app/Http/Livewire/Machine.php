<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\att_machine as Mach;

class Machine extends Component
{

    public $machines, $mach_id, $name,$created_by,$modified_by,$ip,$port;
    public $updateMode = false;
    public $insertMode = false;

    public function render()
    {
        $this->machines = Mach::all();
        // dd(count($this->machines));
        return view('livewire.machine.index');
    }

    private function resetInputFields(){
        $this->name = '';
        $this->mach_id = '';
        $this->created_by = '';
        $this->modified_by = '';
        $this->ip = '';
        $this->port = '';
    }

    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'ip' => 'required',
            'port' => 'required',
        ]);
  
        Mach::create($validatedDate);
  
        session()->flash('message', 'Machine Created Successfully.');
        $this->insertMode = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $mach = Mach::findOrFail($id);
        $this->mach_id = $id;
        $this->name = $mach->name;
        $this->ip = $mach->ip;
        $this->port = $mach->port;
  
        $this->updateMode = true;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->insertMode = false;        
        $this->resetInputFields();
    }

    public function add()
    {
        $this->resetInputFields();
        $this->insertMode = true;        
    }

    public function update()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'ip' => 'required',
            'port' => 'required',
        ]);
  
        $mach = Mach::find($this->mach_id);
        $mach->update([
            'name' => $this->name,
            'ip' => $this->ip,
            'port' => $this->port,
        
        ]);
  
        $this->updateMode = false;
        $this->insertMode = false;
  
        session()->flash('message', 'machine Updated Successfully.');
        $this->resetInputFields();
    }
}
