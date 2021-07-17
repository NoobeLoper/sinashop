<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use UxWeb\SweetAlert\SweetAlert;

class GoogleAuthController extends Controller
{
    use TwoFactorAuth;

    public function redirect()
    {

        return Socialite::driver('google')->redirect();
    }


    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('id', $googleUser->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(Str::random(18)),
                    'two_factor_auth' => 'off',
                ]);
            }

            if ($user->hasVerifyEmail()) {
                $user->markEmailAsVerified();
            }


            Auth::loginUsingId($user->id);

            return $this->logShode($user, $request) ? : redirect('/');
        } catch (\Exception $e) {
            //Todo: Log Error Message

            alert()->error('Login Was Not Successful!', 'Error');
            return redirect('login');
        }
    }
}
