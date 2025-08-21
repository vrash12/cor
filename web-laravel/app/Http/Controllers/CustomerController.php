<?php
// app/Http/Controllers/CustomerController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Only customers can access this controller
        $this->middleware(function ($request, $next) {
            $u = Auth::user();
            if (!$u || strtolower($u->role) !== 'customer') {
                abort(403, 'Only Customers can access this section.');
            }
            // ensure a customer row exists
            $exists = DB::table('customer')->where('userid', $u->userid)->exists();
            if (!$exists) {
                abort(403, 'Customer profile not found for this user.');
            }
            return $next($request);
        });
    }

    

    /** Resolve current customer's primary key (customer.customerid) */
    private function customerId(): int
    {
        $uid = Auth::user()->userid;
        return (int) DB::table('customer')->where('userid', $uid)->value('customerid');
    }

    /** Base query for products joined with farmer + user (for farmer name) */
    private function baseProductQuery()
    {
        return DB::table('product as p')
            ->leftJoin('farmer as f', 'f.farmerid', '=', 'p.farmerid')
            ->leftJoin('user as u', 'u.userid', '=', 'f.userid')
            ->select(
                'p.productid', 'p.farmerid', 'p.name', 'p.description', 'p.category',
                'p.price', 'p.stockquantity', 'p.imageurl',
                'u.name as farmer_name'
            );
    }

    /* =========================================================
     |  1.2.2.1 + 1.2.2.2  MARKETPLACE / SEARCH (Web)
     * =======================================================*/

    /** GET /customer/products?q=&category=  (web page) */
    public function showProducts(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $category = trim((string) $request->input('category', ''));

        $query = $this->baseProductQuery();

        if ($q !== '') {
            $query->where(function($w) use ($q) {
                $w->where('p.name', 'like', "%{$q}%")
                  ->orWhere('p.description', 'like', "%{$q}%")
                  ->orWhere('u.name', 'like', "%{$q}%");
            });
        }
        if ($category !== '') {
            $query->where('p.category', $category);
        }

        $products = $query->orderByDesc('p.productid')
                          ->paginate(12)
                          ->withQueryString();

        // categories for filter dropdown
        $categories = DB::table('product')
            ->whereNotNull('category')
            ->select('category')->distinct()->orderBy('category')->pluck('category');

        return view('customer.products.index', compact('products','categories','q','category'));
    }

    /** GET /customer/farmers/{farmerid} — farmer profile + their products (web) */
    public function viewFarmerProfile(int $farmerid)
    {
        $farmer = DB::table('farmer as f')
            ->leftJoin('user as u', 'u.userid', '=', 'f.userid')
            ->select('f.*', 'u.name as user_name', 'u.email', 'u.phone', 'u.address')
            ->where('f.farmerid', $farmerid)
            ->first();

        abort_if(!$farmer, 404, 'Farmer not found');

        $products = DB::table('product')
            ->where('farmerid', $farmerid)
            ->orderByDesc('productid')
            ->get();

        return view('customer.farmers.show', compact('farmer','products'));
    }

    /* =========================================================
     |  1.2.2.3 SETTINGS & NOTIFICATIONS (Web)
     * =======================================================*/

    /** GET /customer/settings */
    public function settings()
    {
        $user = Auth::user();

        // Optional per-customer prefs (push/email). Only read if table exists.
        $prefs = ['push_enabled' => true, 'email_enabled' => true];
        if (Schema::hasTable('customer_pref')) {
            $row = DB::table('customer_pref')->where('userid', $user->userid)->first();
            if ($row) {
                $prefs['push_enabled']  = (bool) ($row->push_enabled ?? true);
                $prefs['email_enabled'] = (bool) ($row->email_enabled ?? true);
            }
        }

        return view('customer.settings.edit', compact('user', 'prefs'));
    }

    /** POST /customer/settings */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string|max:255',
            'email_enabled'    => 'nullable|boolean',
            'push_enabled'     => 'nullable|boolean',
        ]);

        $user = Auth::user();

        // Update basic contact on user table
        DB::table('user')->where('userid', $user->userid)->update([
            'phone'   => $request->input('phone'),
            'address' => $request->input('address'),
        ]);

        // Optional prefs table
        if (Schema::hasTable('customer_pref')) {
            $exists = DB::table('customer_pref')->where('userid', $user->userid)->exists();
            $payload = [
                'userid'        => $user->userid,
                'push_enabled'  => (bool) $request->boolean('push_enabled'),
                'email_enabled' => (bool) $request->boolean('email_enabled'),
                'updated_at'    => Carbon::now(),
            ];
            if ($exists) {
                DB::table('customer_pref')->where('userid', $user->userid)->update($payload);
            } else {
                $payload['created_at'] = Carbon::now();
                DB::table('customer_pref')->insert($payload);
            }
        }

        return back()->with('success', 'Settings updated.');
    }

    /** GET /customer/notifications */
    public function notifications()
    {
        $uid = Auth::user()->userid;

        $items = [];
        if (Schema::hasTable('notification')) {
            $items = DB::table('notification')
                ->where('userid', $uid)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('customer.notifications.index', ['notifications' => $items]);
    }

    /** POST /customer/notifications/{id}/read */
    public function markNotificationRead(int $id)
    {
        if (Schema::hasTable('notification')) {
            DB::table('notification')->where('id', $id)->where('userid', Auth::user()->userid)->update([
                'read_at' => Carbon::now(),
            ]);
        }
        return back();
    }

    /** POST /customer/notifications/clear */
    public function clearNotifications()
    {
        if (Schema::hasTable('notification')) {
            DB::table('notification')->where('userid', Auth::user()->userid)->delete();
        }
        return back()->with('success', 'Notifications cleared.');
    }

    /* =========================================================
     |  1.2.2.4  HISTORY & TRANSACTIONS (Web)
     * =======================================================*/

    /** GET /customer/orders */
    public function orders()
    {
        $orders = DB::table('order')
            ->where('customerid', $this->customerId())
            ->orderByDesc('orderdate')
            ->get();

        return view('customer.orders.index', compact('orders'));
    }

    /** GET /customer/orders/{orderid} */
    public function showOrder(int $orderid)
    {
        $order = DB::table('order')
            ->where('orderid', $orderid)
            ->where('customerid', $this->customerId())
            ->first();

        abort_if(!$order, 404);

        $items = DB::table('orderitem as oi')
            ->join('product as p', 'p.productid', '=', 'oi.productid')
            ->leftJoin('farmer as f', 'f.farmerid', '=', 'p.farmerid')
            ->leftJoin('user as u', 'u.userid', '=', 'f.userid')
            ->where('oi.orderid', $orderid)
            ->select('oi.*', 'p.name as product_name', 'p.imageurl', 'u.name as farmer_name')
            ->get();

        $payments = DB::table('payment')->where('orderid', $orderid)->orderByDesc('paymentdate')->get();

        return view('customer.orders.show', compact('order','items','payments'));
    }

    /** POST /customer/orders/{orderid}/cancel (only if pending) */
    public function cancelOrder(int $orderid)
    {
        $customerId = $this->customerId();

        $order = DB::table('order')
            ->where('orderid', $orderid)
            ->where('customerid', $customerId)
            ->first();

        abort_if(!$order, 404);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        DB::transaction(function () use ($orderid) {
            // Restock
            $items = DB::table('orderitem')->where('orderid', $orderid)->get();
            foreach ($items as $it) {
                DB::table('product')
                    ->where('productid', $it->productid)
                    ->increment('stockquantity', $it->quantity);
            }
            // Cancel
            DB::table('order')->where('orderid', $orderid)->update(['status' => 'cancelled']);
        });

        return redirect()->route('customer.orders.show', $orderid)
            ->with('success', 'Order cancelled and items restocked.');
    }

    /* =========================================================
     |  PLACE ORDER (Web / Mobile shared)
     * =======================================================*/

    /**
     * POST /customer/order
     * Accepts either:
     *  - single: product_id, quantity
     *  - multi:  items = [{productid, qty}, ...]
     */
    public function placeOrder(Request $request)
    {
        // Normalize payload
        $items = [];
        if ($request->filled('product_id')) {
            $request->validate([
                'product_id' => 'required|integer|min:1',
                'quantity'   => 'required|integer|min:1',
            ]);
            $items = [[
                'productid' => (int) $request->input('product_id'),
                'qty'       => (int) $request->input('quantity'),
            ]];
        } else {
            $request->validate([
                'items'             => 'required|array|min:1',
                'items.*.productid' => 'required|integer|min:1',
                'items.*.qty'       => 'required|integer|min:1',
            ]);
            $items = array_values(array_filter(array_map(function($i){
                return [
                    'productid' => (int) ($i['productid'] ?? 0),
                    'qty'       => (int) ($i['qty'] ?? 0),
                ];
            }, $request->input('items', [])), fn($x) => $x['productid']>0 && $x['qty']>0));
        }

        if (empty($items)) {
            return back()->with('error', 'No items to order.');
        }

        $customerId = $this->customerId();

        $orderId = DB::transaction(function () use ($items, $customerId) {
            // Lock products to check stock and read price
            $ids = array_column($items, 'productid');
            $products = DB::table('product')->whereIn('productid', $ids)->lockForUpdate()->get()->keyBy('productid');

            $total = 0;
            foreach ($items as $it) {
                $p = $products[$it['productid']] ?? null;
                if (!$p) throw new \RuntimeException("Product #{$it['productid']} not found.");
                $currentStock = (int)($p->stockquantity ?? 0);
                if ($currentStock < $it['qty']) {
                    throw new \RuntimeException("Insufficient stock for {$p->name} (available: {$currentStock}).");
                }
                $total += (float)$p->price * (int)$it['qty'];
            }

            // Create order
            $orderId = DB::table('order')->insertGetId([
                'customerid'  => $customerId,
                'orderdate'   => Carbon::now(),
                'status'      => 'pending',
                'totalamount' => $total,
            ]);

            // Create items + decrement stock
            foreach ($items as $it) {
                $p = $products[$it['productid']];
                DB::table('orderitem')->insert([
                    'orderid'   => $orderId,
                    'productid' => $it['productid'],
                    'quantity'  => $it['qty'],
                    'price'     => $p->price, // snapshot
                ]);
                DB::table('product')
                    ->where('productid', $it['productid'])
                    ->decrement('stockquantity', $it['qty']);
            }

            return $orderId;
        });

        // If the client accepts HTML -> redirect. JSON -> return JSON.
        if ($request->wantsJson()) {
            return response()->json(['orderid' => $orderId, 'status' => 'pending'], 201);
        }

        return redirect()->route('customer.orders.show', $orderId)
            ->with('success', "Order #{$orderId} placed.");
    }

    /* =========================================================
     |  REVIEWS (Web)
     * =======================================================*/

    /** POST /customer/review/{productID} */
    public function reviewProduct(Request $request, int $productID)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        DB::table('review')->insert([
            'customerid' => $this->customerId(),
            'productid'  => $productID,
            'rating'     => (int) $request->rating,
            'comment'    => $request->comment,
            'reviewdate' => Carbon::now(),
        ]);

        return back()->with('success', 'Review added.');
    }

    /* =========================================================
     |  JSON ENDPOINTS FOR MOBILE (use in routes/api.php)
     * =======================================================*/

    /** GET /api/products?q=&category= */
    public function apiProducts(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $category = trim((string) $request->input('category', ''));

        $query = $this->baseProductQuery();

        if ($q !== '') {
            $query->where(function($w) use ($q) {
                $w->where('p.name', 'like', "%{$q}%")
                  ->orWhere('p.description', 'like', "%{$q}%")
                  ->orWhere('u.name', 'like', "%{$q}%");
            });
        }
        if ($category !== '') {
            $query->where('p.category', $category);
        }

        $rows = $query->orderByDesc('p.productid')->get();

        // Normalize image URLs (so Expo can render them easily)
        $rows->transform(function ($r) {
            if (!empty($r->imageurl) && !preg_match('#^https?://#i', $r->imageurl)) {
                $r->imageurl = url($r->imageurl);
            }
            return $r;
        });

        return response()->json($rows);
    }

    /** GET /api/notifications */
    public function apiNotifications()
    {
        $uid = Auth::user()->userid;

        $items = [];
        if (Schema::hasTable('notification')) {
            $items = DB::table('notification')
                ->where('userid', $uid)
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($n) {
                    return [
                        'id'        => $n->id,
                        'title'     => $n->title ?? 'Notice',
                        'body'      => $n->body ?? '',
                        'timestamp' => Carbon::parse($n->created_at ?? now())->diffForHumans(),
                        'unread'    => empty($n->read_at),
                    ];
                });
        }
        return response()->json($items);
    }

    /** GET /api/orders */
    public function apiOrders()
    {
        $cid = $this->customerId();

        $orders = DB::table('order as o')
            ->leftJoin('payment as pay', 'pay.orderid', '=', 'o.orderid')
            ->where('o.customerid', $cid)
            ->select(
                'o.orderid', 'o.orderdate', 'o.status', 'o.totalamount',
                DB::raw('COUNT(pay.paymentid) as payments_count')
            )
            ->groupBy('o.orderid','o.orderdate','o.status','o.totalamount')
            ->orderByDesc('o.orderdate')
            ->get();

        // attach item counts
        $ids = $orders->pluck('orderid');
        $counts = DB::table('orderitem')
            ->whereIn('orderid', $ids)
            ->select('orderid', DB::raw('SUM(quantity) as items_count'))
            ->groupBy('orderid')->pluck('items_count','orderid');

        $orders->transform(function ($o) use ($counts) {
            $o->items_count = (int) ($counts[$o->orderid] ?? 0);
            return $o;
        });

        return response()->json($orders);
    }

    /** GET /api/orders/{orderid} */
    public function apiOrderDetail(int $orderid)
    {
        $order = DB::table('order')
            ->where('orderid', $orderid)
            ->where('customerid', $this->customerId())
            ->first();

        abort_if(!$order, 404);

        $items = DB::table('orderitem as oi')
            ->join('product as p', 'p.productid', '=', 'oi.productid')
            ->leftJoin('farmer as f', 'f.farmerid', '=', 'p.farmerid')
            ->leftJoin('user as u', 'u.userid', '=', 'f.userid')
            ->where('oi.orderid', $orderid)
            ->select('oi.*', 'p.name as product_name', 'p.imageurl', 'u.name as farmer_name')
            ->get();

        $items->transform(function ($it) {
            if (!empty($it->imageurl) && !preg_match('#^https?://#i', $it->imageurl)) {
                $it->imageurl = url($it->imageurl);
            }
            return $it;
        });

        $payments = DB::table('payment')->where('orderid', $orderid)->orderByDesc('paymentdate')->get();

        return response()->json([
            'order'    => $order,
            'items'    => $items,
            'payments' => $payments,
        ]);
    }

    /** POST /api/order (mobile checkout) */
    public function apiPlaceOrder(Request $request)
    {
        // Reuse web logic but guarantee JSON response
        $request->headers->set('Accept', 'application/json');
        return $this->placeOrder($request);
    }

    /** POST /api/orders/{orderid}/cancel */
    public function apiCancelOrder(int $orderid)
    {
        $customerId = $this->customerId();

        $order = DB::table('order')
            ->where('orderid', $orderid)
            ->where('customerid', $customerId)
            ->first();

        abort_if(!$order, 404);

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Only pending orders can be cancelled.'], 422);
        }

        DB::transaction(function () use ($orderid) {
            $items = DB::table('orderitem')->where('orderid', $orderid)->get();
            foreach ($items as $it) {
                DB::table('product')
                    ->where('productid', $it->productid)
                    ->increment('stockquantity', $it->quantity);
            }
            DB::table('order')->where('orderid', $orderid)->update(['status' => 'cancelled']);
        });

        return response()->json(['ok' => true]);
    }
}
