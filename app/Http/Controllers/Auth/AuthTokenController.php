<?php

namespace App\Http\Controllers\Auth;

use App\ActiveCode;
use App\Http\Controllers\Controller;
use App\Notifications\LoginToWebsiteNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthTokenController extends Controller
{
    public function getToken(Request $request)
    {
        if(! $request->session()->has('auth')) {
            return redirect('login');
        }

        $request->session()->reflash();
        return view('auth.token');
    }

    public function postToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        if(! $request->session()->has('auth')) {
            return redirect('login');
        }

        $user = User::findOrFail($request->session()->get('auth.user_id'));

        $status = ActiveCode::verifyCode($request->token, $user);

        if (! $status) {
            alert()->error('Code You Entered Was Incorrect', 'Error');
            return redirect(route('login'));
        }

        if (Auth::loginUsingId($user->id, $request->session()->get('auth.remember'))) {
            // $user->notify(new LoginToWebsiteNotification());
            $user->activeCode()->delete();
            alert()->success('Welcome Back');
            return redirect('/home');
        }

        return redirect(route('login'));

    }
}
