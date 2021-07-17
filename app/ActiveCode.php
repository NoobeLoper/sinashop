<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveCode extends Model
{
    protected $fillable = ['code', 'user_id', 'expired_at'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //!Bug:Dar Function e Pain,Bad az $query, Age Jaye $code, $user ro avaz konim, dar Login With 2FA Error mide,
    //! Va agar jaye $code, $user hamin bashe, dar Activation 2FA (dar page e profile edit e dashboard e user) Error mide!

    public function scopeVerifyCode($query, $code, $user)
    {
        return !! $user->activeCode()->whereCode($code)->where('expired_at', '>', now())->first();
    }

    public function scopeGenerateCode($query, $user)
    {
        //Check If Code Is Generated:

        // if ($code = $this->getAliveCodeForUser($user)) {
        //     $code = $code->code;

        // } else {

        // }
        $user->activeCode()->delete();

        do {
            $code = mt_rand(100000, 999999);
        } while($this->checkCodeIsUnique($user , $code));

        // store the code
        $user->activeCode()->create([
            'code' => $code,
            'expired_at' => now()->addMinutes(3)
        ]);

        return $code;
    }

    private function checkCodeIsUnique($user, int $code)
    {
        return !! $user->activeCode()->whereCode($code)->first();
    }


    private function getAliveCodeForUser($user)
    {
        return $user->activeCode()->where('expired_at', '>', now())->first();
    }
}
