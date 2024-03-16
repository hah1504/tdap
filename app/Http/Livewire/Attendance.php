<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Attendance as att;

class Attendance extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $att_type = ['1'=>'OUT','0'=>'In'];
    private $attendances;
    public  $att_id, $user_id,$punch,$punch_type;
    public $updateMode = false;
    public $insertMode = false;

    public function render()
    {
        $this->attendances = att::orderby('id','desc')->paginate(50);
        return view('livewire.attendance.index',['attendances'=>$this->attendances]);
    }

    private function resetInputFields(){
        $this->user_id = '';
        $this->punch = '';
        $this->punch_type = '';
    }

    public function store()
    {
        $validatedDate = $this->validate([
            'user_id' => 'required',
            'punch' => 'required',
            'punch_type' => 'required',
        ]);
  
        $validatedDate['machine_id'] = -1;
        $validatedDate['punch_state'] = -1;
        $validatedDate['uid'] = -1;
        // dd($validatedDate);
        att::create($validatedDate);
  
        session()->flash('message', 'Attendance Created Successfully.');
        $this->insertMode = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $att = att::findOrFail($id);
        $this->att_id = $id;
        $this->user_id = $att->user_id;
        $this->punch = $att->punch;
        $this->punch_type = $att->punch_type;
  
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
            'user_id' => 'required',
            'punch' => 'required',
            'punch_type' => 'required',
        ]);
  
        $att = att::find($this->att_id);
        $att->update([
            'user_id' => $this->user_id,
            'punch' => $this->punch,
            'punch_type' => $this->punch_type,
        
        ]);
  
        $this->updateMode = false;
        $this->insertMode = false;
  
        session()->flash('message', 'Attendance Updated Successfully.');
        $this->resetInputFields();
    }
}
