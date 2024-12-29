<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\PasswordResetToken;
use App\Models\Role;
use App\Models\User;
use App\Models\LoginTracking;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Image;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
	public function login(Request $request)
	{

		if ($request->isMethod('post')) {

			$rules = [
				'email' => 'required|email|max:255',
				'password' => 'required',
			];
			$customMessages = [
				'email.required' => 'Email is required!',
				'email.email' => 'Valid Email Address is required!',
				'password.required' => 'Password is required',
			];

			$this->validate($request, $rules, $customMessages);
			try {

				if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {

					$user = User::find(auth('admin')->user()->id);
					$token = Str::random(56);

					$user->update([
						'token' => $token,
					]);

					return redirect()->route('admin.dashboard')->with('success', "Logged In successfully!");

				} else {
					return redirect()->back()->with('error_message', 'Invalid Email or Password'); 
				}
			} catch (\Throwable $exception) {
				return redirect()->back()->with('error_message', $exception->getMessage());
			}
		}

		if ($request->isMethod('get')) {
			if (Auth::guard('admin')->check()) {
				return redirect()->route('admin.dashboard');
			}

			return view('admin.login');
		}
	}

	public function googleLogin()
	{
		try {
			$googleUser = Socialite::driver('google')->user();
			//  dd($googleUser);
			$user = Admin::updateOrCreate([
				'google_id' => $googleUser->id,
			], [
				'google_id' => $googleUser->id,
				'name' => $googleUser->name,
				'email' => $googleUser->email,
				'token' => $googleUser->token,
				'refresh_token' => $googleUser->refreshToken,
				'image' => $googleUser->avatar,
				'loggedInFrom' => "google",
				'status' => 4, //4 for pending
				'email_verified_at' => now()
			]);

			Auth::guard('admin')->login($user);

			$admin = Admin::with('merchant')->find(auth('admin')->user()->id);
			
				if(isset($admin->role_slug)){
					session(['role_slug' => $admin->role_slug]);
				}
				
				if(isset($admin->merchant->type) && $admin->merchant->type=='both'){
						session(['merchant_role' => 'buyer']);
					}
					else{
						session(['merchant_role' => $admin->merchant->type ?? null]);
					}

			return redirect()->route('admin.dashboard')->with('success_message', 'Successfully logged in');
		} catch (\Throwable $exception) {


			return redirect()->route('login')->with('error_message', $exception->getMessage());
		}
	}

	public function signUp(Request $request)
	{
		if ($request->isMethod('post')) {
			$rules = [
				'email' => 'required|email|max:255|unique:admins',
				'password' => [
					'required',
					'string',
					'min:8',
					'regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^,&*])[A-Za-z\d!@#$%^&*]+$/'
				],
			];
			$customMessages = [
				'email.required' => 'Email is required!',
				'email.email' => 'Valid Email Address is required!',
				'email.unique' => 'The Email is already in use.',
				'password.required' => 'Password is required.',
				'password.min' => 'Password must be at least :min characters.',
				'password.confirmed' => 'Password confirmation does not match.',
				'password.regex' => 'Password must contain at least one uppercase, one number, and one special character',
			];

			$this->validate($request, $rules, $customMessages);
			try {
				$email_verify_token = Str::random(100);
				Admin::create([
					'name' => '',
					'email' => $request->email,
					'password' => bcrypt($request->password),
					'image' => '',
					'email_verify_token' => $email_verify_token,
					'role_slug' => "new_user",
					'status' => 4, //4 stands for pending
					'step' => 1
				]);
				$receiver_mail = $request->email;
				$subject = "Verify Your Email - Threadwel";
				$mail_body = [
					"name" => "",
					"link" => env('APP_URL') . "/email-verify/" . $email_verify_token,
				];
				// dd($mail_body['link']);
				// $replyToAddress = "thread.tech.ext@gmail.com";
				// $replyToName = "Thread";
				// $fromAddress = "thread.tech.ext@gmail.com";
				// $fromName = "Thread";

				$replyToAddress = "noreply@threadwel.com";
				$replyToName = "No Reply";
				$fromAddress = "noreply@threadwel.com";
				$fromName = "Threadwel";
				
				dispatch(new SendEmailJob($receiver_mail, $subject, $fromAddress, $fromName, $replyToAddress, $replyToName, $mail_body, 'verifyEmail'));

				return redirect()->route('email_verify', ['email' => $request->email]);
				// return view('admin.auth.EmailVerify');

			} catch (\Throwable $exception) {

				return redirect()->back()->with('error_message', $exception->getMessage());
			}
		}
		return view('admin.signUp');
	}

	public function resendEmailVerify($email)
	{
			try {
				$admin =  Admin::where('email',$email)->select('email_verify_token')->first();
				$email_verify_token = $admin->email_verify_token;

				$receiver_mail = $email;
				$subject = "Verify Your Email - Threadwel";
				$mail_body = [
					"name" => "",
					"link" => env('APP_URL') . "/email-verify/" . $email_verify_token,
				];
				
				// $replyToAddress = "thread.tech.ext@gmail.com";
				// $replyToName = "Thread";
				// $fromAddress = "thread.tech.ext@gmail.com";
				// $fromName = "Thread";

				$replyToAddress = "noreply@threadwel.com";
				$replyToName = "No Reply";
				$fromAddress = "noreply@threadwel.com";
				$fromName = "Threadwel";
				dispatch(new SendEmailJob($receiver_mail, $subject, $fromAddress, $fromName, $replyToAddress, $replyToName, $mail_body, 'verifyEmail'));

				return redirect()->route('email_verify', ['email' => $email])->with('success_message', 'Email resent successfully');

			} catch (\Throwable $exception) {

				return redirect()->back()->with('error_message', $exception->getMessage());
			}
	}

	public function emailVerify($token)
	{
		try {
			// dd("email verify ".$token);
			$admin = Admin::where('email_verify_token', $token)->first();
			// dd($admin);
			if ($admin && empty($admin->email_verified_at)) {
				$admin->email_verified_at = now();
				$admin->save();

				Auth::guard('admin')->login($admin);
				return redirect()->route('onboarding.step1')->with('success_message', 'Email verified successfully');
				// return redirect()->route('admin.dashboard')->with('success_message', 'Email verified successfully');
			} else if ($admin && !empty($admin->email_verified_at)) {
				return redirect()->route('login')->with('error_message', 'Email already verified');
			} else {
				return redirect()->route('login')->with('error_message', 'Invalid link');
			}
		} catch (\Throwable $exception) {
			return redirect()->back()->with('error_message', $exception->getMessage());
		}
	}

	public function forgetPassword(Request $request)
	{

		if ($request->isMethod('post')) {
			$rules = [
				'email' => 'required|email|max:255',
			];
			$customMessages = [
				'email.required' => 'Email is required!',
				'email.email' => 'Valid Email Address is required!'
			];

			$this->validate($request, $rules, $customMessages);

			try {
				if (Admin::where('email', $request->email)->exists()) {
					$token = Str::random(150);
					$passReset = PasswordResetToken::updateOrCreate([
						'email' => $request->email,
					], [
						'email' => $request->email,
						'token' => $token,
						'created_at' => now()
					]);

					$receiver_mail = $request->email;
					$subject = "Reset Your Password - Threadwel";
					$mail_body = [
						"name" => "",
						"link" => env('APP_URL', 'http://127.0.0.1:8000') . "/reset-password/" . $token,
					];
					// dd($mail_body['link']);
					// $replyToAddress = "thread.tech.ext@gmail.com";
					// $replyToName = "Thread";
					// $fromAddress = "thread.tech.ext@gmail.com";
					// $fromName = "Thread";

					$replyToAddress = "noreply@threadwel.com";
					$replyToName = "No Reply";
					$fromAddress = "noreply@threadwel.com";
					$fromName = "Threadwel";
					dispatch(new SendEmailJob($receiver_mail, $subject, $fromAddress, $fromName, $replyToAddress, $replyToName, $mail_body, 'resetPass'));
					// return redirect()->route('email_verify', ['email' => $request->email]);
					return view('admin.auth.forget_pass')->with('email', $request->email);
				} else {
					$msg = "This email: " . $request->email . " is not registered in our database. Signup! to use our services";
					return redirect()->back()->with('error_message', $msg);
				}
			} catch (\Throwable $exception) {
				return redirect()->back()->with('error_message', $exception->getMessage());
			}
		}
		return view('admin.auth.email_send_forgot_pass');
	}

	public function resetPassword(Request $request, $token)
	{
		// dd($token);
		try {

			if ($request->isMethod('get')) {
				$passReset = PasswordResetToken::where('token', $request->token)->first();
				if (!empty($passReset) && Carbon::parse($passReset->created_at)->diffInMinutes(now()) < 60) {
					return view('admin.auth.reset_pass')->with(['token' => $request->token, 'email' => $passReset->email]);
				} else {
					return redirect()->route('forget.pass')->with('error_message', "The link is expired or invalid!! You can resend a request");
				}
			}

			if ($request->isMethod('post')) {
				$rules = [
					'password' => 'required|min:6|confirmed',
				];
				$customMessages = [
					'password.required' => 'Password is required!',
					'password.min' => 'Password should be more than 6 charecters!',
				];
				$this->validate($request, $rules, $customMessages);
				$passReset = PasswordResetToken::where('token', $request->token)->first();
				if ($passReset) {
					$admin = Admin::where('email', $request->email)->first();
					// dd($admin);
					$admin->password = bcrypt($request->password);
					// dd(Hash::make($request->password));
					$admin->save();
					$passReset->delete();
					return redirect()->route('login')->with('success_message', "Password reset successful. You can login with new password");
				} else {
					return redirect()->back()->with('error_message', "Token is invalid");
				}
			}
		} catch (\Throwable $exception) {
			return redirect()->back()->with('error_message', $exception->getMessage());
		}
	}

	public function logout()
	{
		try {
			$user = Auth::guard('admin')->user();
			$user->update([
				'token' => null
			]);
			Auth::guard('admin')->logout();
			Session::flush();

			return redirect()->route('login');
		} catch (\Throwable $exception) {
			return redirect()->back()->with('error_message', $exception->getMessage());
		}
	}

	public function checkAdminPassword(Request $request)
	{
		if (Hash::check($request->current_password, Auth::guard('admin')->user()->password)) {
			return response()->json(['data' => true]);
		} else {
			return response()->json(['data' => false]);
		}
	}

	public function changeAdminPassword(Request $request)
	{
			$rules = [
				'current_password' => 'required',
				'password' => 'required|min:6|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',
			];
			$customMessages = [
				'current_password.required' => 'Current password is required!',
				'password.required' => 'Password is required!',
				'password.min' => 'Password should be more than 6 charecters!',
				'password.regex' => 'Password must contain at least one uppercase, one number, and one special character',
				'password.confirmed' => 'New password and confirmed password do not match.',
			];
			$this->validate($request, $rules, $customMessages);
			try {
				//check if Current password entered by admin is correct
				if (Hash::check($request->current_password, Auth::guard('admin')->user()->password)) {
					Admin::where('id', Auth::guard('admin')->user()->id)->update(['password' => bcrypt($request->password)]);
					return redirect()->back()->with('success_message', 'Your Password has been updated!');
				} else {
					return redirect()->back()->with('error_message', 'Your Current Password is Incorrect!');
				}
			} catch (\Throwable $exception) {
				return redirect()->back()->with('error_message', $exception->getMessage());
			}
	}
}
