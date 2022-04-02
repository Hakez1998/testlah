<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\TestCase;
use App\Models\TestStep;

class ProjectTestCase extends Component
{
    use WithPagination;

    public $project_id, $noti, $step, $output, $title, $caseToUpdate, $caseToDelete;
    public $steps = array();

    protected $rules = [
        'step' => 'required',
    ];

    public function render()
    {
        return view('livewire.page.project-test-case', ['cases' => Project::find($this->project_id)->testCases()->orderBy('title')->paginate(10)]);
    }

    public function mount()
    {

    }  
    
    public function getTotalTestSteps($caseId)
    {
        return TestCase::find($caseId)->testSteps->count();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function pushStep()
    {
        $validatedData = $this->validate();
        $num = count($this->steps);

        array_push($this->steps, ['count' => $num, 'step' => $this->step, 'output' => $this->output]);

        $this->step = '';
        $this->output = '';        
    }

    public function createTestCase()
    {
        $case = TestCase::create([
            'title' => $this->title,
            'project_id' => $this->project_id,
        ]);

        if(count($this->steps) > 0)
        {
            foreach($this->steps as $step)
            {
                TestStep::create([
                    'title' => $step['step'],
                    'expected_result' => $step['output'],
                    'test_case_id' => $case->id
                ]);
            }
        }
        
        return redirect('/project/'.$this->project_id.'/testcases')->with('success', 'Test case created successfully');
    }

    public function deleteTestCase($caseId)
    {
        TestCase::find($caseId)->delete();
        
        return redirect('/project/'.$this->project_id.'/testcases')->with('success', 'Test case deleted successfully');
    }

    public function mountToDelete($id)
    {
        $this->caseToDelete = $id;
    }

    public function mountToUpdate($caseId)
    {
        $this->caseToUpdate = $caseId;
        $this->title = TestCase::find($caseId)->title;
        $steps = TestCase::find($caseId)->testSteps;
        $num = 0;

        foreach($steps as $step)
        {
            array_push($this->steps, ['count' => $num, 'step' => $step->title, 'output' => $step->expected_result]);
            $num++;
        }
    }

    public function updateTestCase()
    {
        $case = TestCase::find($this->caseToUpdate)->update([
            'title' => $this->title
        ]);

        $steps = TestCase::find($this->caseToUpdate)->testSteps;

        foreach($steps as $step)
        {
            $step->delete();
        }

        if(count($this->steps) > 0)
        {
            foreach($this->steps as $step)
            {
                TestStep::create([
                    'title' => $step['step'],
                    'expected_result' => $step['output'],
                    'test_case_id' => $this->caseToUpdate
                ]);
            }
        }

        return redirect('/project/'.$this->project_id.'/testcases')->with('success', 'Test case updated successfully');
    }

    public function removeStep($key)
    {
        unset($this->steps[$key]);
    }
    
}
