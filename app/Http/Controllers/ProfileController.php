<?php

namespace App\Http\Controllers;

use App\ActiveCode;
use App\Notifications\ActiveCodeNotification;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.profile.index');
    }

    public function manageTwoFactor()
    {
        return view('auth.profile.manageTwoFactor');
    }

    public function postManageTwoFactor(Request $request)
    {
        $data = $request->validate([
            'two_factor_auth' => 'required|in:off,sms',
            'phone' => 'required_unless:two_factor_auth,off'
        ]);

        if ($data['two_factor_auth'] === 'off') {
            $request->user()->update([
                'two_factor_auth' => 'off'
            ]);
            alert()->success('Two-factor authentication disabled', 'Done!');
        }

        if ($data['two_factor_auth'] === 'sms') {
            if ($request->user()->phone !== $data['phone']) {
                //Create Code
                $code = ActiveCode::generateCode($request->user());

                $request->session()->flash('phone', $data['phone']);

                //Send Code to user phone by Notification
                $request->user()->notify(new ActiveCodeNotification($code, $data['phone']));

                return redirect(route('profile.two.factor.phone'));
            } else {
                $request->user()->update([
                    'two_factor_auth' => 'sms',
                ]);
            }
        }

        return back();
    }

    public function getPhoneVerify(Request $request)
    {
        if (! $request->session()->has('phone')) {
            return redirect(route('profile.two.factor'));
        }

        $request->session()->reflash();
        return view('auth.profile.phone-verify');
    }

    public function postPhoneVerify(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        if (! $request->session()->has('phone')) {
            return redirect(route('profile.two.factor'));
        }

        $status = ActiveCode::verifyCode($request->token, $request->user());

        if ($status) {

            $request->user()->activeCode()->delete();
            $request->user()->update([
                'two_factor_auth' => 'sms',
                'phone' => $request->session()->get('phone'),
            ]);

            alert()->success('شماره تلفن شما ثبت شد','عملیات موفقیت آمیز بود');
        } else {
            alert()->error('شماره تلفن شما ثبت نشد. کد ورودی را بررسی کنید', 'خطا');
        }
        return redirect(route('profile.two.factor'));
    }
}
