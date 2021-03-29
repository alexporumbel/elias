<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ambulatory;
use App\Models\MedicalSpeciality;
use Illuminate\Http\Request;

class AmbulatoryController extends Controller
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
        $ambulatories = Ambulatory::all();
        return view('admin.ambulatory.index', ['ambulatories'=> $ambulatories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $specialities = MedicalSpeciality::all();
        return view('admin.ambulatory.create', [
            'specialities' => $specialities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ambulatory $ambulatory)
    {
        $ambulatory->delete();
        return back();
    }
}
