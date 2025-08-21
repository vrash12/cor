<?php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        // must be logged in AND role must be cooperativeadmin
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user || strtolower($user->role) !== 'cooperativeadmin') {
                abort(403, 'Only Cooperative Admins can access this page.');
            }
            return $next($request);
        });
    }

    /**
     * GET /admin/dashboard
     */
    public function showDashboard(Request $request)
    {
        // High-level stats
        $stats = [
            'farmers'   => DB::table('farmer')->count(),
            'customers' => DB::table('customer')->count(),
            'products'  => DB::table('product')->count(),
            'orders'    => DB::table('order')->count(),
        ];

        // Orders by status (pending/shipped/delivered/cancelled)
        $orderStatus = DB::table('order')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Low stock products (<= 10)
        $lowStock = DB::table('product')
            ->select('productid', 'name', 'stockquantity')
            ->whereNotNull('stockquantity')
            ->where('stockquantity', '<=', 10)
            ->orderBy('stockquantity')
            ->limit(10)
            ->get();

        // Recent orders with customer name/email (latest 10)
        $recentOrders = DB::table('order as o')
            ->leftJoin('customer as c', 'c.customerid', '=', 'o.customerid')
            ->leftJoin('user as u', 'u.userid', '=', 'c.userid')
            ->select(
                'o.orderid',
                'o.orderdate',
                'o.status',
                'o.totalamount',
                'u.name as customer_name',
                'u.email as customer_email'
            )
            ->orderByDesc('o.orderdate')
            ->limit(10)
            ->get();

        // Top products by sold quantity (from orderitem)
        $topProducts = DB::table('orderitem as oi')
            ->join('product as p', 'p.productid', '=', 'oi.productid')
            ->select('p.productid', 'p.name', DB::raw('SUM(oi.quantity) as sold'))
            ->groupBy('p.productid', 'p.name')
            ->orderByDesc('sold')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'orderStatus', 'lowStock', 'recentOrders', 'topProducts'));
    }
    public function farmerApplications()
    {
        $apps = DB::table('farmer as f')
            ->join('user as u', 'u.userid', '=', 'f.userid')
            ->leftJoin('cooperative as c', 'c.cooperativeid', '=', 'f.cooperativeid')
            ->where('f.status', 'pending')
            ->select('f.*', 'u.name as user_name', 'u.email', 'u.phone', 'u.address', 'c.name as coop_name')
            ->orderByDesc('f.farmerid')
            ->get();

        return view('admin.farmers.applications', compact('apps'));
    }

    /** Approve */
    public function approveFarmer(int $farmerid)
    {
        $f = DB::table('farmer')->where('farmerid', $farmerid)->first();
        if (!$f) abort(404);

        DB::table('farmer')->where('farmerid', $farmerid)->update(['status' => 'approved']);
        // ensure user role is 'farmer'
        DB::table('user')->where('userid', $f->userid)->update(['role' => 'farmer']);

        return back()->with('success', "Farmer #{$farmerid} approved.");
    }

    /** Reject */
    public function rejectFarmer(int $farmerid)
    {
        $f = DB::table('farmer')->where('farmerid', $farmerid)->first();
        if (!$f) abort(404);

        DB::table('farmer')->where('farmerid', $farmerid)->update(['status' => 'rejected']);
        // (optional) demote back to customer
        DB::table('user')->where('userid', $f->userid)->update(['role' => 'customer']);

        return back()->with('success', "Farmer #{$farmerid} rejected.");
    }
}
