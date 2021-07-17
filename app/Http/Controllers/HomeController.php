<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        //Inja mishe, Tuye Web.php ham mishe ezafe kard (Taide Email)
        // $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function comment(Request $request)
    {
        // if(! $request->ajax()) {
        //     return response()->json([
        //         'status' => 'Just ajax request would be accepted'
        //     ]);
        // }

        $data = $request->validate([
            'comment' => 'required',
            'commentable_id' => 'required',
            'commentable_type' => 'required',
            'parent_id' => 'required'
        ]);

        Auth::user()->comments()->create($data);

        // return response()->json([
        //     'status' => 'success'
        // ]);

        alert()->success('نظر شما با موفقیت ثبت شد و پس از تایید، نمایش داده می شود.')->persistent('باشه');
        return back()->with('success', 'نظر ثبت شد.');
    }
}
