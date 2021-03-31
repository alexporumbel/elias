<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospitalization;
use App\Models\Recovery;
use App\Models\RecoverySeries;
use Illuminate\Http\Request;

class RecoveryController extends Controller
{
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
        $recovery = Recovery::all();
        return view('admin.recovery.index', ['recoveries'=> $recovery]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $series = RecoverySeries::all();
        return view('admin.recovery.create', [
            'series' => $series,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validatedData = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'min:10'],
            'appointmentType' => ['required', 'string'],
            'series' => ['required', 'integer'],
        ]);

        $user = Recovery::create([
            'name' => request('name'),
            'lname' => request('lname'),
            'email' => request('email'),
            'phone' => request('phone'),
            'start_date' => RecoverySeries::where('id', request('series'))->first()->start_date,
            'end_date' => RecoverySeries::where('id', request('series'))->first()->end_date,
            'appointment_type' => request('appointmentType'),
        ]);
        return redirect(route('recovery'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recovery $recovery)
    {
        $recovery->delete();
        return back();
    }
}
