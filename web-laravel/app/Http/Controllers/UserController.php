<?php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use DB
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Redirect based on the role of the user
            $user = Auth::user();
            switch ($user->role) {
                case 'Farmer':
                    return redirect()->route('farmer.products.index');
                case 'Customer':
                    return redirect()->route('customer.products.index');
                case 'CooperativeAdmin':
                    return redirect()->route('admin.dashboard');
                default:
                    return redirect()->route('home');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
    // Show the registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|in:Farmer,Customer,CooperativeAdmin',
            // Add validation for the new fields
            'pickup_address' => 'nullable|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'registered_address' => 'nullable|string|max:255',
            'taxpayer_id' => 'nullable|string|max:32',
            'business_registration_certificate' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:10240',
            'proof_of_identity' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:10240',
            'seller_type' => 'nullable|in:individual,partnership,corporation',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('register')
                             ->withErrors($validator)
                             ->withInput();
        }
    
        // Create the user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = $request->role;
        $user->save();
    
        // If the user is a Farmer, create their Farmer profile
        if ($request->role == 'Farmer') {
            DB::table('farmer')->insert([
                'userid' => $user->userid,
                'status' => 'pending', // Farmer is pending approval
            ]);
    
            // Redirect the user to the Shop Info form
            return redirect()->route('farmer.shopinfo');
        }
    
        Auth::login($user);
    
        return redirect()->route('home');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
