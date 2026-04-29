<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doorman;
use Clarkeash\Doorman\Exceptions\DoormanException;
use App\Models\Invite;

class DoormanController extends Controller
{
    // Home page
    public function index()
    {
        return view('index');
    }

    // =========================
    // GENERATE SINGLE INVITE
    // =========================
    public function generateSingle()
    {
        $invite = Doorman::generate()->once();

        $dbInvite = Invite::where('code', $invite->code)->first();

        if ($dbInvite) {
            $dbInvite->update([
                'status' => 'active',
                'uses' => 0
            ]);
        }

        return view('result', [
            'message' => 'Single Invite Code Generated: ' . $invite->code
        ]);
    }

    // =========================
    // GENERATE MULTIPLE INVITES
    // =========================
    public function generateMultiple()
    {
        $invites = Doorman::generate()->times(5)->make();

        $codes = [];

        foreach ($invites as $invite) {

            $codes[] = $invite->code;

            $dbInvite = Invite::where('code', $invite->code)->first();

            if ($dbInvite) {
                $dbInvite->update([
                    'status' => 'active'
                ]);
            }
        }

        return view('result', [
            'message' => 'Multiple Invite Codes: ' . implode(', ', $codes)
        ]);
    }

    // =========================
    // EXPIRY INVITE
    // =========================
    public function generateExpiry()
    {
        $invite = Doorman::generate()
            ->expiresIn(7)
            ->once();

        $dbInvite = Invite::where('code', $invite->code)->first();

        if ($dbInvite) {
            $dbInvite->update([
                'status' => 'active',
                'valid_until' => now()->addDays(7)
            ]);
        }

        return view('result', [
            'message' => 'Invite Code with 7 days expiry: ' . $invite->code
        ]);
    }

    // =========================
    // EMAIL INVITE
    // =========================
    public function generateEmail()
    {
        $email = 'test@gmail.com';

        // check if already exists
        $existing = \DB::table('invites')->where('for', $email)->first();

        if ($existing) {
            return view('result', [
                'message' => 'Invite already exists for ' . $email . ' : ' . $existing->code
            ]);
        }

        $invite = Doorman::generate()
            ->for($email)
            ->once();

        return view('result', [
            'message' => 'Invite Code for ' . $email . ' : ' . $invite->code
        ]);
    }

    // =========================
    // REDEEM FORM
    // =========================
    public function redeemForm()
    {
        return view('redeem');
    }

    // =========================
    // REDEEM INVITE
    // =========================
    public function redeem(Request $request)
    {
        try {

            $invite = Invite::where('code', $request->code)->first();

            if (!$invite) {
                return view('result', [
                    'message' => 'Error: Invalid Invite Code'
                ]);
            }

            // expiry check
            if ($invite->valid_until && now()->greaterThan($invite->valid_until)) {

                $invite->update(['status' => 'expired']);

                return view('result', [
                    'message' => 'Error: Invite expired'
                ]);
            }

            Doorman::redeem(
                $request->code,
                $request->email
            );

            $invite->update([
                'status' => 'used',
                'uses' => $invite->uses + 1
            ]);

            return view('result', [
                'message' => 'Invite Redeemed Successfully'
            ]);
        } catch (DoormanException $e) {

            return view('result', [
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // =========================
    // INVITE DASHBOARD PAGE
    // =========================
    public function invitesPage()
    {
        $invites = Invite::orderBy('id', 'asc')->get();

        return view('invites', compact('invites'));
    }

    // =========================
    // LIVE SEARCH (AJAX)
    // =========================
    public function searchInvites(Request $request)
    {
        $query = $request->get('query');

        $invites = Invite::where('code', 'like', "%{$query}%")
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($invites);
    }
}
