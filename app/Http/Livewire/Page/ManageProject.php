<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use App\Models\Project;
use App\Models\User;
use App\Models\Participate;
use App\Models\TestCase;
use App\Models\TestSuite;
use App\Models\TestStep;
use App\Models\TestCasesTestSuites;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitation;
use App\Mail\UserPromotionDemotion;
use App\Mail\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Models\TestExecution;
use App\Models\TestCaseExecution;
use App\Models\TestStepExecution;
use App\Mail\TesterInvitation;


class ManageProject extends Component
{
    public $project_id, $tab, $project, $projectTitle, $projectDescription, $test_suites, $selectedTestCase, $testCases, $testSteps, $project_teams, $selectedTestSuite, $selectedTestTitle, $indicator, $testExecutions;

    protected $listeners = ['setTitle', 'remountProjectTitle', 'changePage', 'removeTestSuite', 'mountTestSuite', 'mountExecution', 'clearTestCase', 'remountCase', 'flashMessage', 'deleteTestCase'];

    public function render()
    {
        return view('livewire.page.manage-project')
        ->layout('layouts.app', ['page' => 'Project', 'user' => Auth::user()->name, 'projectId' => $this->project_id, 'tab' => $this->tab]);
    }

    public function mount($project_id, $tab)
    {   
        $this->project = Project::find($project_id);
        $this->projectTitle = $this->project->name;
        $this->projectDescription = $this->project->description;
        $this->selectedTestTitle = null;
        $this->creator = Project::find($this->project_id)->users()->get();
        $this->test_suites = Project::find($this->project_id)->testSuites()->get();
        $this->testCases = Project::find($this->project_id)->testCases()->get();
        $this->project_teams = Project::find($project_id)->users;
        $this->testExecutions = TestExecution::where('user_id', Auth::id())->get();
        $this->selectedTestSuite = null;
        $this->selectedTestCase = null;
    }

    public function setTitle($data)
    {
        $this->projectTitle = $data;
        //dd($this->projectTitle);
    }

    public function clearSession($session)
    {
        session()->forget($session);
    }

    public function userRequest()
    {   
        $userId = Crypt::encryptString(Auth::id());
        $projectId = Crypt::encryptString($this->project_id);

        $data = [
            'user' => Auth::user()->name,
            'link' => url("/approval/{$userId}/{$projectId}"),
        ];

        $user = Participate::where('project_id', $this->project_id)->where('role', 'creator')->first();
        $to = User::find($user->user_id)->email;
        
        Mail::to($to)->send(new UserRequest($data));

        $this->emit('invitationSent', 'null');
    }



    public function mountTestExecution($executionTitle)
    {   
        $this->selectedTestSuite['testExecution'] = TestExecution::where('title', $executionTitle)->get();
        $this->selectedTestSuite['testExecutionTitle'] = $executionTitle;
        $this->selectedTestSuite['invitees'] = null;
        //dd($this->selectedTestSuite['testExecution']);

        return true;
    }

    public function getInvitationStatus($executionId)
    {
        $execution = TestExecution::find($executionId)->tester_name;
        if($execution == null)
        {
            return "Pending";
        }
        else
        {
            return "Accepted";
        }
        
    }

    public function additionalInvitation($email, $title, $borrowId)
    {   
        $execution = TestExecution::create(
            [
                'email' => $email,
                'title' => $title,
                'invitation_code' => $this->randomAlphanumericString(5)
            ]
        );

        $testCases = TestExecution::find($borrowId)->testCaseExecution;
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
        
        $invitationCode = Crypt::encryptString($execution->invitation_code); 

        $data = [
            'link' => url("/test/{$invitationCode}"),    
            'email' => $email,
            'project' => $this->projectTitle
        ];
        
        Mail::to($email)->send(new TesterInvitation($data));

        $this->testExecutions = TestExecution::where('user_id', Auth::id())->get();

    }

    public function resendInvitation($email, $id)
    {   
        $invitationCode = Crypt::encryptString(TestExecution::find($id)->invitation_code); 
        
        $data = [
            'link' => url("/test/{$invitationCode}"),    
            'email' => $email
        ];
        
        Mail::to($email)->send(new TesterInvitation($data));
    }

    public function getTesterNumber($title)
    {
        return TestExecution::where('title', $title)->count();
    }

    public function flashMessage()
    {
        $this->emit('alertShow', 'null');
    }

    public function roleAction($userId)
    {
        $role = Participate::where('user_id', $userId)->where('project_id', $this->project_id)->first()->role;
        if($userId != Auth::id())
        {
            if($role == "creator")
            {
                $action = null;
            }
            elseif($role == "admin")
            {
                $action = "Demote";
            }
            elseif($role == "member")
            {
                $action = "Promote";
            }
        }
        else
        {
            $action = null;
        }
        return $action;
    }
        
}
