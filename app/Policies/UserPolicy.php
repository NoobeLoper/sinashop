<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function edit(User $user, User $toBeEditedUser)
    {
        return $user->id == $toBeEditedUser->id;
    }

}
