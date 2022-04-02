<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Project;
use App\Models\Participate;
use App\Models\TestExecution;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Dashboard extends Component
{   
    use WithPagination;

    public $proj, $noti, $title, $description, $projectID;

    protected $listeners = ['changePage'];

    public function render()
    {
        return view('livewire.page.dashboard', ['projects' => User::find(Auth::id())->projects()->orderBy('name')->paginate(10)])
        ->layout('layouts.app', ['page' => 'Dashboard', 'user' => Auth::user()->name, 'projectId' => null, 'tab' => null]);
    }

    public function mount()
    {   /*
        if(session('noti'))
            $this->noti = true;
        else
            $this->noti = false;
*/
        $this->proj = User::find(Auth::id())->projects()->orderBy('name')->get();
    }

    public function mountId($id)
    {
        $this->projectID = $id;
    }

    public function countProject()
    {
        return User::find(Auth::id())->projects()->count();
    }

    public function createProject()
    {
        $validatedData = $this->validate(
            ['title' => 'required'],
            [
                'title.required' => 'The :attribute cannot be empty.'
            ],
            ['title' => 'project title']
        );

        $user = User::find(Auth::id());
        $project = Project::create([
            'name' => $this->title,
            'description' => $this->description,
        ]);
        
        $participate = Participate::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'role' => "admin",
        ]);  

        return redirect('/dashboard')->with('success', 'Project created successfully');
    }

    public function deleteProject($projId)
    {
        Project::find($projId)->delete();
        //delete noti session
        return redirect('/dashboard')->with('delete', 'Project deleted successfully');
    }

    public function countTeam()
    {
        $total = 0;
        foreach($this->proj as $project)
        {
            $total += $this->countMember($project->id);
        }

        return $total;
    }

    public function countExecution()
    {
        $total = 0;
        $total += TestExecution::where('user_id', Auth::id())->count();

        return $total;
    }

    public function countMember($id)
    {
        return Project::find($id)->users()->count();
    }

    public function getDate($id)
    {
        $time = Project::find($id)->updated_at;
        return Carbon::parse($time)->format('d M, Y');
    }

    public function viewProject($id)
    {
        return redirect('/project/'.$id.'/description');
    }

    public function changePage($page)
    {   
        session()->forget('test');
        session()->forget('team');
        return redirect('/main'.'/'.$page);
    }

    public function viewExecution()
    {
        session(['test' => 'executed']);
        return redirect('main/test');
    }

    public function clearSession($session)
    {
        session()->forget($session);
    }
}
