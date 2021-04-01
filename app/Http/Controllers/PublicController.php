<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ambulatory;
use App\Models\MedicalSpeciality;
use App\Models\Recovery;
use App\Models\RecoverySeries;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class PublicController extends Controller
{

    public function createAmbulatory()
    {
        $specialities = MedicalSpeciality::all();
        return view('welcome', [
            'specialities' => $specialities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAmbulatory(MailController $mailController)
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
        $medic = User::where('id', request('medic'))->first();
        $speciality = MedicalSpeciality::where('id', request('speciality'))->first();
        $mailController->sendMail($start_datetime, request('name'), request('lname'), request('medic'), 'ambulatory');
        return redirect()->route('homepage')->with('success',"Esti programat pe  ". $start_datetime->format('d.m.Y') ." ora ". $start_datetime->format('H:i') ." la ". $speciality->name .", Dr. ". $medic->name ." ". $medic->lname);
    }

    public function createRecovery()
    {
        $series = RecoverySeries::all();
        return view('publicRecovery', [
            'series' => $series,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRecovery()
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
        return redirect()->route('publicRecovery')->with('success','Locul tau a fost rezervat!');
    }

}



