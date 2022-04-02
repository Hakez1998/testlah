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

    protected $listeners = ['changePage', 'removeTestSuite', 'mountTestSuite', 'mountExecution', 'clearTestCase', 'remountCase', 'flashMessage', 'deleteTestCase'];

    public function render()
    {
        return view('livewire.page.manage-project')
        ->layout('layouts.app', ['page' => 'project']);
    }

    public function mount($project_id, $tab)
    {   
        $this->project = Project::find($project_id);
        $this->changeTab($tab);
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

    public function totalProjectMembers()
    {
        return count(Project::find($this->project_id)->users()->get());
    }

    public function totalTestCases()
    {
        return count(Project::find($this->project_id)->testCases()->get());
    }

    public function totalTestSuites()
    {
        return count(Project::find($this->project_id)->testSuites()->get());
    }

    public function getTeamName($id)
    {
        $user = User::find($id);
        if($user->name == 'new user')
        {
            return $user->email;
        }
        else
            return $user->name;
    }

    public function getStatus($id)
    {
        $user = User::find($id);
        if($user->name == 'new user')
            return 'Pending...';
        else
        {
            $role = Participate::where('project_id', $this->project_id)->where('user_id', $id )->first()->role;

            if($role == 'creator')
                return 'Admin';
            else    
                return ucfirst($role);
        }
    }

    public function mountExecution($a)
    { 
        $this->indicator = $a;
    }

    public function mountTestSuite($id)
    {   
        $this->indicator = true;
        $this->selectedTestSuite['testSuite'] = TestSuite::find($id);   
        $this->selectedTestSuite['testExecutionTitle'] = null;
        
        $teams = Project::find($this->project_id)->users; 
        $j = 0;

        foreach($teams as $team)
        {
            $this->selectedTestSuite['invitees'][$j] = $team->email;
            $j++;
        }

        $this->selectedTestSuite['testCaseTestSuite'] = TestCasesTestSuites::where('test_suite_id', $id)->get();

        if(count($this->selectedTestSuite['testCaseTestSuite']) > 1)
        {   
            $selectedCases = null;
            $i = 0;
            foreach($this->selectedTestSuite['testCaseTestSuite'] as $case)
            {
                $selectedCases[$i] = $case->test_case_id;
                $i++;
            }
            $this->selectedTestSuite['testCases'] = Project::find($this->project_id)->testCases()->whereNotIn('id', $selectedCases)->get();
        }
        elseif(count($this->selectedTestSuite['testCaseTestSuite']) == 1)
        {   
            $selectedCases = null;
            $i = 0;
            foreach($this->selectedTestSuite['testCaseTestSuite'] as $case)
            {
                $selectedCases[$i] = $case->test_case_id;
                $i++;
            }
            
            $this->selectedTestSuite['testCases'] = Project::find($this->project_id)->testCases()->where('id', '!=', $selectedCases)->get();
        }
        else
        {
            $this->selectedTestSuite['testCases'] = Project::find($this->project_id)->testCases;
        }
        
    }

    public function getTestCaseTitle($testCaseID)
    {
        return TestCase::find($testCaseID)->title;
    }

    public function userRole()
    {
        return Participate::where('project_id', $this->project_id)->where('user_id', Auth::id() )->first()->role;
    }

    public function changePage($page)
    {
        return redirect('/main/'.$page);
    }

    public function changeTab($tab)
    {
        $this->tab = $tab;
        $this->emit('changeTab', $this->tab);
    }

    public function getCreator()
    {
        $user = Participate::where('project_id', $this->project_id)->where('role', 'creator')->first();
        return User::find($user->user_id)->name;
    }

    public function getDate()
    {
        $date = Project::find($this->project_id)->created_at;
        return Carbon::parse($date)->format('d M Y');
    }

    public function addTestSuite($title)
    {   
        if($title)
        {
            TestSuite::create([
                'project_id' => $this->project_id,
                'title' => $title,
            ]);
            $this->emit('resetInput', 'null');
            return redirect('/main/project/'.$this->project_id.'/suites');
        }
        else
        {
            $this->emit('suiteNull', 'null');
        }
        
    }

    public function addTestCase($title)
    {
        if($title)
        {
            TestCase::create([
                'project_id' => $this->project_id,
                'title' => $title,
            ]);
            $this->emit('resetInput', 'null');
            return redirect('/main/project/'.$this->project_id.'/cases');
        }
        else
        {
            $this->emit('titleNull', 'null');
        }
    }

    public function mountTestCase($caseId)
    {
        $this->selectedTestCase = TestCase::find($caseId);
        $this->testSteps = $this->selectedTestCase->testSteps;
    }

    public function deleteTestCase($testCaseId)
    {   
        $caseSuite = TestCasesTestSuites::where('test_case_id', $testCaseId)->first();
        if($caseSuite)
        {
            $caseSuite->delete();
        }

        $testCase = TestCase::find($testCaseId);
        if($testCase)
        {
            $testSteps = $testCase->testSteps()->get(); 

            foreach($testSteps as $step)
            {
                $step->delete();
            }

            $testCase->delete();

            return redirect('/main/project/'.$this->project_id.'/cases');
        }
    }

    public function clearTestCase()
    {
        $this->selectedTestCase = null;
    }

    public function addStep($caseId)
    {
        TestStep::create([
            'test_case_id' => $caseId,
        ]);
        $this->mountTestCase($caseId);
    }

    public function remountCase()
    {
        $this->mountTestCase($this->selectedTestCase->id);
    }

    public function updateTestSuite($suiteId, $title)
    {   
        if($title != "")
        {
            $testSuite = TestSuite::find($suiteId);
            $testSuite->title = $title;
            $testSuite->save();

            $this->selectedTestTitle = null;

            $this->test_suites = Project::find($this->project_id)->testSuites()->get();
        }
        else
        {
            $this->emit('suiteTitleNull', $suiteId);
            $this->selectedTestTitle = null;
            $this->test_suites = Project::find($this->project_id)->testSuites()->get();
        }
        
    }

    public function removeTestSuite($id)
    {   
        $suite = TestSuite::find($id);
        if($suite)
        {
            $cases = TestCasesTestSuites::where('test_suite_id', $id)->get();

            foreach($cases as $case)
            {
                $case->delete();
            }
            $suite->delete();
            $this->test_suites = Project::find($this->project_id)->testSuites()->get();
        }        
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

    public function updateProjectTitle()
    {
        $project = Project::find($this->project_id);
        $project->name = $this->projectTitle;
        $project->save();
        $this->project = Project::find($this->project_id);
        $this->projectTitle = $this->project->name;
    }

    public function updateProjectDescription()
    {
        $project = Project::find($this->project_id);
        $project->description = $this->projectDescription;
        $project->save();
        $this->project = Project::find($this->project_id);
        $this->projectDescription = $this->project->description;
    }

    public function selectTestTitle($suiteTitle)
    {   
        $this->selectedTestTitle = $suiteTitle;
    }

    public function insertTestCase($testCaseID, $testSuiteID)
    {   
        $data = TestCasesTestSuites::create([
            'test_suite_id' => $testSuiteID,
            'test_case_id' => $testCaseID,
        ]);

        $this->mountTestSuite($testSuiteID);
    }

    public function removeTestCase($testCaseID, $testSuiteID)
    {
        $data = TestCasesTestSuites::find($testCaseID);
        $data->delete();

        $this->mountTestSuite($testSuiteID);
    }

    public function resetTestSuite()
    {
        $this->selectedTestSuite = null;
    }

    public function addInvitation($email, $emailId)
    {   
        if($email == null)
        {
            $this->emit('nullEmail', $emailId);
        }
        else
        {   
            if($this->selectedTestSuite['invitees'])
                $i = count($this->selectedTestSuite['invitees']);
            else
                $i = 0;
            $this->selectedTestSuite['invitees'][$i] = $email;
            $this->emit('resetEmail', $emailId);
        }
        
    }

    public function removeInvitation($index)
    {
        $arr = $this->selectedTestSuite['invitees'];
        unset($arr[$index]);
        $this->selectedTestSuite['invitees'] = array_values($arr);
    }

    public function randomAlphanumericString($length) {
        $chars = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        return substr(str_shuffle($chars), 0, $length);
    }

    public function checkTitle($title)
    {
        $data = TestExecution::where('title', $title)->first();
        
        if($data)
        {
            $this->emit('titleError', 'Title already taken');
        }
    }

    public function executeTestNow($title)
    {
        if($title == null)
        {
            $this->emit('titleError', 'Title Empty');
        }
        else
        {   
            $this->emit('closeExecutionForm', false);
            foreach($this->selectedTestSuite['invitees'] as $invitee)
            {   
                if(User::where('email', $invitee)->first())
                {
                    $execution = TestExecution::create(
                        [
                            'user_id' => User::where('email', $invitee)->first()->id,
                            'tester_name' => User::where('email', $invitee)->first()->name,
                            'email' => $invitee,
                            'title' => $title,
                            'invitation_code' => $this->randomAlphanumericString(5)
                        ]
                    );
                }
                else
                {
                    $execution = TestExecution::create(
                        [
                            'email' => $invitee,
                            'title' => $title,
                            'invitation_code' => $this->randomAlphanumericString(5)
                        ]
                    );
                    
                }
                $invitationCode = Crypt::encryptString($execution->invitation_code); 

                $data = [
                    'link' => url("/test/{$invitationCode}"),    
                    'email' => $invitee,
                    'project' => $this->projectTitle
                ];
                
                Mail::to($invitee)->send(new TesterInvitation($data));

                $testCases = TestCasesTestSuites::where('test_suite_id', $this->selectedTestSuite['testSuite']['id'])->get();
                foreach($testCases as $case)
                {   
                    $testCase = TestCaseExecution::create(
                        [
                            'test_execution_id' => $execution->id,
                            'title' => TestCase::find($case->test_case_id)->title,
                        ]
                    );
                    $testSteps = TestCase::find($case->test_case_id)->testSteps;
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
                                    
            } 
        }
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

    public function setSession($sessionTittle, $link)
    {
        if($link == 'test')
        {
            session(['test' => $sessionTittle]);
            return redirect('main/'.$link);
        }
        elseif($link == 'team')
        {
            session(['team' => $sessionTittle]);
            return redirect('main/'.$link);
        }        
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

    public function inviteNewUser($to)
    {
        if($to)
        {
            $data = [
                'email' => $to,
                'link' => url("/login"),
                'project' => Project::find($this->project_id)->name
            ];
            
            Mail::to($to)->send(new UserInvitation($data));

            $user = User::firstOrCreate(
                [   'email' => $to  ],
                [   'name' => 'new user' ],
            );

            $participate = Participate::firstOrCreate(
                [   'user_id' => $user->id,
                    'project_id' => $this->project_id
                ],
                ['role' => 'member'],

            );
        
            $this->emit('invitationModule', 'success');
            $this->project_teams = Project::find($this->project_id)->users;
        }
        else
        {
            $this->emit('invitationModule', 'fail');
        }
    }

    public function promoteOrDemoteUser($userId, $action)
    {
        if($action == "Promote")
        {
            $message = "You are now an Admin of Teslah";
            $role = "admin";
        }
        elseif($action == "Demote")
        {
            $message = "You have been demoted by admin";
            $role = "member";
        }

        $data = [
            'email' => User::find($userId)->email,
            'subject' => "Here, an announcment for you",
            'message' => $message
        ];
        
        Mail::to($data['email'])->send(new UserPromotionDemotion($data));

        $user = Participate::where('project_id', $this->project_id)->where('user_id', $userId )->first();
        $user->role = $role;
        $user->save();
    }

    public function removeMember($teamId)
    {
        Participate::find($teamId)->delete();
        $this->project_teams = Project::find($this->project_id)->users;
    }
        
}
