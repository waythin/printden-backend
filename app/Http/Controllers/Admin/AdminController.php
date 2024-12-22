<?php

namespace App\Http\Controllers\Admin;

use DB;
use Image;
use Carbon\Carbon;
use DataTables;
use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
	public function updateAdminDetails(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			// dd($data);
			$rules = [
				'email' => 'required|email|unique:admins,email,' . Auth::guard('admin')->user()->id,
				'mobile' => 'required|min:11|max:14|regex:/^(?:\+?88)?01[13-9]\d{8}$/'
			];
			$customMessages = [
				'email.required' => 'Email is required!',
				'email.email' => 'Valid Email is required!',
				'email.unique' => 'This Email already in use with one of our user!',
				'mobile.regex' => 'Valid Mobile is required!',
			];
			$this->validate($request, $rules, $customMessages);
			try {
				//Upload Admin Photo
				if ($request->hasFile('photo')) {
					$image_tmp = $request->file('photo');
					if ($image_tmp->isValid()) {
						$extension = $image_tmp->getClientOriginalExtension();
						$imgName = rand(111, 99999) . '.' . $extension;
						$imagePath = 'admin/img/profile/' . $imgName;
						Image::make($image_tmp)->save($imagePath);
						$imgName = $imagePath;
						if (file_exists($data['current_image'])) {
							unlink($data['current_image']);
						}
					}
				} else if (!empty($data['current_image'])) {
					$imgName = $data['current_image'];
				} else {
					$imgName = "";
				}

				Admin::where('id', Auth::guard('admin')->user()->id)
					->update(
						[
							'email' => $data['email'],
							'mobile' => $data['mobile'],
							'image' => $imgName
						]
					);
				return redirect()->back()->with('success_message', 'Admin Details has been updated Successfully!');
			} catch (\Throwable $exception) {
				return redirect()->back()->with('error_message', $exception->getMessage());
			}
		}
	}

	public function dashboard()
	{

		// dd("hi");
		$title = 'Dashboard';

		// dd($members);
		return view('admin.dashboard')->with(compact('title'));
	}
}
