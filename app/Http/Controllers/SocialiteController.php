<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;

class SocialiteController extends Controller
{
    public function googleAuth()
    {
        $socialite = Socialite::driver('google')->user();
        
        if(User::firstWhere('email', $socialite->getEmail()))
        {   
            $user = User::firstWhere('email', $socialite->getEmail());
            if($user->name == 'new user')
            {
                $user->name = $socialite->getName();
                $user->save();
            }
            
            Auth::login($user, $remember = true);
            return redirect('/');
        }
        else
        {   
            $user = User::firstOrCreate(
                ['name' => $socialite->getName()],
                ['email' => $socialite->getEmail()]
            );

            Auth::login($user, $remember = true);
            return redirect('/');
        }                
    }

    public function googleLogout()
    {
        Auth::logout();
        return redirect('/login');
    }

}
