<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doorman;
use Carbon\Carbon;
use Clarkeash\Doorman\Exceptions\DoormanException;
use Clarkeash\Doorman\Models\Invite;

class DoormanController extends Controller
{

    // Show Home Page
    public function index()
    {
        return view('index');
    }

    // Invites Dashboard Page
    public function invitesPage()
    {
        $invites = Invite::orderBy('created_at', 'desc')->paginate(10);
        $stats = $this->getStats();
        return view('invites', compact('invites', 'stats'));
    }

    // Get statistics
    private function getStats()
    {
        $total = Invite::count();
        
        // Check what columns exist
        $columns = \Schema::getColumnListing('invites');
        
        // Active invites (not used)
        if(in_array('used', $columns)) {
            $active = Invite::where('used', 0)->count();
            $redeemed = Invite::where('used', '>', 0)->count();
        } elseif(in_array('uses', $columns)) {
            $active = Invite::where('uses', 0)->count();
            $redeemed = Invite::where('uses', '>', 0)->count();
        } else {
            $active = $total;
            $redeemed = 0;
        }
        
        // Expired invites (if expires_at column exists)
        if(in_array('expires_at', $columns)) {
            $expired = Invite::where('expires_at', '<', Carbon::now())
                ->where(function($query) use ($columns) {
                    if(in_array('used', $columns)) {
                        $query->where('used', 0);
                    } elseif(in_array('uses', $columns)) {
                        $query->where('uses', 0);
                    }
                })->count();
        } else {
            $expired = 0;
        }
        
        // Last 7 days invites
        $last7Days = [];
        for($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Invite::whereDate('created_at', $date->toDateString())->count();
            $last7Days[] = [
                'date' => $date->format('M d'),
                'count' => $count
            ];
        }
        
        return compact('total', 'active', 'redeemed', 'expired', 'last7Days');
    }

    // Live Search Invites
    public function searchInvites(Request $request)
    {
        $search = $request->get('search');
        
        $invites = Invite::query()
            ->when($search, function($query, $search) {
                return $query->where('code', 'like', "%{$search}%")
                    ->orWhere('for', 'like', "%{$search}%")
                    ->orWhere('created_at', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        if($request->ajax()) {
            return view('partials.invites-table', compact('invites'))->render();
        }
        
        $stats = $this->getStats();
        return view('invites', compact('invites', 'stats'));
    }

    // Delete single invite
    public function deleteInvite($id)
    {
        try {
            $invite = Invite::findOrFail($id);
            $invite->delete();
            return response()->json(['success' => true, 'message' => 'Invite deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting invite'], 500);
        }
    }

    // Bulk delete invites
    public function bulkDeleteInvites(Request $request)
    {
        try {
            $ids = $request->get('ids', []);
            Invite::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => count($ids) . ' invites deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting invites'], 500);
        }
    }

    // Export invites to CSV
    public function exportInvites(Request $request)
    {
        $search = $request->get('search');
        
        $invites = Invite::query()
            ->when($search, function($query, $search) {
                return $query->where('code', 'like', "%{$search}%")
                    ->orWhere('for', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'invites_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($invites) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['ID', 'Code', 'Email', 'Max Uses', 'Used', 'Created At', 'Status']);
            
            foreach($invites as $invite) {
                $used = $invite->used ?? $invite->uses ?? 0;
                $maxUses = $invite->max_uses ?? 1;
                $status = ($used >= $maxUses) ? 'Redeemed' : 'Active';
                
                fputcsv($file, [
                    $invite->id,
                    $invite->code,
                    $invite->for ?? 'Not specified',
                    $maxUses,
                    $used,
                    $invite->created_at,
                    $status
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // Get invite details for modal
    public function getInviteDetails($id)
    {
        try {
            $invite = Invite::findOrFail($id);
            
            $used = $invite->used ?? $invite->uses ?? 0;
            $maxUses = $invite->max_uses ?? 1;
            $status = ($used >= $maxUses) ? 'Redeemed' : 'Active';
            
            return response()->json([
                'success' => true,
                'invite' => [
                    'id' => $invite->id,
                    'code' => $invite->code,
                    'email' => $invite->for ?? 'Not specified',
                    'max_uses' => $maxUses,
                    'used' => $used,
                    'created_at' => Carbon::parse($invite->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($invite->updated_at)->format('Y-m-d H:i:s'),
                    'status' => $status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invite not found'], 404);
        }
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

        foreach($invites as $invite)
        {
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