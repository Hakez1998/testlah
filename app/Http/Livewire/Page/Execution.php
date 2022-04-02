<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\TestExecution;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class Execution extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.page.execution', ['executions' => TestExecution::where('user_id', Auth::id())->orderBy('title')->paginate(10)])
        ->layout('layouts.app', ['page' => 'Execute Test', 'user' => Auth::user()->name, 'projectId' => null, 'tab' => null]);
    }

    public function getDate($time)
    {
        $past = Carbon::parse($time);
        return $past->diffForHumans();
    }

    public function getStatus($submission)
    {
        if($submission)
            return "submitted";
        else
            return "in progress";
    }

    public function encryptCode($code)
    {
        return Crypt::encryptString($code);
    }

    public function getProjectTitle($projectID)
    {
        $project = Project::find($projectID);

        if($project)
        {
            return ucfirst($project->name);
        }
        else
            return "Project romeved";
    }
}
