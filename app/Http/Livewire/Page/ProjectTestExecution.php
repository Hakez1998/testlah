<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\TestSuite;
use App\Models\TestCase;
use App\Models\TestCasesTestSuites;
use App\Models\User;
use App\Models\TestExecution;
use App\Models\TestCaseExecution;
use App\Models\TestStepExecution;
use App\Mail\TesterInvitation;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class ProjectTestExecution extends Component
{
    use WithPagination;

    public $project_id, $mountedExecution, $executionTitle, $email;
    public $testers = array();

    protected function rules()
    {
        return [
            'email' => ['required', 'email', function ($attribute, $value, $fail){
                
                foreach($this->testers as $tester)
                {
                    if($value === $tester['email'])
                    {
                        $fail('Email address already been taken');
                        return true;
                    }
                }
                
            }],
        ];
    }
    
    public function render()
    {
        return view('livewire.page.project-test-execution', ['tests' => TestExecution::where('project_id', $this->project_id)->groupBy('title')->orderBy('title')->paginate(10)]);
    }

    public function totalTester($title)
    {
        return TestExecution::where('title', $title)->get()->count();
    }

    public function getDate($id)
    {
        $time = TestExecution::find($id)->created_at;
        return Carbon::parse($time)->format('d M, Y');
    }

    public function mountExecution($id, $title)
    {
        $this->mountedExecution = $id;
        $this->executionTitle = $title;
        $testers = TestExecution::where('title', $title)->get();

        foreach($testers as $tester)
        {
            array_push($this->testers,['id' => $tester->id, 'email' => $tester->email, 'name' => $tester->tester_name]);
        }
    }

    public function checkExistance($email)
    {
        if(TestExecution::where('email', $email)->where('title', $this->executionTitle)->first())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function removeEmail($key)
    {
        unset($this->testers[$key]);
    }

    public function randomAlphanumericString($length) {
        $chars = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        return substr(str_shuffle($chars), 0, $length);
    }

    public function invite()
    {
        $this->validate();
        array_push($this->testers,['email' => $this->email]);
        $this->email = null;
            
    }

    public function additionalInvitation($email)
    {
        if(User::where('email', $email)->first())
        {
            $execution = TestExecution::create(
                [
                    'user_id' => User::where('email', $email)->first()->id,
                    'project_id' => $this->project_id,
                    'tester_name' => User::where('email', $email)->first()->name,
                    'email' => $email,
                    'title' => $this->executionTitle,
                    'invitation_code' => $this->randomAlphanumericString(5)
                ]
            );
        }
        else
        {
            $execution = TestExecution::create(
                [
                    'project_id' => $this->project_id,
                    'email' => $email,
                    'title' => $this->executionTitle,
                    'invitation_code' => $this->randomAlphanumericString(5)
                ]
            );
            
        }

        $invitationCode = Crypt::encryptString($execution->invitation_code); 

        $data = [
            'link' => url("/test/{$invitationCode}"),    
            'email' => $email,
            'project' => $this->executionTitle
        ];
            
        Mail::to($email)->send(new TesterInvitation($data));

        $testCases = TestExecution::find($this->mountedExecution)->testCaseExecution;

        foreach($testCases as $case)
        {
            $testCase = TestCaseExecution::create(
                [
                    'test_execution_id' => $execution->id,
                    'title' => $case->title,
                ]
            );
            $testSteps = TestCaseExecution::find($case->id)->testStepExecution;
            foreach($testSteps as $step)
            {
                $testStep = TestStepExecution::create(
                    [
                        'test_case_execution_id' => $testCase->id,
                        'title' => $step->title,
                        'expected_result' => $step->expected_result,
                    ]
                );
            }
        }
        
        $this->checkExistance($email);
    }

    public function resendInvitation($id)
    {
        $tester = TestExecution::find($id);

        $invitationCode = Crypt::encryptString($tester->invitation_code); 

        $data = [
            'link' => url("/test/{$invitationCode}"),    
            'email' => $tester->email,
            'project' => $this->executionTitle
        ];

        Mail::to($tester->email)->send(new TesterInvitation($data));

        return redirect('/project/'.$this->project_id.'/testexecutes')->with('invitation', 'Invitation Sent');
    }

    public function totalExecution($type)
    {
        $countAll = 0; $countSubmit = 0; $countInProgress = 0;

        $executions = TestExecution::where('title', $this->executionTitle)->get();

        foreach($executions as $execution)
        {
            $submission = TestExecution::find($execution->id)->submitted_at;

            $countAll++;
            if($submission != null)
                $countSubmit++;
            else
                $countInProgress++;
        }

        if($type == "submitted")
            return $countSubmit;
        elseif($type == "inprogress")
            return $countInProgress;
        elseif($type == "all")
            return $countAll;

    }

    public function getSubmission($id)
    {
        $submission = TestExecution::find($id)->submitted_at;
        if($submission)
            return "submitted";
        else
            return "in progress";
    }

    public function encryptCode($id)
    {
        return Crypt::encryptString(TestExecution::find($id)->invitation_code);
    }
}
