<?php

namespace App\Http\Controllers\Admin;

use DB;
use Image;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Admin;
use App\Models\Order;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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
						// Image::make($image_tmp)->save($imagePath);
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
		// dd(Auth::guard('admin')->user());
		$title = 'Dashboard';

		$orders = Order::orderBy('id', 'desc')->limit(5)->get();

		$totalOrders = Order::count();
		$deliveredOrders = Order::where('status', 'delivered')->count();
		$totalCustomers = Customer::count();
		return view('admin.dashboard')->with(compact('title', 'orders', 'totalOrders', 'deliveredOrders', 'totalCustomers'));
	}

	public function contactUsSubmit(Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				'name' => 'required',
				'email' => 'required|email',
				'phone' => 'required',
				'message' => 'required'
			],
			[]
		);
		if ($validator->fails()) {
			return $this->responseWithError(null, $validator->errors(), 402);
		}
		try {
			$data = ContactUs::create([
				'name' => $request->name,
				'email' => $request->email,
				'phone' => $request->phone,
				'message' => $request->message
			]);

			return $this->responseWithSuccess($data, 'Form submitted successfully!');
		} catch (\Throwable $exception) {
			return $this->responseWithError(null, $exception->getMessage());
		}
	}


	// public function contactUsList()
	// {
	// 	$contactUs = ContactUs::orderBy('id', 'desc')->get();
	// 	return view('admin.contact_us.index', compact('contactUs'));
	// }



	public function contactDatatables()
	{
		$contactUs = ContactUs::orderBy('id', 'desc')->get();
		return DataTables::of($contactUs)

			->addColumn('updated_date', function ($data) {
				$data = $data->updated_at->format('d-m-Y | h:i:s A'); // Format: Day-Month-Year Hour:Minute:Second AM/PM
				return $data;
			})
			->addColumn('status', function ($data) {
				$statuses = ['pending', 'responded'];
				$dropdown = '<select class="form-control contact-status-dropdown" data-id="' . $data->id . '">';
				foreach ($statuses as $status) {
					$selected = $data->status === $status ? 'selected' : '';
					$dropdown .= '<option value="' . $status . '" ' . $selected . '>' . ucfirst($status) . '</option>';
				}
				$dropdown .= '</select>';
				return $dropdown;
			})
			->addColumn('date', function ($data) {
                $date = $data->created_at->format('d-m-Y | h:i:s A'); // Format: Day-Month-Year Hour:Minute:Second AM/PM
                return $date;
            })
			->rawColumns(['status', 'updated_date'])
			->make(true);
	}


	public function contactUsList()
    {
        $title = 'Contact Us';
        return view('admin.contact.contact', compact('title'));
    }


	public function Customers()
	{
		$title = 'Ordered Customers';
		return view('admin.customer.customer')->with(compact('title'));
	}
	

	public function customerDatatables()
	{
		$customer = Customer::orderBy('id', 'desc')->get();
		return DataTables::of($customer)
			// ->addColumn('action', function ($data) {
			// 	$actions = '<a href="javascript:void(0)" class="btn btn-sm btn-danger delete_contact" title="Delete Contact" data-url="' . route('admin.contact.delete', ['id' => $data->id]) . '">
			// 						<img class="toggle-image-change" src="' . asset('admin/img/icons/delete.svg') . '" width="30">
			// 					</a>';
			// 	return $actions;
			// })
			// ->addColumn('date', function ($data) {
            //     $date = $data->created_at->format('d-m-Y h:i:s A'); // Format: Day-Month-Year Hour:Minute:Second AM/PM
            //     return $date;
            // })
			->rawColumns([])
			->make(true);
	}




	public function updateContactStatus(Request $request)
    {
		// dd($request->all());
        $data = ContactUs::find($request->id);
        if ($data) {
            $data->status = $request->status;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Record not found.']);
    }

	
}
