<?php

namespace App\Http\Controllers\Admin;

use Str;
use Image;
use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
	public function settings($type = null)
	{

		try {
			$title = '';
			$merchant_id = Auth::guard('admin')->user()->merchant_id;

			 if ($type == 'account-settings') {
				$title = 'Account Settings';

				return view('admin.settings.account_settings')->with(compact('title'));
			}

			return view('admin.settings.settings')->with(compact('title'));
		} catch (\Throwable $exception) {

			return redirect()->back()->with('error_message', $exception->getMessage());
		}
	}

	public function isAdmin(Request $request){
		try{

			
			$adminId = $request->input('admin_id');
			$id = (int) $adminId;
			
			$admin = Admin::find($adminId);
			if($admin){
				$admin->update([
					'is_admin' => 1,
				]);
				return response()->json([
					'success' => true,
					'data' => [],
					'success_message' => "Information Updated",
					'code' => 200,
				]); 
			}
			return response()->json([
				'success' => false,
				'data' => [],
				'error_message' => "Admin no found",
				'code' => 400,
			]); 

		}catch (\Throwable $exception) {
			return response()->json([
				'success' => false,
				'data' => [],
				'error_message' => "Something went wrong",
				'code' => 400,
			]); 
		}

	}
}
