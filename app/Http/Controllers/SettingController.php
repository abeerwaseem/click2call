<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Http\Requests\Setting\UpdateRequest;
use Illuminate\Http\Request;

class SettingController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Settings";
        $data['settings'] = Setting::first();
        
        return view('settings.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Setting\UpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateRequest $request)
    {
        $request->process();

        return redirect(route('add-setting'));
    }

}
