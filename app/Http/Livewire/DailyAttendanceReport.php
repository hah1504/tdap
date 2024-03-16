<?php

namespace App\Http\Livewire;

use Livewire\Component;

class dailyAttendanceReport extends Component
{
    public function render()
    {
        return view('livewire.report.daily-summary-report');
    }
}