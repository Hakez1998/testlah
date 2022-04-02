<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use App\Models\Participate;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    
    if(Auth::check())
        return redirect('dashboard');
    else
    return redirect('/login');
})->name('login');
    
Route::get('/login-failed', App\Http\Livewire\Auth\AuthFailed::class);

Route::get('/login', App\Http\Livewire\Auth\Login::class);

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/callback',  [SocialiteController::class, 'googleAuth']);

Route::get('/logout',  [SocialiteController::class, 'googleLogout']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard',  App\Http\Livewire\Page\Dashboard::class);
Route::middleware(['auth:sanctum', 'verified'])->get('/execution',  App\Http\Livewire\Page\Execution::class);

Route::middleware(['auth:sanctum', 'verified'])->get('/project/{project_id}/{tab}', App\Http\Livewire\Page\ManageProject::class);

Route::get('/test/{invitation_code}', App\Http\Livewire\Page\TesterDashboard::class);

Route::get('/approval/{user_id}/{project_id}', function($user_id, $project_id){
    $userid = Crypt::decryptString($user_id);
    $projectid = Crypt::decryptString($project_id);

    $user = Participate::where('project_id', $projectid)->where('user_id', $userid )->first();
    if($user->role == "admin")
        return redirect('/');
    else{
        $user->role = "admin";
        $user->save();
    }

    return redirect('/');
});

Route::get('/test/view/result/{code}', App\Http\Livewire\Page\ViewTestResult::class);


