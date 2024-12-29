<?php

namespace App\Http\Controllers\Admin;


use DB;
use Log;
use PDF;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function ordersDatatables(Request $request, $type = 'active')
    {
        $datas = Order::query();
        // dd("ggwp") ;
        // pending', 'confirm', 'processing', 'failed', 'success
        if ($type == "pending") {
            $datas = $datas->where('status', 'pending');
            $title = "Pending Orders";
        } else if ($type == "confirm") {
            $datas = $datas->where('status', 'confirm');
            $title = "Confirm Orders";
        } else if ($type == "processing") {
            $datas = $datas->where('status', 'processing');
            $title = "Processing Orders";
        } else if ($type == "failed") {
            $datas = $datas->where('status', 'failed');
            $title = "Failed Orders";
        } else if ($type == "success") {
            $datas = $datas->where('status', 'success');
            $title = "Success Orders";
        } else if ($type == "all") {
            $title = "All Orders";
        }


        $data  = $datas->get();
        // dd($data);
        // $datas = $datas
        //     ->when($request->filled('daterange'), function($query) use ($request) {
        //         $dates = explode(' - ', $request->daterange);
        //         $from_date = Carbon::parse($dates[0] ?? 'today')->startOfDay();
        //         $to_date = Carbon::parse($dates[1] ?? 'today')->endOfDay();
        //         $query->whereBetween('created_at', [$from_date, $to_date]);
        //     })
        //     ->with([
        //     ])->get();



        return DataTables::of($datas)

            ->addColumn('order_details', function (Order $data) {
                $order_no = $data->order_no;
                return $order_no;
            })
            ->addColumn('date', function (Order $data) {
                $order_date = $data->created_at->format('d-m-Y h:i:s A'); // Format: Day-Month-Year Hour:Minute:Second AM/PM
                return $order_date;
            })
            ->addColumn('customer_info', function (Order $data) {
                return $data->customer->name . '<br>' . $data->customer->phone . '<br>' . $data->customer->email;
            })
            // ->addColumn('status', function (Order $data) {

            //     return $data->status;
            // })

            ->addColumn('status', function (Order $data) {
                $statuses = ['pending', 'confirm', 'processing', 'failed', 'success', 'delivered'];
                $dropdown = '<select class="form-control status-dropdown" data-id="' . $data->id . '">';
                foreach ($statuses as $status) {
                    $selected = $data->status === $status ? 'selected' : '';
                    $dropdown .= '<option value="' . $status . '" ' . $selected . '>' . ucfirst($status) . '</option>';
                }
                $dropdown .= '</select>';
                return $dropdown;
            })
            ->addColumn('payment_method', function (Order $data) {
                return $data->payment->payment_method;
            })
            // ->addColumn('payment_status', function (Order $data) {
            //     return $data->payment->payment_status;
            // })
            ->addColumn('payment_status', function (Order $data) {
                $statuses = ['pending', 'success', 'failed'];
                $dropdown = '<select class="form-control payment-status-dropdown" data-id="' . $data->id . '">';
                foreach ($statuses as $status) {
                    $selected = $data->payment->payment_status === $status ? 'selected' : '';
                    $dropdown .= '<option value="' . $status . '" ' . $selected . '>' . ucfirst($status) . '</option>';
                }
                $dropdown .= '</select>';
                return $dropdown;
            })
            ->addColumn('amount', function (Order $data) {
                return $data->total . ' TK';
            })
            ->addColumn('delivery_charge', function (Order $data) {
                return "N/A";
            })

            ->addColumn('action', function (Order $data) {
                $actions = '<div class="d-flex align-items-center">
                                <a href="javascript:void(0);" 
                                   class="btn order_details" 
                                   title="View Order Details"
                                   data-url="' . route('admin.order-details', ['id' => $data->id]) . '">
                                    <img class="toggle-image-change" src="' . asset('admin/img/icons/password.svg') . '" width="30">
                                </a>
                            </div>';
                return $actions;
            })
            ->rawColumns(['order_details', 'customer_info', 'status', 'action', 'amount', 'payment_status'])    
            ->make(true);
    }


    public function orders()
    {
        $title = 'Orders';
        return view('admin.orders.orders')->with(compact('title'));
    }


    public function orderDetails($id)
    {
        $order = Order::with([
            'customer',
            'payment',
            'orderDetails' => function ($query) {
                $query->with('document', 'album', 'frame');
            }
        ])->findOrFail($id);
        // dd($order->toArray());
        $html = view('admin.orders.order_details', compact('order'))->render();
        return response()->json(['html' => $html]);
    }

    public function updateOrderStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            try {
                // Check if 'order_ids' exists in the request for multiple selections
                if (isset($data['order_ids']) && is_array($data['order_ids'])) {
                    Order::whereIn('id', $data['order_ids'])->update(['status' => $data['status']]);
                }
                // Otherwise, check for 'data_id' to update a single order
                else if (isset($data['data_id'])) {
                    Order::where('id', $data['data_id'])->update(['status' => $data['status']]);
                } else {
                    return response()->json(['error_message' => 'No valid order ID(s) provided.']);
                }

                return response()->json(['success_message' => 'Order Status Updated!']);
            } catch (\Throwable $exception) {
                return response()->json(['error_message' => $exception->getMessage()]);
            }
        }
    }


        public function downloadZip($order_id)
    {
        $order = Order::findOrFail($order_id);

        $zipFileName = public_path('order_' . $order_id . '.zip');
        $zip = new \ZipArchive;

        $zipStatus = $zip->open($zipFileName, \ZipArchive::CREATE);
        if ($zipStatus !== TRUE) {
            return response()->json(['error' => 'Failed to create ZIP file. Error code: ' . $zipStatus], 500);
        }

        foreach ($order->orderDetails as $detail) {
            if (!empty($detail->document->file_name)) {
                // Get the local file system path
                $filePath = public_path($detail->document->file_name); // Generate file path
            
                // Normalize path to avoid double slashes
                $filePath = preg_replace('/[\\\\\/]+/', DIRECTORY_SEPARATOR, $filePath);
            
                // dd($filePath); // Debugging output to verify path
            
                if (file_exists($filePath)) {
                    // Add file to the ZIP
                    $zip->addFile($filePath, basename($detail->document->file_name)); // Add only the file name to ZIP
                } else {
                    // return response()->json(['error' => 'File does not exist: ' . $filePath], 404);
                    return redirect()->back()->with('error_message', 'Something Went wrong'); 
                }
            }
        }

        $zip->close();

        if (!file_exists($zipFileName)) {
            return response()->json(['error' => 'ZIP file was not created.'], 500);
        }

        $response = response()->download($zipFileName);
        unlink($zipFileName); 
        return $response;
    }


    public function updateStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        if ($order) {
            $order->status = $request->status;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Order not found.']);
    }



    public function updatePaymentStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        if ($order && $order->payment) {
            $order->payment->payment_status = $request->payment_status;
            $order->payment->save();

            return response()->json(['success' => true, 'message' => 'Payment status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Order or payment record not found.']);
    }

    

}
