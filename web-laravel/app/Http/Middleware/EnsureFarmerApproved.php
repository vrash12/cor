<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnsureFarmerApproved
{
    public function handle(Request $request, Closure $next)
    {
        $u = Auth::user();
        if (!$u || strtolower($u->role) !== 'farmer') {
            abort(403, 'Only Farmers can access this section.');
        }
        $row = DB::table('farmer')->where('userid', $u->userid)->first();
        if (!$row || $row->status !== 'approved') {
            return redirect()->route('sell.pending')
                ->with('error', 'Your farmer application is not approved yet.');
        }
        return $next($request);
    }
}
