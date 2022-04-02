<?php

namespace App\Http\Livewire\Layout;

use Livewire\Component;
use App\Models\TestStep;

class InputScript extends Component
{
    public $step, $result, $case_id;

    public function render()
    {
        return view('livewire.layout.input-script');
    }

    public function mount($id)
    {   
        $this->case_id = $id;
        $test_step = TestStep::find($id);
        $this->step = $test_step->title;
        $this->result = $test_step->expected_result;    
    }

    public function updateStep()
    {
        $test_step = TestStep::find($this->case_id);
        $test_step->title = $this->step;
        $test_step->save();

        $this->emitUp('flashMessage');
    }

    public function updateResult()
    {
        $test_step = TestStep::find($this->case_id);
        $test_step->expected_result = $this->result;
        $test_step->save();

        $this->emitUp('flashMessage');
    }

    public function deleteScript()
    {
        TestStep::find($this->case_id)->delete();
        $this->emitUp('remountCase');

        $this->emitUp('flashMessage');
    }
}
