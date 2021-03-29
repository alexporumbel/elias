<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MedicalSpeciality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SpecialityController extends Controller
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
        $specialities = MedicalSpeciality::all();
        return view('admin.specialities.index', [
            'specialities' => $specialities
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.specialities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validateSpeciality();

        $speciality = new MedicalSpeciality(request(['name', 'is_paid']));
        $speciality->name = request('name');
        $speciality->is_paid = request('is_paid') == 'on' ? 1 : 0;
        $speciality->save();

        return redirect(route('specialities.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MedicalSpeciality $speciality)
    {
        return view('admin.specialities.edit', [
            'speciality' => $speciality
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MedicalSpeciality $speciality)
    {
        $this->validateSpeciality();
        $speciality->name = request('name');
        $speciality->is_paid = request('is_paid') == 'on' ? 1 : 0;
        $speciality->update();
        return redirect(route('specialities.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicalSpeciality $speciality)
    {
        $speciality->delete();
        return back();
    }


    public function validateSpeciality()
    {
        return request()->validate([
            'name' => 'required',
            'is_paid'=>'alpha'
        ]);
    }
}
