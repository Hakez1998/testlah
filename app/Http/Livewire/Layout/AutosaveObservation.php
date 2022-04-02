<?php

namespace App\Http\Livewire\Layout;

use Livewire\Component;
use App\Models\TestResult;
use App\Models\TestStep;

class AutosaveObservation extends Component
{
    public $test_step_id, $observation, $form;

    public function render()
    {
        return view('livewire.layout.autosave-observation');
    }

    public function mount()
    {
        if(session('page') == 'submit')
            $this->form = false;
        else
            $this->form = true;
            
        $this->observation = TestResult::where('test_step_execution_id', $this->test_step_id)->first()->observation;
    }

    public function updateObservation()
    {
        TestResult::where('test_step_execution_id', $this->test_step_id)->update(['observation' => $this->observation]);
    }
}
