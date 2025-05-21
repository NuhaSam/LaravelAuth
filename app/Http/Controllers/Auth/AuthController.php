<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Mail\SendResetLinkMail;
use App\Mail\VerifyUserEmailMail;
use App\Models\Role;
use App\Models\Session;
use App\Models\User;
use App\Services\PhoneVerificationService;
use Exception;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(public PhoneVerificationService $phoneVerificationService) {}

    public function register(RegisterRequest $request)
    {
        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'otp_code' => $otp,
            // 'role' => $request->role
        ]);

        Mail::to($user->email)->send(new VerifyUserEmailMail($otp, $user->email));

        return redirect()->route('verifyAccount')->with('success', 'Register successfully');
    }
    public function registerPage()
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }
    public function login(LoginRequest $request)
    {
        $field = filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        $credentials = [$field => $request->identifier, 'password' => $request->password];

        $user = User::where($field, $request->identifier)->first();

        if ($user->email_verified_at == null) {
            return redirect()->route('verifyAccountPage', ['identifier' => $request->identifier])->with('warning', 'you should to verfify your email before you are login.');
        }
        if (Auth::attempt($credentials, $request->has('remember'))) {
            return redirect()->route('profile', ['user' => $user])->with('success', 'Logged in successfully!');
        }

        return back()->with('error', 'Invalid credentials. Please try again.');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login')->with('success', 'Your are Logout!');
    }
    public function logoutDevice(Session $session)
    {
        $session->delete();
        return redirect()->back()->with('success', 'Logout Succesfully!');
    }

    public function verifyAccountPage(HttpRequest $request)
    {
        return view('auth.verifyAccount', ['identifier' => $request->identifier]);
    }
    public function verifyAccount(HttpRequest $request)
    {
        $request->validate([
            'otp' => "required|string",
            'identifier' => 'required|string',
        ]);

        $type =  filter_var($request->input('identifier'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($type, $request->identifier)->first();

        if ($request->otp != $user->otp_code) {
            session('error', 'INVALID');
            return view('auth.verifyAccount', ['identifier' => $request->identifier])->with('error', 'Invalid OTP Code! ');
        }
        $user->update([
            'email_verified_at' => now(),
        ]);
        $user->save();

        Auth::login($user);

        return redirect()->route('profile', ['user' => $user])->with('success', 'Account Verified Successfully!');
    }
    public function profile($user)
    {
        if (Auth::id() != $user) abort(403, 'unauthorized');
        $user = User::find(Auth::id());
        return view('auth.profile', compact('user'));
    }
    public function sendOtp(HttpRequest $request)
    {
        $type =  filter_var($request->input('identifier'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($type, $request->identifier)->first();

        if ($user->email_verified_at != null) {
            return redirect()->route('login')->with('success', 'your account Already Verified!');
        }
        if ($request->method == 'email') {
            $user->otp_code = rand(100000, 999999); // Example: Generate a 6-digit OTP
            $user->save();

            Mail::to($user->email)->send(new VerifyUserEmailMail($user->otp_code, $user->email));

            return redirect()->route('verifyAccount', ['identifier' => $request->identifier])->with('success', 'An OTP Code was sent to Your Email ');
        }

        if ($user->phone_number == '' || !$user->phone_number) {
            return redirect()->back()->with('error', 'Enter Phone Number to Use it in Verification ');
        }
        try {
            $response = $this->phoneVerificationService->sendOtpMessage($user->phone_number, $user->otp_code);
            if ($response->failed()) {
                dd($response->status(), $response->body());

                return "no send"  . $user;
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('verifyAccount', $request->method == 'email' ? $user->email : $user->phone_number);
    }
    public function change_password(ChangePasswordRequest $request)
    {
        $user = User::find(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return Redirect()->back()->with('error', 'Incorect Password');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);
        Auth::login($user);

        return redirect()->route('profile', ['user' => $user])->with('success', 'Password Changes Successfully!');
    }
    public function update_profile(UpdateProfileRequest $request)
    {
        $user = User::find(Auth::id());

        $user->update($request->validated());

        return redirect()->route('profile', ['user' => $user])->with('success', 'Your Profile Updated Successfully');
    }
    public function forget_password(ForgetPasswordRequest $request)
    {
        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now(),
            ],
        );
        Mail::to($request->email)->send(new SendResetLinkMail($token));
        return redirect()->route('reset-password', ['token' => $token])->with('success', 'OTP Code Has been sent to your email address.');
    }
    public function reset_password(ResetPasswordRequest $request)
    {
        $result =  DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$result) {
            return redirect()->back()->with('error', 'INVLID');
        }
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        $user = User::where('email', $request->email)->first();

        $user->update([
            'password' => Hash::make($request->password),
        ]);
        Auth::login($user);

        Auth::logoutOtherDevices();

        return redirect()->route('profile', ['user' => $user])->with('success', 'Password Reset successfully!');
    }
    public function google_redirect()
    {
        return Socialite::driver('google')->redirect();
    }
    public function google_callback()
    {
        $user = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            [
                'email' => $user->getEmail(),
            ],
            [
                'name' => $user->getName() ?? $user->getNickname() ?? 'Unkown',
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => now(),
                'otp_code' => rand(100000, 999999),
            ]
        );

        Auth::login($user);

        return redirect()->route('profile', ['user' => $user])->with('success', 'You Are In');
    }
    public function github_redirect()
    {
        return Socialite::driver('github')->redirect();
    }
    public function github_callback()
    {
        $user = Socialite::driver('github')->user();

        $user = User::firstOrCreate(
            [
                'email' => $user->getEmail(),
            ],
            [
                'name' =>  $user->getName() ?? $user->getNickname() ?? 'Unknown',
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => now(),
                'otp_code' => rand(100000, 999999),
            ]
        );

        Auth::login($user);

        return redirect()->route('profile', ['user' => $user])->with('success', 'You Are In');
    }

    public function facebook_redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function facebook_callback()
    {
        $user = Socialite::driver('facebook')->user();

        $user = User::firstOrCreate(
            [
                'email' => $user->getEmail(),
            ],
            [
                'name' =>  $user->getName() ?? $user->getNickname() ?? 'Unknown',
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => now(),
                'otp_code' => rand(100000, 999999),
            ]
        );

        Auth::login($user);

        return redirect()->route('profile', ['user' => $user])->with('success', 'You Are In');
    }
    public function redirect(string $driver)
    {
        if (!in_array($driver, ['google', 'github', 'facebook'])) {
            return redirect()->route('login')->with('error', 'Invalid driver');
        }
        return Socialite::driver($driver)->redirect();
    }
    public function callback(string $driver)
    {
        $user = Socialite::driver($driver)->user();
        $user = User::firstOrCreate(
            [
                'email' => $user->getEmail(),
            ],
            [
                'name' =>  $user->getName() ?? $user->getNickname() ?? 'Unknown',
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => now(),
                'otp_code' => rand(100000, 999999),
            ]
        );

        Auth::login($user);
        return redirect()->route('profile', ['user' => $user])->with('success', 'You Are In');
    }
}
