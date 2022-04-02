<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use App\Models\Project;
use App\Models\User;
use App\Models\Participate;
use Carbon\Carbon;


class ProjectDescription extends Component
{
    public $project_id, $projectDescription, $projectTitle;

    public function render()
    {
        return view('livewire.page.project-description');
    }

    public function mount($project_id)
    {
        $this->project = Project::find($project_id);
        $this->projectTitle = $this->project->name;
        $this->projectDescription = $this->project->description;
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

    public function getCreator()
    {
        $user = Participate::where('project_id', $this->project_id)->where('role', 'admin')->first();
        return User::find($user->user_id)->name;
    }

    public function getDate()
    {
        $date = Project::find($this->project_id)->created_at;
        return Carbon::parse($date)->format('d M Y');
    }

    public function updateProjectDescription()
    {
        $project = Project::find($this->project_id);
        $project->name = $this->projectTitle;
        $project->description = $this->projectDescription;
        $project->save();
        return redirect('/project/'.$this->project_id.'/description')->with('success', 'Project description updated successfully');
    }
}
