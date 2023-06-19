<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\App;
use Livewire\Component;

use App\Models\Employee as Emp;
use App\Models\Designation as Desig;

class Employee extends Component
{

    public $employees, $emp_id, $full_name,$attendance_machine_id,$desgination,$status,$updateMode=false,$insertMode=false;

    public $emp_status = ['1'=>'Active','0'=>'In-Active'];
    public $emp_designations;

    public function render()
    {
        $this->emp_designations = Desig::all();
        $this->employees = Emp::all();
        return view('livewire.employee.employee');
    }

    private function resetInputFields(){
        $this->full_name = '';
        $this->attendance_machine_id = '';
        $this->desgination = '';
        $this->status = '';

    }
 

    public function cancel()
    {        
        $this->insertMode = false;        
        $this->updateMode = false;        
        $this->resetInputFields();
    }

    public function add()
    {
        $this->insertMode = true;        
        $this->resetInputFields();
    }

    public function store()
    {
        
        $validatedDate = $this->validate([
            'full_name' => 'required',
            'attendance_machine_id' => 'required',
            'desgination' => 'required',
            'status' => 'required',
        ]);
  
        Emp::create($validatedDate);
  
        session()->flash('message', 'Employee Created Successfully.');
        $this->insertMode = false;
        $this->resetInputFields();

    }

    public function edit($id)
    {
        $emp = Emp::findOrFail($id);
        $this->emp_id = $id;
        $this->full_name = $emp->full_name;
        $this->attendance_machine_id = $emp->attendance_machine_id;
        $this->desgination = $emp->desgination;
        $this->status = $emp->status;
  
        $this->updateMode = true;
        $this->insertMode = false;
    }

    public function update()
    {
        $validatedDate = $this->validate([
            'full_name' => 'required',
            'attendance_machine_id' => 'required',
            'desgination' => 'required',
            'status' => 'required',
        ]);
  
        $emp = emp::find($this->emp_id);
        $emp->update([
            'emp_id'=>$this->emp_id,
            'full_name'=>$this->full_name,
            'attendance_machine_id'=>$this->attendance_machine_id,
            'desgination'=>$this->desgination,
            'status'=>$this->status,
        ]);
  
        $this->updateMode = false;
  
        session()->flash('message', 'Emp Updated Successfully.');
        $this->resetInputFields();
    }

    
}
