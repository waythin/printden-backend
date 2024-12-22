<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Carbon\Carbon;
use DB;
use PDF;
use DataTables;

class OrderController extends Controller
{
    public function ordersDatatables(Request $request,$type='active')
    {

        $datas = Order::query();
        if ($type == "active") {
            $datas = $datas->where('status', 'processing');
            $title = "Active Orders";
        } else if ($type == "delivered") {
            $datas = $datas->where('status', 'delivered');
            $title = "Delivered Orders";
        } else if ($type == "canceled") {
            $datas = $datas->where('status', 'canceled');
            $title = "Canceled Orders";
        } else if ($type == "all") {
            $title = "All Orders";
        }

        
        $datas = $datas
            ->when($request->filled('daterange'), function($query) use ($request) {
                $dates = explode(' - ', $request->daterange);
                $from_date = Carbon::parse($dates[0] ?? 'today')->startOfDay();
                $to_date = Carbon::parse($dates[1] ?? 'today')->endOfDay();
                $query->whereBetween('created_at', [$from_date, $to_date]);
            })
            ->with([
            ])
            ->get();

        return Datatables::of($datas)
            ->addColumn('status', function (Order $data) {
                $currentUserId = Auth::guard('admin')->user()->id;
                $statusOptions = [
                    'processing' => 'Processing',
                    'canceled' => 'Canceled',
                    'delivered' => 'Delivered'
                ];
                $statusSelect = '<div class="action-list"><select class="form-control w-75 updateStatus" module="order" data_id="' . e($data->id) . '" data_admin_id="' . e($currentUserId) . '">';
                foreach ($statusOptions as $value => $label) {
                    $selected = $data->status == $value ? 'selected' : '';
                    $statusSelect .= "<option value=\"$value\" $selected>$label</option>";
                }
                $statusSelect .= '</select></div>';
                return $statusSelect;
            })
            ->addColumn('action', function (Order $data) {
                $actions = '';
                $actions .= '<a title="Delete Order" href="javascript:void(0)" class="confirmDelete" module="order" moduleid="' . e($data->id) . '"><i style="color: #bb1616; font-size:1rem" class="fa fa-trash"></i></a>';
                return $actions;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    public function orders()
    {
        $title = 'Orders';
      //  dd('hq');
        return view('admin.orders.orders')->with(compact('title'));
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
}
