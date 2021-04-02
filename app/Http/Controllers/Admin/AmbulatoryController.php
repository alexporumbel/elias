<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
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
        $types = ['trimitere' => 'Cu bilet de trimitere',
            'control' => 'Consultatie de control',
            'cronic' => 'Afectiune cronica',
            'plata' => 'Cu plata'];
        return view('admin.ambulatory.index', ['ambulatories'=> $ambulatories, 'types' => $types]);
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
    public function store(MailController $mailController)
    {
        $validatedData = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'min:10'],
            'speciality' => ['required', 'integer'],
            'medic' => ['required', 'integer'],
            'selectedDate' => ['required', 'string'],
            'appointmentType' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
        $start_datetime = new \DateTime(request('selectedDate'));
        $end_datetime= new \DateTime(request('selectedDate'));
        $end_datetime->modify('+20 minutes');

        $user = Ambulatory::create([
            'name' => request('name'),
            'lname' => request('lname'),
            'email' => request('email'),
            'phone' => request('phone'),
            'speciality_id' => request('speciality'),
            'user_provider_id' => request('medic'),
            'appointment_type' => request('appointmentType'),
            'notes' => request('notes'),
            'start_datetime'=> $start_datetime,
            'end_datetime'=> $end_datetime,
        ]);

        $mailController->sendMail($start_datetime, request('name'), request('lname'), request('medic'), 'ambulatory');

        return redirect()->route('ambulatory')->with('success','Programarea a fost adaugata');
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
        return redirect()->back()->with('warning', 'Programarea a fost stearsa');
    }
}
