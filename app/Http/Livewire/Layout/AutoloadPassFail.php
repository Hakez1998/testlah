<?php

namespace App\Http\Livewire\Layout;

use Livewire\Component;
use App\Models\TestResult;
use App\Models\TestStep;

class AutoloadPassFail extends Component
{   
    public $test_step_id, $result, $form;

    protected $listeners = ['checkSubmission'];

    public function render()
    {
        return view('livewire.layout.autoload-pass-fail');
    }

    public function mount()
    {   
        if(session('page') == 'submit')
            $this->form = false;
        else
            $this->form = true;

        $this->result = TestResult::where('test_step_execution_id', $this->test_step_id)->first()->is_success;
        if($this->result == "")
        {
            $this->result = "null";
        }
        
    }

    public function checkSubmission()
    {
        dd('lol');
    }

    public function testCaseResult($id, $button_id, $result)
    {   
        $test = TestResult::firstWhere('test_step_execution_id', $this->test_step_id);
        $test->is_success = $result;
        $test->save();

        $this->result = TestResult::where('test_step_execution_id', $this->test_step_id)->first()->is_success;

        $this->emitTo('page.tester-dashboard', 'checkResult');
    }

}
