<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends Controller
{
    /**
     * Show registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
            'is_verified' => false,
        ]);

        // Generate and send OTP
        $otp = Otp::generate($user->email, 'verification');
        
        // Send OTP via email
        try {
            Mail::send('emails.otp', ['otp' => $otp, 'name' => $user->name], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Verifikasi Email - CineWave');
            });
        } catch (Throwable $e) {
            Log::error('Failed to send OTP email (register)', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['email' => 'Gagal mengirim email OTP. Cek konfigurasi email, lalu coba lagi.'])
                ->withInput();
        }

        ActivityLog::logActivity($user->id, 'register', 'User registered successfully');

        return redirect()->route('otp.verify', ['email' => $user->email])
                        ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk kode OTP.');
    }

    /**
     * Show login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        if (!$user->is_verified) {
            return back()->withErrors(['email' => 'Email Anda belum diverifikasi. Silakan verifikasi terlebih dahulu.'])->withInput();
        }

        auth()->login($user, $request->remember);

        ActivityLog::logActivity($user->id, 'login', 'User logged in successfully');

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    /**
     * Show OTP verification form.
     */
    public function showOtpVerify(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    /**
     * Verify OTP.
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $otp = Otp::where('email', $request->email)
                  ->where('otp', $request->otp)
                  ->where('type', 'verification')
                  ->where('is_used', false)
                  ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->withInput();
        }

        if (!$otp->isValid()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa.'])->withInput();
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        $otp->markAsUsed();

        ActivityLog::logActivity($user->id, 'email_verified', 'Email verified successfully');

        auth()->login($user);

        return redirect()->route('home')->with('success', 'Email berhasil diverifikasi!');
    }

    /**
     * Resend OTP.
     */
    public function resendOtp(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $otp = Otp::generate($user->email, 'verification');
        
        try {
            Mail::send('emails.otp', ['otp' => $otp, 'name' => $user->name], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Verifikasi Email - CineWave');
            });
        } catch (Throwable $e) {
            Log::error('Failed to send OTP email (resend)', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Silakan coba lagi nanti.']);
        }

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    /**
     * Logout.
     */
    public function logout()
    {
        ActivityLog::logActivity(auth()->id(), 'logout', 'User logged out');
        auth()->logout();
        return redirect()->route('login');
    }
}
