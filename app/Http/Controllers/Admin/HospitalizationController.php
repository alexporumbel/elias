<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospitalization;
use App\Models\MedicalSpeciality;
use App\Models\UserSettings;
use Illuminate\Http\Request;

class HospitalizationController extends Controller
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
        $hospitalizations = Hospitalization::all();
        $types = ['trimitere' => 'Cu bilet de trimitere',
            'revenire' => 'Cu recomandare de revenire',
            'plata' => 'Cu plata'];
        $hospitalizationTypes = ['zi' => 'Spitalizare de zi',
            'continua' => 'Spitalizare continua'];
        return view('admin.hospitalization.index', ['hospitalizations'=> $hospitalizations, 'types' => $types, 'hospitalizationTypes' => $hospitalizationTypes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $specialities = MedicalSpeciality::all();
        $medics = UserSettings::whereNotNull('speciality_id')->get();
        return view('admin.hospitalization.create', [
            'specialities' => $specialities,
            'medics' => $medics,
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
            'medic' => ['required', 'integer'],
            'selectedDate' => ['required', 'string'],
            'appointmentType' => ['required', 'string'],
            'hospitalizationType' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $user = Hospitalization::create([
            'name' => request('name'),
            'lname' => request('lname'),
            'email' => request('email'),
            'phone' => request('phone'),
            'user_provider_id' => request('medic'),
            'start_datetime' => new \DateTime(request('selectedDate') + ' 8:00'),
            'appointment_type' => request('appointmentType'),
            'hospitalization_type' => request('hospitalizationType'),
            'notes' => request('notes'),
        ]);
        return redirect(route('hospitalization'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hospitalization $hospitalization)
    {
        $hospitalization->delete();
        return back();
    }
}
