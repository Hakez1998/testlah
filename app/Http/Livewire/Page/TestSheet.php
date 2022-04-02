<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use App\Models\TestExecution;
use App\Models\TestCaseExecution;
use App\Models\TestStepExecution;
use App\Models\Invitation;
use App\Models\TestSuite;
use App\Models\TestCase;
use App\Models\TestResult;
use Illuminate\Support\Arr;

class TestSheet extends Component
{
    public $invitation_id, $test_id, $totalPagination, $pagination, $test_steps, $test_cases;

    protected $listeners = ['remountTestContent'];

    public function render()
    {
        return view('livewire.page.test-sheet');
    }

    public function mount($invitation_id, $test_id)
    {   
        $this->invitation_id = $invitation_id;
        $this->test_execution_id = $test_id;
        $this->totalPagination = count(TestExecution::find($test_id)->testCaseExecution()->get());
        $this->pagination = 1;
        $this->paginationInitial();
    }

    public function getTestExecutionTitle()
    {
        return TestExecution::find($this->test_execution_id)->title;
    }

    public function getTestContent($test)
    {   
        $this->test_cases = TestCaseExecution::find($test->id);
        $this->test_steps = TestCaseExecution::find($test->id)->testStepExecution;
        
        foreach($this->test_steps as $step)
        {
            TestResult::firstOrCreate([
                'test_step_execution_id' => $step->id,
            ],
            [
                'is_success' => null,
                'observation' => ''
            ]);
        }
    }

    public function paginationInitial()
    {   
        $this->getTestContent(TestExecution::find($this->test_execution_id)->testCaseExecution[$this->pagination-1]);
    }

    public function paginationIncrease()
    {
        $pagination = $this->pagination;
        $pagination++;

        $this->pagination = $pagination;            
        
        $this->getTestContent(TestExecution::find($this->test_execution_id)->testCaseExecution[$this->pagination-1]);
    }

    public function paginationDecrease()
    {
        $pagination = $this->pagination;
        $pagination--;
 
        $this->pagination = $pagination;
        
        $this->getTestContent(TestExecution::find($this->test_execution_id)->testCaseExecution[$this->pagination-1]);
    }



}
