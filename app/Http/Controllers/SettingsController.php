<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;

class SettingsController extends Controller
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
        return redirect('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        $settings = Config::get('settings.' . $id);
        return view('settings.show', compact(['settings', 'id']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        $settings = Config::get('settings.' . $id);
        return view('settings.edit', compact(['settings', 'id']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }


        $settings = Config::get('settings.' . $id);
        foreach ($settings as $key => $val):
            $input = $request->input($key);
            if ($input != $val):
                self::updateDotEnv(strtoupper($id) . '.' . strtoupper($key), $input);
            endif;
        endforeach;
        return redirect()->route('settings.edit', $id)
            ->with('success', 'Settings updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
