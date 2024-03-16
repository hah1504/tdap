<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Holidays as HD;

class Holidays extends Component
{
    public $holiday, $hd_id, $date,$name;
    public $insertMode = false;

    public function render()
    {
        $this->holiday = HD::all();
        // dd(count($this->holiday));
        return view('livewire.holiday.index');
    }

    private function resetInputFields(){
        $this->name = '';
        $this->date = '';
    }

    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'date' => 'required',
        ]);
  
        HD::create($validatedDate);
  
        session()->flash('message', 'Holiday Added Successfully.');
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
        HD::find($id)->delete();
        session()->flash('message', 'Holiday Deleted Successfully.');
    }
}
