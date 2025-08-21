<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerOnboardingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Helper: get or create (in-memory) current farmer row */
    private function getFarmerRow()
    {
        $uid = Auth::user()->userid;
        return DB::table('farmer')->where('userid', $uid)->first();
    }

    /** Step 1: Shop Info (GET) */
    public function showShopInfoForm()
    {
        $u = Auth::user();
        $farmer = $this->getFarmerRow();

        // If already approved -> go to farmer dashboard
        if ($farmer && $farmer->status === 'approved') {
            return redirect()->route('farmer.products.index');
        }

        return view('sell.shop-info', [
            'user'   => $u,
            'farmer' => $farmer,
        ]);
    }

    /** Step 1: Shop Info (POST) */
    public function submitShopInfo(Request $request)
    {
        $request->validate([
            'farmname'      => 'required|string|max:255',
            'description'   => 'nullable|string',
            'certification' => 'nullable|string|max:255',
        ]);

        $uid = Auth::user()->userid;
        $existing = DB::table('farmer')->where('userid', $uid)->first();

        if ($existing) {
            DB::table('farmer')->where('farmerid', $existing->farmerid)->update([
                'farmname'      => $request->farmname,
                'description'   => $request->description,
                'certification' => $request->certification,
                // keep status as-is (could be pending/rejected)
            ]);
        } else {
            // create initial application; start as pending
            DB::table('farmer')->insert([
                'userid'        => $uid,
                'cooperativeid' => null,
                'farmname'      => $request->farmname,
                'certification' => $request->certification,
                'description'   => $request->description,
                'status'        => 'pending',
            ]);

            // (optional) immediately mark their role as farmer; they still can't sell until approved
            DB::table('user')->where('userid', $uid)->update(['role' => 'farmer']);
        }

        // Proceed to step 2
        return redirect()->route('sell.business')->with('success', 'Shop info saved. Continue with business info.');
    }

    /** Step 2: Business Info (GET) */
    public function showBusinessInfoForm()
    {
        $u = Auth::user();
        $farmer = $this->getFarmerRow();

        if (!$farmer) {
            return redirect()->route('sell.shop')->with('error', 'Please fill up Shop Info first.');
        }
        if ($farmer->status === 'approved') {
            return redirect()->route('farmer.products.index');
        }

        $coops = DB::table('cooperative')
            ->select('cooperativeid','name')
            ->orderBy('name')
            ->get();

        return view('sell.business-info', compact('farmer','coops','u'));
    }

    /** Step 2: Business Info (POST) */
    public function submitBusinessInfo(Request $request)
    {
        $request->validate([
            'cooperativeid' => 'nullable|integer|exists:cooperative,cooperativeid',
            'businesspermit'=> 'nullable|string|max:100',
            'tin'           => 'nullable|string|max:32',
        ]);

        $uid = Auth::user()->userid;
        $farmer = DB::table('farmer')->where('userid', $uid)->first();
        if (!$farmer) {
            return redirect()->route('sell.shop')->with('error', 'Please fill up Shop Info first.');
        }

        DB::table('farmer')->where('farmerid', $farmer->farmerid)->update([
            'cooperativeid' => $request->cooperativeid ?: null,
            'businesspermit'=> $request->businesspermit,
            'tin'           => $request->tin,
            'status'        => 'pending', // ensure pending while waiting for admin review
        ]);

        // (optional) guarantee role is 'farmer'
        DB::table('user')->where('userid', $uid)->update(['role' => 'farmer']);

        return redirect()->route('sell.pending')->with('success', 'Application submitted. Please wait for admin approval.');
    }

    /** Waiting / Pending page */
    public function pending()
    {
        $farmer = $this->getFarmerRow();
        if ($farmer && $farmer->status === 'approved') {
            return redirect()->route('farmer.products.index');
        }
        return view('sell.pending', compact('farmer'));
    }
}
