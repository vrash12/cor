<?php
// app/Http/Controllers/FarmerController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class FarmerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $u = auth()->user();
            if (!$u || strtolower($u->role) !== 'farmer') {
                abort(403, 'Only Farmers can access this section.');
            }
            // ensure farmer status approved
            $row = \Illuminate\Support\Facades\DB::table('farmer')->where('userid', $u->userid)->first();
            if (!$row || $row->status !== 'approved') {
                return redirect()->route('sell.pending')
                    ->with('error', 'Your farmer account is not approved yet.');
            }
            return $next($request);
        });
    }
    

   private function farmerId(): int
{
    return (int) \Illuminate\Support\Facades\DB::table('farmer')
        ->where('userid', \Illuminate\Support\Facades\Auth::user()->userid)
        ->value('farmerid');
}

    /* =========================================================
     |  PRODUCTS (CRUD)
     * =======================================================*/

    /** GET /farmer/products */
    public function showProducts()
    {
        $farmerId = $this->farmerId();

        $products = DB::table('product')
            ->where('farmerid', $farmerId)
            ->orderByDesc('productid')
            ->get();

return view('farmers.products.index', compact('products'));
    }

public function createProduct(Request $request)
{
$request->validate([
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
    'category' => 'nullable|string|max:255',
    'price' => 'required|numeric|min:0',
    'stock_quantity' => 'required|integer|min:0',
    'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
]);

$path = null;
if ($request->hasFile('image')) {
    $path = $request->file('image')->store('products', 'public'); // storage/app/public/products
}

DB::table('product')->insert([
    'farmerid'      => $this->farmerId(),
    'name'          => $request->name,
    'description'   => $request->description,
    'category'      => $request->category,
    'price'         => $request->price,
    'stockquantity' => $request->stock_quantity,
    'imageurl'      => $path ? 'storage/'.$path : null,
]);


    return redirect()->route('farmer.products.index')
                     ->with('success', 'Product added successfully!');
}


    /** GET /farmer/product/{productid}/edit */
    public function editProductForm(int $productid)
    {
        $product = DB::table('product')
            ->where('productid', $productid)
            ->where('farmerid', $this->farmerId())
            ->first();

        abort_if(!$product, 404);

    return view('farmers.products.edit', compact('product'));
    }

    /** PATCH /farmer/product/{productid} */
    public function updateProduct(Request $request, int $productid)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'category'        => 'nullable|string|max:255',
            'price'           => 'required|numeric|min:0',
            'stock_quantity'  => 'required|integer|min:0',
            'image_url'       => 'nullable|url|max:255',
        ]);

        $updated = DB::table('product')
            ->where('productid', $productid)
            ->where('farmerid', $this->farmerId())
            ->update([
                'name'          => $request->name,
                'description'   => $request->description,
                'category'      => $request->category,
                'price'         => $request->price,
                'stockquantity' => $request->stock_quantity,
                'imageurl'      => $request->image_url,
            ]);

        abort_if(!$updated, 404, 'Product not found or not yours.');

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product updated.');
    }

    /** DELETE /farmer/product/{productid} */
    public function deleteProduct(int $productid)
    {
        $deleted = DB::table('product')
            ->where('productid', $productid)
            ->where('farmerid', $this->farmerId())
            ->delete();

        abort_if(!$deleted, 404, 'Product not found or not yours.');

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product deleted.');
    }

    /* =========================================================
     |  INVENTORY
     * =======================================================*/

    /** PATCH /farmer/product/{productid}/inventory (delta can be + or -) */
    public function adjustInventory(Request $request, int $productid)
    {
        $request->validate([
            'delta' => 'required|integer', // +10 restock, -2 sale correction, etc.
        ]);

        $row = DB::table('product')
            ->where('productid', $productid)
            ->where('farmerid', $this->farmerId())
            ->first();

        abort_if(!$row, 404, 'Product not found or not yours.');

        $current = (int) ($row->stockquantity ?? 0);
        $new     = max(0, $current + (int) $request->delta);

        DB::table('product')
            ->where('productid', $productid)
            ->update(['stockquantity' => $new]);

        return back()->with('success', "Stock updated (from {$current} to {$new}).");
    }

    /** POST /farmer/inventory/bulk-restock  body: items: [{productid, qty}, ...] */
    public function bulkRestock(Request $request)
    {
        $request->validate([
            'items'               => 'required|array|min:1',
            'items.*.productid'   => 'required|integer',
            'items.*.qty'         => 'required|integer|min:1',
        ]);

        $farmerId = $this->farmerId();
        foreach ($request->items as $it) {
            $row = DB::table('product')
                ->where('productid', $it['productid'])
                ->where('farmerid', $farmerId)
                ->first();
            if ($row) {
                $new = max(0, (int)($row->stockquantity ?? 0) + (int)$it['qty']);
                DB::table('product')
                    ->where('productid', $it['productid'])
                    ->update(['stockquantity' => $new]);
            }
        }

        return back()->with('success', 'Bulk restock complete.');
    }

    /** GET /farmer/low-stock?threshold=10 */
    public function lowStock(Request $request)
    {
        $threshold = (int) $request->input('threshold', 10);
        $products = DB::table('product')
            ->where('farmerid', $this->farmerId())
            ->whereNotNull('stockquantity')
            ->where('stockquantity', '<=', $threshold)
            ->orderBy('stockquantity')
            ->get();

       return view('farmers.products.lowstock', compact('products', 'threshold'));
    }

    /* =========================================================
     |  ORDERS (simple: 1 farmer per order assumption)
     * =======================================================*/

    /** GET /farmer/orders */
    public function orders()
    {
        $farmerId = $this->farmerId();

        // Orders that include this farmer's products
        $orders = DB::table('order as o')
            ->join('orderitem as oi', 'oi.orderid', '=', 'o.orderid')
            ->join('product as p', 'p.productid', '=', 'oi.productid')
            ->leftJoin('customer as c', 'c.customerid', '=', 'o.customerid')
            ->leftJoin('user as u', 'u.userid', '=', 'c.userid')
            ->where('p.farmerid', $farmerId)
            ->select(
                'o.orderid', 'o.orderdate', 'o.status', 'o.totalamount',
                DB::raw('SUM(oi.quantity) as items_count'),
                DB::raw('SUM(oi.quantity * oi.price) as items_total'),
                'u.name as customer_name', 'u.email as customer_email'
            )
            ->groupBy('o.orderid','o.orderdate','o.status','o.totalamount','u.name','u.email')
            ->orderByDesc('o.orderdate')
            ->get();

        return view('farmer.orders.index', compact('orders'));
    }

    /** PATCH /farmer/orders/{orderid}/confirm?status=shipped|delivered */
    public function confirmDelivery(Request $request, int $orderid)
    {
        $status = strtolower($request->input('status', 'shipped'));
        if (!in_array($status, ['shipped','delivered','cancelled'])) {
            return back()->with('error', 'Invalid status.');
        }

        // sanity: ensure the order contains this farmer's products
        $has = DB::table('orderitem as oi')
            ->join('product as p', 'p.productid', '=', 'oi.productid')
            ->where('p.farmerid', $this->farmerId())
            ->where('oi.orderid', $orderid)
            ->exists();

        abort_if(!$has, 404, 'Order not found or not related to you.');

        DB::table('order')
            ->where('orderid', $orderid)
            ->update(['status' => $status]);

        return back()->with('success', "Order #{$orderid} marked as {$status}.");
    }

    /* =========================================================
     |  SALES REPORT
     * =======================================================*/

    /** GET /farmer/sales-report?from=YYYY-MM-DD&to=YYYY-MM-DD */
    public function salesReport(Request $request)
    {
        $from = $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay()
                                         : now()->subDays(30)->startOfDay();
        $to   = $request->filled('to')   ? Carbon::parse($request->input('to'))->endOfDay()
                                         : now()->endOfDay();

        $farmerId = $this->farmerId();

        // Daily aggregates for orders involving this farmer
        $daily = DB::table('orderitem as oi')
            ->join('product as p', 'p.productid', '=', 'oi.productid')
            ->join('order as o', 'o.orderid', '=', 'oi.orderid')
            ->where('p.farmerid', $farmerId)
            ->whereBetween('o.orderdate', [$from, $to])
            ->whereIn('o.status', ['shipped','delivered']) // count fulfilled sales
            ->selectRaw('DATE(o.orderdate) as day,
                         SUM(oi.quantity) as units,
                         SUM(oi.quantity * oi.price) as revenue')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $topProducts = DB::table('orderitem as oi')
            ->join('product as p', 'p.productid', '=', 'oi.productid')
            ->join('order as o', 'o.orderid', '=', 'oi.orderid')
            ->where('p.farmerid', $farmerId)
            ->whereBetween('o.orderdate', [$from, $to])
            ->whereIn('o.status', ['shipped','delivered'])
            ->select('p.productid','p.name', DB::raw('SUM(oi.quantity) as units'), DB::raw('SUM(oi.quantity * oi.price) as revenue'))
            ->groupBy('p.productid','p.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return view('farmer.reports.sales', [
            'from'        => $from->toDateString(),
            'to'          => $to->toDateString(),
            'daily'       => $daily,
            'topProducts' => $topProducts,
        ]);
    }

    /* =========================================================
     |  REVIEWS (reply – requires review.reply column)
     * =======================================================*/

    /** POST /farmer/reviews/{reviewid}/reply */
    public function respondToReview(Request $request, int $reviewid)
    {
        $request->validate(['reply' => 'required|string|max:1000']);

        // Ensure schema supports replies
        if (!Schema::hasColumn('review', 'reply')) {
            return back()->with('error', 'Review replies require a `reply` column in `review` table.');
        }

        // Check that the review is for one of this farmer's products
        $ok = DB::table('review as r')
            ->join('product as p', 'p.productid', '=', 'r.productid')
            ->where('p.farmerid', $this->farmerId())
            ->where('r.reviewid', $reviewid)
            ->exists();

        abort_if(!$ok, 404, 'Review not found for your products.');

        DB::table('review')->where('reviewid', $reviewid)->update([
            'reply' => $request->reply,
        ]);

        return back()->with('success', 'Reply posted.');
    }

    public function editProfile()
    {
        $farmer = DB::table('farmer')->where('farmerid', $this->farmerId())->first();
        return view('farmer.profile.edit', compact('farmer'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'farmname'      => 'nullable|string|max:255',
            'certification' => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'pickup_address' => 'nullable|string|max:255', // New field
            'business_name' => 'nullable|string|max:255',  // New field
            'registered_address' => 'nullable|string|max:255', // New field
            'taxpayer_id'   => 'nullable|string|max:32', // New field
            'seller_type'   => 'nullable|in:individual,partnership,corporation', // New field
        ]);
    
        // Update farmer details
        DB::table('farmer')->where('farmerid', $this->farmerId())->update([
            'farmname'      => $request->farmname,
            'certification' => $request->certification,
            'description'   => $request->description,
            'pickup_address' => $request->pickup_address, // New field
            'business_name' => $request->business_name,  // New field
            'registered_address' => $request->registered_address, // New field
            'taxpayer_id'   => $request->taxpayer_id,   // New field
            'seller_type'   => $request->seller_type,   // New field
        ]);
    
        return back()->with('success', 'Profile updated.');
    }

    public function showShopInfoForm()
{
    // Return the view for the Shop Info form
    return view('farmers.shopinfo');
}

public function submitShopInfoForm(Request $request)
{
    // Validate the Shop Info form fields
    $request->validate([
        'farmname' => 'required|string|max:255',
        'pickup_address' => 'nullable|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
    ]);

    // Insert or update the shop info for the farmer (you may store it in the `farmer` table or elsewhere)
    DB::table('farmer')
        ->where('userid', Auth::id())
        ->update([
            'farmname' => $request->farmname,
            'pickup_address' => $request->pickup_address,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

    // Redirect to the Business Info form after the Shop Info is filled out
    return redirect()->route('farmer.businessinfo');
}

public function submitBusinessInfoForm(Request $request)
{
    // Validate the Business Info form fields
    $request->validate([
        'business_name' => 'required|string|max:255',
        'registered_address' => 'nullable|string|max:255',
        'taxpayer_id' => 'nullable|string|max:32',
        'business_registration_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg',
        'proof_of_identity' => 'nullable|file|mimes:pdf,jpeg,png,jpg',
        'seller_type' => 'nullable|in:individual,partnership,corporation',
    ]);

    // Insert or update the business info for the farmer
    DB::table('farmer')
        ->where('userid', Auth::id())
        ->update([
            'business_name' => $request->business_name,
            'registered_address' => $request->registered_address,
            'taxpayer_id' => $request->taxpayer_id,
            'seller_type' => $request->seller_type,
        ]);

    // Redirect to a confirmation page or dashboard
    return redirect()->route('farmer.dashboard')->with('success', 'Your account is under review.');
}

}
