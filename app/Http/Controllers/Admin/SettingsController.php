<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;

class SettingsController extends Controller
{


    public function edit()
    {
        $dbsettings = Settings::all();
        $settings = [];
        foreach($dbsettings as $dbsetting){
            $settings[$dbsetting->name] = $dbsetting->value;
        }

        return view('admin.settings', ['settings' => $settings]);

    }

    public function update(Request $request)
    {
        $validatedData = request()->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'mail_server' => ['required', 'string', 'max:255'],
            'mail_port' => ['required', 'integer'],
            'mail_password' => ['nullable', 'string'],
            'ambulatory_duration' => ['required', 'integer'],
            'mail_name' => ['required', 'string', 'max:255'],
        ]);

        $updatedColumns = ['site_name', 'mail_server', 'mail_port', 'ambulatory_duration', 'mail_name'];
        foreach ($updatedColumns as $column) {
            Settings::where('name', $column)->update(['value' => request($column)]);
        }

        if (request('password')) {
            Settings::where('name', 'mail_password')->update(['value' => request('password')]);
        }

        return redirect(route('settings'));


    }
}
