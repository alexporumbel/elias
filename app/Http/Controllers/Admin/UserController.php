<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalSpeciality;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
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
        $users = User::all();
        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $specialities = MedicalSpeciality::all();
        return view('admin.users.create', [
            'specialities' => $specialities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validatedData = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'user_id' => ['nullable', 'integer'],
            'speciality_id' => ['nullable', 'integer'],
            'working_plan' => ['nullable', 'json'],
            'is_admin' => ['nullable', 'integer'],
        ]);

        $user = User::create([
            'name' => request('name'),
            'lname' => request('lname'),
            'email' => request('email'),
            'email_verified_at' => now(),
            'password' => Hash::make(request('password')),
        ]);

        if(request('speciality') != null) {
            UserSettings::create([
                'user_id' => $user->id,
                'speciality_id' => request('speciality'),
                'working_plan' => request('working_plan'),
                'is_admin' => request('is_admin'),
            ]);
        }else{
            UserSettings::create([
                'user_id' => $user->id,
                'working_plan' => request('working_plan'),
                'is_admin' => 1,
            ]);
        }

        return redirect(route('users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $specialities = MedicalSpeciality::all();
        return view('admin.users.edit', [
            'user' => $user,
            'specialities' => $specialities
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        $validatedData = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name = request('name');
        $user->lname = request('lname');
        $user->email = request('email');
        if (request('password')) {
            $user->password = Hash::make(request('password'));
            }
        $user->update();

        $userSettings = $user->settings;

        if(request('speciality') !== 'null'){
            $userSettings->speciality_id = request('speciality');
            $userSettings->is_admin = request('is_admin') == 'on' ? 1 : 0;
            $userSettings->working_plan = request('working_plan');
            $userSettings->update();
        }else{
            $userSettings->speciality_id = null;
            $userSettings->is_admin =  1;
            $userSettings->working_plan = null;
            $userSettings->update();
        }

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back();
    }


}
