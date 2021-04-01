<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class MailController extends Controller
{

    public function sendMail($dateTime, $name, $lname, $medic, $type)
    {
        $date = $dateTime->format('d.m.Y');
        $hour = $dateTime->format('H:i');
        $medic = User::where('id', $medic)->first();
        $type = $type === 'ambulatory' ? 'in ambulatoriu':'pentru spitalizare';
        Mail::raw("Ai o programare noua $type pe $date la ora $hour pacient $name $lname", function ($message) use ($medic, $date, $hour, $type){
            $message->to($medic->email)->subject("Programare noua $type $date $hour");
        });
    }

}



