<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Leaves as LE;

class Leaves extends Component
{
    public $leave, $le_id, $date,$emp_id,$l_type;
    public $insertMode = false;
    public $l_types = ['EL'=>'Earn Leave','CL'=>'Casual Leave'];

    public function render()
    {
        $this->leave = LE::all();
        return view('livewire.leave.index');
    }

    private function resetInputFields(){
        $this->emp_id = '';
        $this->date = '';
        $this->l_type = '';
    }

    public function store()
    {
        $validatedDate = $this->validate([
            'emp_id' => 'required',
            'date' => 'required',
            'l_type' => 'required',
        ]);
  
        LE::create($validatedDate);
  
        session()->flash('message', 'Leave Added Successfully.');
        $this->insertMode = false;
        $this->resetInputFields();
    }

    public function cancel()
    {
        $this->insertMode = false;        
        $this->resetInputFields();
    }

    public function add()
    {
        $this->resetInputFields();
        $this->insertMode = true;        
    }

    public function delete($id)
    {
        LE::find($id)->delete();
        session()->flash('message', 'Leave Deleted Successfully.');
    }
}
