<?php

namespace App\Http\Controllers\Auth;

use App\ActiveCode;
use App\Notifications\ActiveCodeNotification;
use App\Notifications\LoginToWebsiteNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

trait TwoFactorAuth
{
    public function logShode(Request $request, $user)
    {
        if ($user->hasTwoFactorAuthEnabled()) {
            return $this->LogoutAndRedirectToTokenEntry($request, $user);
        }

        // Mail For Login To Website
        // $user->notify(new LoginToWebsiteNotification());

        return false;

        //TEST: Alert The User for Login
        alert()->success('خوش آمدید', 'درود')->persistent('سپاس');
        return redirect('/home');
    }

    public function LogoutAndRedirectToTokenEntry(Request $request, $user)
    {
        Auth::logout();

        $request->session()->flash('auth', [
            'user_id' => $user->id,
            'using_sms' => false,
            'remember' => $request->has('remember'),
        ]);

        if ($user->hasSmsAuthEnabled()) {
            $code = ActiveCode::generateCode($user);

            //Send SMS
            $user->notify(new ActiveCodeNotification($code, $user->phone));

            $request->session()->push('auth.using_sms', true);
        }
        return redirect(route('2fa.token'));
    }
}
