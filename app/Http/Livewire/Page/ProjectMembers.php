<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\User;
use App\Models\Participate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Mail\UserInvitation;
use App\Mail\UserRequest;
use App\Mail\UserPromotionDemotion;

class ProjectMembers extends Component
{
    use WithPagination;
    
    public $project_id, $email, $noti, $memberID;

    public function rules()
    {
        return [
                'email' => ['required','email', function ($attribute, $value, $fail){

                    $emails = Project::find($this->project_id)->users()->get();  
                    foreach($emails as $email)
                    {
                        if($value === $email->email)
                        {
                            $fail('Email address already been invited');
                            return true;
                        }
                    }
                    
                }],
            ];
    } 

    public function render()
    {
        return view('livewire.page.project-members', ['members' => Project::find($this->project_id)->users()->orderBy('name')->paginate(10)]);
    }

    public function mount()
    {

    }

    public function getMyRole()
    {
        if(Participate::where('user_id', Auth::id())->where('project_id', $this->project_id)->first()->role == 'admin')
            return false;
        else
            return true;
    }

    public function getUserRole($id)
    {
        $role = Participate::where('user_id', $id)->where('project_id', $this->project_id)->first()->role;

        if($role == "pending")
            return ucfirst("member");
        else
            return ucfirst($role);
    }

    public function getStatus($id)
    {
        if(User::find($id)->name == "new user" || Participate::where('user_id', $id)->where('project_id', $this->project_id)->first()->role == "pending")
        {
            return ucfirst('pending');
        }
        else
            return ucfirst('active');
    }

    public function invite()
    {
        $this->validate();

        $data = [
            'email' => $this->email,
            'link' => url("/login"),
            'project' => Project::find($this->project_id)->name
        ];

        Mail::to($this->email)->send(new UserInvitation($data));

        $user = User::firstOrCreate(
            [   'email' => $this->email  ],
            [   'name' => 'new user' ],
        );
        
        $participate = Participate::firstOrCreate(
            [   'user_id' => $user->id,
                'project_id' => $this->project_id
            ],
            ['role' => 'member'],

        );
        
        return redirect('/project/'.$this->project_id.'/members')->with('invitation', 'Invitation Sent');
    }

    public function requestBecomeAdmin()
    {
        $userId = Crypt::encryptString(Auth::id());
        $projectId = Crypt::encryptString($this->project_id);

        $data = [
            'link' => url("/approval/{$userId}/{$projectId}"),
        ];

        $users = Participate::where('project_id', $this->project_id)->where('role', 'admin')->get();
        foreach($users as $user){
            $to = User::find($user->user_id)->email;
        
            Mail::to($to)->send(new UserRequest($data));
        }

        Participate::where('user_id', Auth::id())->where('project_id', $this->project_id)->update([
            'role' => "pending"
        ]);

        return redirect('/project/'.$this->project_id.'/members')->with('invitation', 'Request Sent');
        
    }

    public function promoteOrDemoteUser($userId, $role)
    {
        if($role == "Member")
        {
            $message = "You are now an Admin of Teslah";
            $role = "admin";
        }
        elseif($role == "Admin")
        {
            $message = "You have been demoted by ".User::find(Auth::id())->name;
            $role = "member";
        }

        $data = [
            'email' => User::find($userId)->email,
            'subject' => "Here, an announcment for you",
            'message' => $message,
            'user' => User::find($userId)->name
        ];
        
        Mail::to($data['email'])->send(new UserPromotionDemotion($data));

        $user = Participate::where('project_id', $this->project_id)->where('user_id', $userId )->first();
        $user->role = $role;
        $user->save();

        return redirect('/project/'.$this->project_id.'/members')->with('success', 'Your action have been updated successfully');
    }

    public function resendInvitation($email)
    {
        $data = [
            'email' => $email,
            'link' => url("/login"),
            'project' => Project::find($this->project_id)->name
        ];
        
        Mail::to($email)->send(new UserInvitation($data));

        return redirect('/project/'.$this->project_id.'/members')->with('invitation', 'Invitation Sent');
    }

    public function removeMember($teamId)
    {
        Participate::where('project_id', $this->project_id)->where('user_id', $teamId)->delete();
        return redirect('/project/'.$this->project_id.'/members')->with('success', User::find($teamId)->name.' has been removed');
    }

    public function mountTeamID($id)
    {
        $this->memberID = $id;
    }

}
