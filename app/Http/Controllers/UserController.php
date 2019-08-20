<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\EditUserRequest;

class UserController extends Controller
{   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['users'] = User::where('is_admin', 0)->get();

        return view('users.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $data['title'] = "Add User";

        return view('users.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\AddUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddUserRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => \Hash::make("Password@1"),
        ]);

        $user->sip()->create([
                                'sip_username' => $request->input('sip_username'),
                                'sip_password' => $request->input('sip_password'),
                                'sip_address'  => $request->input('sip_address'),
                                'sip_auth'     => md5(microtime()),
                            ]);

        return redirect(route('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {   
        $data['user'] = $user;
        return view('users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\AddUserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(EditUserRequest $request, User $user)
    {
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->sip->sip_username = $request->input('sip_username');
        $user->sip->sip_password = $request->input('sip_password');
        $user->sip->sip_address = $request->input('sip_address');
        $user->push();

        return redirect(route('users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        // redirect
        \Session::flash('message', 'Successfully deleted the user!');
        return redirect(route('users'));
    }
}
