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

class ProjectTestSuite extends Component
{
    use WithPagination;
    
    public $project_id, $title, $suiteToUpdate, $suiteToRelease, $suiteToDelete, $email;
    public $cases = array();
    public $invitees = array();

    protected function rules()
    {
        return [
            'email' => ['required', 'email', function ($attribute, $value, $fail){
                
                foreach($this->invitees as $invitee)
                {
                    if($value === $invitee['email'])
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
        return view('livewire.page.project-test-suite', ['suites' => Project::find($this->project_id)->testSuites()->orderBy('title')->paginate(10)]);
    }

    public function mount()
    {
        $cases = Project::find($this->project_id)->testCases;
        $num = 0;

        foreach($cases as $case)
        {
            array_push($this->cases, ['count' => $num, 'id' => $case->id, 'title' => $case->title, 'selected' => false]);
            $num++;
        }
        
    }

    public function getTotalTestCases($suiteId)
    {
        return TestSuite::find($suiteId)->testCases->count();
    }

    public function selectCase($key)
    {
        $this->cases[$key]['selected'] = !$this->cases[$key]['selected'];
    }

    public function createTestSuite()
    {
        $suite = TestSuite::create([
            'title' => $this->title,
            'project_id' => $this->project_id
        ]);

        $num = 0;
        foreach($this->cases as $case)
        {
            if($case['selected'])
            {
                TestCasesTestSuites::create([
                    'test_suite_id' => $suite->id,
                    'test_case_id' => $case['id']
                ]);
            }
            $num++;
        }
        return redirect('/project/'.$this->project_id.'/testsuites')->with('success', 'Test suite created successfully');

    }

    public function deleteTestSuite($suiteId)
    {
        TestSuite::find($suiteId)->delete();
        return redirect('/project/'.$this->project_id.'/testsuites')->with('success', 'Test suite deleted successfully');
    }

    public function mountToDelete($id)
    {
        $this->suiteToDelete = $id;
    }

    public function mountToUpdate($suiteId)
    {
        $this->suiteToUpdate = $suiteId;
        $this->title = TestSuite::find($suiteId)->title;
        $cases = Project::find($this->project_id)->testCases;
        $num = 0;

        foreach($this->cases as $case)
        {
            if(TestCasesTestSuites::where('test_case_id', $case['id'])->where('test_suite_id', $suiteId)->first() != null)
            {
                $this->cases[$num]['selected'] = true;
            }
            $num++;
        }
    }

    public function updateTestSuite()
    {
        TestSuite::find($this->suiteToUpdate)->update(['title' => $this->title]);

        $num = 0;
        foreach($this->cases as $case)
        {
            if($case['selected'])
            {
                TestCasesTestSuites::firstOrCreate([
                    'test_suite_id' => $this->suiteToUpdate,
                    'test_case_id' => $case['id']
                ]);
            }
            else
            {
                TestCasesTestSuites::where('test_case_id', $case['id'])->where('test_suite_id', $this->suiteToUpdate)->delete();
            }
            $num++;
        }

        return redirect('/project/'.$this->project_id.'/testsuites')->with('success', 'Test suite updated successfully');
    }

    public function mountToRelease($suiteID)
    {
        $this->suiteToRelease = $suiteID;
        $members = Project::find($this->project_id)->users;
        $num = 0;

        foreach($members as $member)
        {
            array_push($this->invitees, ['count' => $num, 'email' => $member->email]);
            $num++;
        }

    }

    public function removeEmail($key)
    {
        unset($this->invitees[$key]);
    }

    public function invite()
    {
        $this->validate();

        $num = count($this->invitees);
        array_push($this->invitees, ['count' => $num, 'email' => $this->email]);
        $this->email = '';
    }

    public function randomAlphanumericString($length) {
        $chars = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        return substr(str_shuffle($chars), 0, $length);
    }

    public function createExecution()
    {
        $validatedData = $this->validate(
            ['title' => 'required|unique:test_executions,title'],
            [
                'title.required' => 'The :attribute cannot be empty.'
            ],
            ['title' => 'Execution title']
        );

        $this->emit('buttonAction', 'null');
        
        foreach($this->invitees as $invitee)
        {
            if(User::where('email', $invitee['email'])->first())
            {
                $execution = TestExecution::create(
                    [
                        'user_id' => User::where('email', $invitee['email'])->first()->id,
                        'project_id' => $this->project_id,
                        'tester_name' => User::where('email', $invitee['email'])->first()->name,
                        'email' => $invitee['email'],
                        'title' => $this->title,
                        'invitation_code' => $this->randomAlphanumericString(5)
                    ]
                );
            }
            else
            {
                $execution = TestExecution::create(
                    [
                        'project_id' => $this->project_id,
                        'email' => $invitee['email'],
                        'title' => $this->title,
                        'invitation_code' => $this->randomAlphanumericString(5)
                    ]
                );
                
            }

            $invitationCode = Crypt::encryptString($execution->invitation_code); 

            $data = [
                'link' => url("/test/{$invitationCode}"),    
                'email' => $invitee['email'],
                'project' => $this->title
            ];
            
            Mail::to($invitee['email'])->send(new TesterInvitation($data));

            $testCases = TestCasesTestSuites::where('test_suite_id', $this->suiteToRelease)->get();

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
        
        return redirect('/project/'.$this->project_id.'/testsuites')->with('success', 'Test execution created successfully');
    }

}
