<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use Illuminate\Support\Facades\Crypt;
use App\Models\TestExecution;
use App\Models\TestCaseExecution;
use App\Models\TestStepExecution;
use App\Models\TestResult;

class ViewTestResult extends Component
{
    public $code, $invitationCode, $execution, $cases, $steps;

    public function render()
    {
        return view('livewire.page.view-test-result')
        ->layout('layouts.guest');
    }

    public function mount($code)
    {
        $this->invitationCode = Crypt::decryptString($this->code);
        $this->execution = TestExecution::where('invitation_code', $this->invitationCode)->first();
        $this->cases = TestExecution::find($this->execution->id)->testCaseExecution;
        $this->mountStep();
    }

    public function mountStep()
    {
        foreach($this->cases as $case)
        {
            $this->steps['case '.$case->id] = TestCaseExecution::find($case->id)->testStepExecution;
        }
    }

    public function getTestResult($stepId)
    {
        $result = TestResult::where('test_step_execution_id', $stepId)->first()->is_success;

        if($result == "")
            return '';
        elseif($result == true)
            return 'pass';
        elseif($result == false)
            return 'fail';
    }

    public function getTestObservation($stepId)
    {
        return TestResult::where('test_step_execution_id', $stepId)->first()->observation;
    }

    public function getTestExecutionTitle()
    {
        return $this->execution->title;
    }

    public function getTesterName()
    {
        return $this->execution->tester_name;
    }

    public function getCaseSummary($id)
    {
        $all = 0; $temppass = 0; $tempsfail = 0; $tempignore = 0;

        $steps = TestCaseExecution::find($id)->testStepExecution;

        foreach($steps as $step)
        {
            $result = TestResult::where('test_step_execution_id', $step->id)->first()->is_success;
            $all++;

            if($result == 1)
                $temppass++;
            elseif($result == '')
                $tempignore++;
            elseif($result == 0)
                $tempsfail++;                
        }

        if($temppass == count($steps))
            return 'Passed';
        elseif($tempignore == count($steps))
            return 'Ignored';
        elseif($temppass < count($steps))
            return 'Failed';
        elseif($tempsfail <= count($steps))
            return 'Failed';
    }

    public function totalCases($type)
    {   
        $pass = 0; $fail = 0; $all = 0; $ignore = 0; $temppass = 0; $tempsfail = 0; $tempignore = 0;

        foreach($this->cases as $cases)
        {
            $steps = $cases->testStepExecution;

            foreach($steps as $step)
            {
                $result = TestResult::where('test_step_execution_id', $step->id)->first()->is_success;
                $all++;

                if($result == 1)
                    $temppass++;
                elseif($result == '')
                    $tempignore++;
                elseif($result == 0)
                    $tempsfail++;                
            }
            $pass = floor($temppass / count($steps));
            $fail = ceil($tempsfail / count($steps));
            $ignore = floor($tempignore / count($steps));
        }
        
        if($type == "pass")
            return $pass;
        elseif($type == "fail")
            return $fail;
        elseif($type == "all")
            return $pass + $fail + $ignore;
        elseif($type == "ignore")
            return $ignore;
    }

    public function generatePDF()
    {
        
    }
}
