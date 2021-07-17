<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class PermissionForUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:staff-user-permissions')->only(['create', 'store']);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        return view('admin.users.permissions', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User  $user)
    {
        $user->permissions()->sync($request->permissions);
        $user->roles()->sync($request->roles);

        alert()->success('مطلب مورد نظر شما با موفقیت ثبت شد');
        return redirect(route('admin.users.index'));
    }

}
