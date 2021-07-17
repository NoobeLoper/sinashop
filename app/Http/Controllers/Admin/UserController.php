<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    // Raveshe E'maal kardane Middleware, Mostaqim

    public function __construct()
    {
        $this->middleware('can:create-user')->only(['create', 'store']);
        $this->middleware('can:edit-user')->only(['edit', 'update']);
        $this->middleware('can:delete-user')->only(['destroy']);
        $this->middleware('can:show-users')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::paginate(5);
        //Add Searching To Index(All Users) Page

        $users = User::query();
        if ($keyword = request('search')) {
            $users->where('email', 'LIKE', "%{$keyword}%")->orWhere('name', 'LIKE', "%{$keyword}%")->orWhere('id', $keyword);
        }

        if (request('admin')) {
            $this->authorize('show-staff-users');
            $users->where('is_superuser', 1)->orWhere('is_staff', 1);
        }

        if (Gate::allows('show-staff-users')) {
            if (\request('admin')) {
                $users->where('is_superuser', 1)->orWhere('is_staff', 1);
            }
        } else {
            $users->where('is_superuser', 0)->orWhere('is_staff', 0);
        }

        $users = $users->latest()->paginate(10);

        return view('admin.users.all', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if ($request->has('verify')) {
            $user->markEmailAsVerified();
        }

        alert()->success('ثبت با موفقیت انجام شد.', 'Message');
        return redirect(route('admin.users.index'))->with('success', 'ثبت کاربر انجام شد.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // ! Raveshe 1:
        // if (Gate::allows('edit-user', $user)) {

        //     return view('admin.users.edit', compact('user'));
        // }
        // abort(403);

        //! Raveshe 2 - Policy

        // $this->authorize('edit', $user);
        return view('admin.users.edit', compact('user'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        if (! is_null($request->password)) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed']
            ]);

            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        if($request->has('verify')) {
            $user->markEmailAsVerified();
        }

        return redirect(route('admin.users.index'))->with('success', 'ویرایش کاربر انجام شد.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        alert()->warning('Your account has been Deleted successfully', 'Deleted')->persistent('FFFFine');
        return redirect(route('admin.users.index'))->with('success', 'کاربر حذف شد.');
    }
}
