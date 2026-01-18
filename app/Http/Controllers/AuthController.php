<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact administrator.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function verify_email($email, $userData)
    {
        $verificationToken = bin2hex(random_bytes(32));
        
        // Store user data temporarily in cache (expires in 1 hour)
        Cache::put("registration_{$verificationToken}", $userData, now()->addHour());
        
        $verifyUrl = route('auth.verify', ['token' => $verificationToken]);

        $mailContent = (new MailtrapEmail())
            ->from(new Address('support@cls-360.com', 'CLS-360 Support'))
            ->to(new Address($email))
            ->subject('Verify Your Email Address')
            ->category('Email Verification')
            ->text("Please verify your email by visiting: {$verifyUrl}")
            ->html("
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <h1 style='color: #333;'>Email Verification</h1>
                    <p>Click the button below to verify your email and complete your registration:</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='{$verifyUrl}' style='display: inline-block; padding: 15px 40px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;'>Verify Email</a>
                    </p>
                    <p style='color: #666; font-size: 12px;'>This link expires in 1 hour.</p>
                </div>
            ");

        $response = MailtrapClient::initSendingEmails(
            apiKey: env('MAILTRAP_API_KEY')
        )->send($mailContent);

        return ResponseHelper::toArray($response);
    }

    /**
     * Handle email verification and complete registration.
     */
    public function verifyAndCreateAccount($token)
    {
        $userData = Cache::get("registration_{$token}");

        if (!$userData) {
            return redirect()->route('auth.register')
                ->withErrors(['email' => 'Verification link is invalid or has expired.']);
        }

        // Remove the cached data
        Cache::forget("registration_{$token}");

        $clientRole = Role::where('slug', 'client')->first();

        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'contact_no' => $userData['contact_no'] ?? null,
            'password' => Hash::make($userData['password']),
            'role_id' => $clientRole?->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Email verified! Your account has been created successfully.');
    }


    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'contact_no' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Send verification email with user data
        $this->verify_email($validated['email'], $validated);

        return redirect()->route('auth.login')
            ->with('success', 'A verification email has been sent. Please check your inbox and click the verify button to complete registration.');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
