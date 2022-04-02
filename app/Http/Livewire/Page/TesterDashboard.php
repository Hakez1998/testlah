<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;use App\Models\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TestExecution;
use App\Models\TestCaseExecution;
use App\Models\TestStepExecution;
use App\Models\TestResult;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class TesterDashboard extends Component
{
    public $invitation_code, $page, $invitation_id;

    protected $listeners = ['checkResult'];

    public function render()
    {
        return view('livewire.page.tester-dashboard')
        ->layout('layouts.guest');
    }

    public function mount()
    {   
        if(TestExecution::firstWhere('invitation_code', Crypt::decryptString($this->invitation_code))->tester_name == 'new user' || TestExecution::firstWhere('invitation_code', Crypt::decryptString($this->invitation_code))->tester_name == '')
        {
            session()->forget('page');
        }
        elseif(session('page') == 'complete')
        {
            $this->page = session('page');
        }
        else
        {
            $this->checkSubmission();
        }
        
        if(session('page'))
        {
            $invitation = $this->getDecryptedData();
            $this->invitation_id = $this->getDecryptedData()->id;
            $this->page = session('page');
        }
        else
        {
            $invitation = $this->getDecryptedData();
            $this->invitation_id = $this->getDecryptedData()->id;
            if($invitation->tester_name == 'new user' || $invitation->tester_name == '')
            {
                $this->page = "incomplete";
                session(['page' => $this->page]);	
            }
            else
            {
                $this->page = "complete";
                session([
                    'page' => $this->page,
                    'invitation_id' => $this->invitation_id,
                    'test_id' => $this->getTest()->id
                ]);	
            }
        }    
    }

    public function checkSubmission()
    {
        $submission = $this->getDecryptedData()->submitted_at;
        
        if($submission != null)
        {   
            session(['page' => 'submit']);
        }  
        else
        {
            session(['page' => 'test']);
        } 
             
    }

    public function getDecryptedData()
    {
        $code = Crypt::decryptString($this->invitation_code);
        return TestExecution::firstWhere('invitation_code', $code);
    }

    public function getTesterName()
    {
        return TestExecution::find($this->invitation_id)->tester_name;
    }

    public function getTest()
    {
        return TestExecution::find($this->invitation_id);
    }

    public function updateUsername($name)
    {
        $invitation = $this->getDecryptedData();
        $invitation->tester_name = $name;
        $invitation->save();
        session(['page' => 'complete']);

        return redirect('/test/'. $this->invitation_code);
    }

    public function beginTest()
    {
        session(['page' => 'test']);
        $this->page == "test";
        return redirect('/test/'. $this->invitation_code);
    }

    public function logout()
    {
        if(Auth::check())
        {
            return redirect('/');
        }
        session([
            'page' => "complete",
            'invitation_id' => $this->invitation_id,
            'test_id' => $this->getTest()->id
        ]);	
        return redirect('/test/'. $this->invitation_code);
    }

    public function checkResult()
    {
        $cases = TestExecution::find($this->getTest()->id)->testCaseExecution;
        
        foreach($cases as $case)
        {
            $steps = TestStepExecution::where('test_case_execution_id', $case->id)->get();
            if(count($steps) < 1)
            {
                return false;
            }
            $i=0;
            foreach($steps as $step)
            {   
                //dd(count(TestResult::where('test_step_execution_id', $step->id)->get()) < count($steps));

                if(count(TestResult::where('test_step_execution_id', $step->id)->get()) < 1 )
                {   
                    return false;
                    break;
                }
                $result = TestResult::where('test_step_execution_id', $step->id)->first()->is_success;   
                if($result != "")
                    $check[$case->title][$i] = 'true';
                else
                    $check[$case->title][$i] = 'false';
                
                $i++;
            }

            if(count(array_unique($check[$case->title])) === 1 && end($check[$case->title]) === 'true')
            {                
                return true;
                break;
            }
        }            
    }

    public function submitExecution()
    {
        $execution = $this->getDecryptedData();
        $execution->submitted_at = Carbon::now();
        $execution->save();

        session(['page' => 'submit']);
        return redirect('/test/'. $this->invitation_code);
    }

    public function withdrawExecution()
    {
        $execution = $this->getDecryptedData();
        $execution->submitted_at = null;
        $execution->save();

        session(['page' => 'test']);
        return redirect('/test/'. $this->invitation_code);
    }

 
}
