<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doorman;
use Carbon\Carbon;
use Clarkeash\Doorman\Exceptions\DoormanException;

class DoormanController extends Controller
{

    // Show Home Page
    public function index()
    {
        return view('index');
    }


    // Generate single invite
    public function generateSingle()
    {
        $invite = Doorman::generate()->once();

        return view('result', [
            'message' => 'Single Invite Code Generated: ' . $invite->code
        ]);
    }


    // Generate multiple invites
    public function generateMultiple()
    {
        $invites = Doorman::generate()
            ->times(5)
            ->make();

        $codes = [];

        foreach ($invites as $invite) {
            $codes[] = $invite->code;
        }

        return view('result', [
            'message' => 'Multiple Invite Codes: ' . implode(', ', $codes)
        ]);
    }


    // Generate invite with expiry
    public function generateExpiry()
    {
        $invite = Doorman::generate()
            ->expiresIn(7)
            ->once();

        return view('result', [
            'message' => 'Invite Code with 7 days expiry: ' . $invite->code
        ]);
    }


    // Generate invite with email
    public function generateEmail()
    {
        $invite = Doorman::generate()
            ->for('test@gmail.com')
            ->once();

        return view('result', [
            'message' => 'Invite Code for test@gmail.com: ' . $invite->code
        ]);
    }


    // Show redeem form
    public function redeemForm()
    {
        return view('redeem');
    }


    // Redeem invite
    public function redeem(Request $request)
    {

        try {

            Doorman::redeem(
                $request->code,
                $request->email
            );

            return view('result', [
                'message' => 'Invite Redeemed Successfully'
            ]);

        } catch (DoormanException $e) {

            return view('result', [
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

}