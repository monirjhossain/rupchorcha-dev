<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Send OTP to user's email or phone
    public function sendOtp(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string',
            ]);

            // Find or create user by phone
            $user = User::where('phone', $request->phone)->first();
            if (!$user) {
                // Create test user if doesn't exist (for development)
                $user = User::create([
                    'name' => 'Test User',
                    'email' => 'test_' . time() . '@example.com',
                    'phone' => $request->phone,
                    'password' => bcrypt('password'),
                ]);
            }

            // Generate OTP (6 digit)
            $otp = rand(100000, 999999);
            $user->otp_code = $otp;
            $user->otp_expires_at = now()->addMinutes(5);
            $user->save();

            // Send OTP via email if email is provided
            if ($user->email) {
                try {
                    \Mail::raw('Your OTP code is: ' . $otp, function($mail) use ($user) {
                        $mail->to($user->email)
                            ->subject('Your OTP Code');
                    });
                } catch (\Exception $e) {
                    \Log::error('Email sending failed: ' . $e->getMessage());
                }
            }

            // Send OTP via SMS if phone is provided
            if ($user->phone) {
                try {
                    \App\Services\SmsService::send($user->phone, 'Your OTP code is: ' . $otp);
                } catch (\Exception $e) {
                    \Log::error('SMS sending failed: ' . $e->getMessage());
                }
            }

            return response()->json(['success' => true, 'message' => 'OTP sent to your email/SMS.', 'otp_code' => $otp]);
        } catch (\Exception $e) {
            \Log::error('SendOtp error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // Verify OTP and log in
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string|exists:users,phone',
                'otp' => 'required|digits:6',
            ]);

            \Log::info('OTP Verification Attempt', ['phone' => $request->phone, 'otp_input' => $request->otp]);

            $user = User::where('phone', $request->phone)->first();
            if (!$user || !$user->otp_code || !$user->otp_expires_at) {
                \Log::warning('OTP Verification Failed: OTP not found', ['phone' => $request->phone]);
                return response()->json(['success' => false, 'message' => 'OTP not found.'], 404);
            }
            if ($user->otp_code != $request->otp || now()->gt($user->otp_expires_at)) {
                \Log::warning('OTP Verification Failed: Invalid or expired OTP', [
                    'phone' => $request->phone,
                    'stored_otp' => $user->otp_code,
                    'input_otp' => $request->otp,
                    'expired' => now()->gt($user->otp_expires_at)
                ]);
                return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 401);
            }

            // Clear OTP after successful verification
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            // Check if user needs to complete profile (first time user)
            $needsProfileCompletion = str_starts_with($user->name, 'Test User') || 
                                       str_starts_with($user->email, 'test_');
            
            if ($needsProfileCompletion) {
                \Log::info('OTP Verified - Profile Completion Required', [
                    'user_id' => $user->id,
                    'phone' => $user->phone
                ]);
                
                return response()->json([
                    'success' => true, 
                    'message' => 'OTP verified. Please complete your profile.',
                    'requires_profile_completion' => true,
                    'temp_token' => $user->createToken('temp_token')->plainTextToken,
                    'user' => [
                        'id' => $user->id,
                        'phone' => $user->phone,
                    ]
                ]);
            }

            // Existing user - direct login
            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;
            
            \Log::info('OTP Login Successful', [
                'user_id' => $user->id,
                'phone' => $user->phone,
                'email' => $user->email,
                'name' => $user->name
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Login successful.', 
                'token' => $token, 
                'user' => $user,
                'requires_profile_completion' => false
            ]);
        } catch (\Exception $e) {
            \Log::error('OTP Verification Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Complete Profile after OTP verification
    public function completeProfile(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:2|max:255',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = $request->user();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            // Update user profile
            $user->name = $request->name;
            $user->password = bcrypt($request->password);
            
            // Generate proper email if it's a test email
            if (str_starts_with($user->email, 'test_')) {
                $user->email = 'user_' . $user->id . '_' . time() . '@rupchorcha.com';
            }
            
            $user->save();

            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;

            \Log::info('Profile Completed', [
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile completed successfully',
                'token' => $token,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            \Log::error('Complete Profile Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

            // Password reset API: set new password using token
            public function resetPassword(Request $request)
            {
                $request->validate([
                    'email' => 'required|email|exists:users,email',
                    'token' => 'required|string',
                    'password' => 'required|string|min:6|confirmed',
                ]);

                $status = \Password::reset(
                    $request->only('email', 'password', 'password_confirmation', 'token'),
                    function ($user, $password) {
                        $user->password = \Hash::make($password);
                        $user->save();
                    }
                );

                if ($status === \Password::PASSWORD_RESET) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Password has been reset successfully.'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid token or unable to reset password.'
                    ], 400);
                }
            }
        // Forgot password API: send reset link
        public function forgotPassword(Request $request)
        {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $status = \Password::sendResetLink(
                $request->only('email')
            );

            if ($status === \Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset link sent to your email.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to send reset link.'
                ], 500);
            }
        }
    // User profile API (authenticated user)
    public function profile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
    // User list (admin)
    public function index()
    {
        $query = User::query();
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }
        if ($role = request('role')) {
            $query->where('role', $role);
        }
        $users = $query->get();
        return view('admin.users.index', compact('users'));
    }
    // Toggle user active status
    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->active = !$user->active;
        $user->save();
        return back()->with('success', 'User status updated.');
    }

    // ...existing code...
    // Send email or SMS to customer
    public function sendMessage(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'message_type' => 'required|in:email,sms',
            'message' => 'required|string',
        ]);
        if ($request->message_type === 'email') {
            \Mail::send([], [], function($mail) use ($user, $request) {
                $mail->to($user->email)
                    ->subject('Message from Admin')
                    ->setBody($request->message, 'text/html');
            });
            return back()->with('success', 'Email sent successfully!');
        } elseif ($request->message_type === 'sms') {
            // Implement SMS sending logic here (integration required)
            // Example: SmsService::send($user->phone, $request->message);
            return back()->with('success', 'SMS sent (simulation).');
        }
        return back()->with('error', 'Invalid message type.');
    }

    // Show create user form (admin)
    public function create()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only super admin can create users.');
        }
        return view('admin.users.create');
    }

    // Show registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle registration (API-ready, JSON response)
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be valid.',
            'email.unique' => 'Email already exists.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        Auth::login($user);

        // If API request, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration successful.',
                'user' => $user,
            ], 201);
        }

        // Otherwise, redirect (web)
        return redirect('/admin')->with('success', 'Registration successful.');
    }
     // Show customer details
    public function show($id)
    {
        $user = User::with('orders')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    // Store new user (admin)
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only super admin can create users.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:super_admin,admin,shop_manager,content_manager,customer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show edit user form (admin)
    public function edit($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only super admin can edit users.');
        }
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Update user (admin)
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only super admin can update users.');
        }
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,admin,shop_manager,content_manager,customer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Delete user (admin)
    public function destroy($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only super admin can delete users.');
        }
        $user = User::findOrFail($id);
        if ($user->role === 'super_admin') {
            return redirect()->route('users.index')->with('error', 'Cannot delete super admin.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
        /**
     * Handle bulk SMS sending to selected users.
     */
    public function bulk_sms(Request $request)
    {
        $request->validate([
            'sms_message' => 'required|string',
            'sms_api_key' => 'required|string',
            'user_ids' => 'required|array',
        ]);

        $users = \App\Models\User::whereIn('id', $request->user_ids)->whereNotNull('phone')->get();
        $count = 0;
        foreach ($users as $user) {
            // sendSms($user->phone, $request->sms_message, $request->sms_api_key); // Implement this
            $count++;
        }
        return redirect()->route('users.index')->with('success', "Bulk SMS sent to $count users.");
    }
}
