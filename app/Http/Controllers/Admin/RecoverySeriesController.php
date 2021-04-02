<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\RecoverySeries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RecoverySeriesController extends Controller
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
        $recoveryseries = RecoverySeries::all();
        return view('admin.recoverySeries.index', [
            'recoveryseries' => $recoveryseries
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.recoverySeries.create');
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
            'start_datetime' => ['required', 'string', 'max:10'],
            'end_datetime' => ['required', 'string', 'max:10'],
            'capacity' => ['required', 'integer'],
        ]);

        $user = RecoverySeries::create([
            'series' => request('name'),
            'start_date' => request('start_datetime'),
            'end_date' => request('end_datetime'),
            'capacity' => request('capacity'),
        ]);

        return redirect()->route('recoveryseries.index')->with('success','Seria a fost adaugata');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RecoverySeries $recoveryseries)
    {
        return view('admin.recoverySeries.edit', [
            'recoveryseries' => $recoveryseries
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RecoverySeries $recoveryseries)
    {
        $validatedData = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_datetime' => ['required', 'string', 'max:10'],
            'end_datetime' => ['required', 'string', 'max:10'],
            'capacity' => ['required', 'integer'],
        ]);

        $recoveryseries->series = request('name');
        $recoveryseries->start_date = request('start_datetime');
        $recoveryseries->end_date = request('end_datetime');
        $recoveryseries->capacity = request('capacity');
        $recoveryseries->update();
        return redirect()->route('recoveryseries.index')->with('info','Seria a fost modificata');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecoverySeries $recoveryseries)
    {
        $recoveryseries->delete();
        return redirect()->back()->with('warning', 'Seria a fost stearsa');
    }



}
