<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\Album;
use App\Models\Frame;
use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\PrintType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    
    public function serviceList()
    {
        try {
            $data = Service::all();
            if($data){
                return $this->responseWithSuccess($data, 'Service list Loaded');
            }
            return $this->responseWithError(null, "Data not found");

        } catch (\Throwable $th) {
            return $this->responseWithError(null, $th->getMessage());
        }
    }
    
    public function sizeList()
    {
        try {
            $data = Size::all();
            if($data){
                return $this->responseWithSuccess($data, 'Size list Loaded');
            }
            return $this->responseWithError(null, "Data not found");

        } catch (\Throwable $th) {
            return $this->responseWithError(null, $th->getMessage());
        }
    }

    public function printTypeList()
    {
        try {
            $data = PrintType::with('size')->get();
            if($data){
                return $this->responseWithSuccess($data, 'Size list Loaded');
            }
            return $this->responseWithError(null, "Data not found");

        } catch (\Throwable $th) {
            return $this->responseWithError(null, $th->getMessage());
        }
    }
    

    public function albumList()
    {
        try {
            $data = Album::all();
            if($data){
                return $this->responseWithSuccess($data, 'Album list Loaded');
            }
            return $this->responseWithError(null, "Data not found");

        } catch (\Throwable $th) {
            return $this->responseWithError(null, $th->getMessage());
        }
    }

    public function frameList()
    {
        try {
            $data = Frame::all();
            if($data){
                return $this->responseWithSuccess($data, 'Frame list Loaded');
            }
            return $this->responseWithError(null, "Data not found");

        } catch (\Throwable $th) {
            return $this->responseWithError(null, $th->getMessage());
        }
    }


    public function servicePost(Request $request)
    {
        try {
            // dd($request->all());
            // Log::info('Request data:', $request->all());
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'service_id' => 'required',
                    'location' => 'required',
                    'delivery_type' => 'required',
                    'documents' => 'required|array|min:1',
                    'documents.*.size_id' => 'required|integer',            
                ],
                []
            );
            if ($validator->fails()) {
                return $this->responseWithError(null, $validator->errors(), 402);
            }

            DB::beginTransaction();
            $documents = $request->documents;
            if (empty($documents)) {
                return $this->responseWithError(null, "No documents provided.", 400);
            }
            
            // Create customer
            $customer = Customer::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
    
            $document_ids = [];
            foreach ($documents as $key => $data) {

                if ($request->hasFile("documents.$key.file")) {
                    $image_file = $request->file("documents.$key.file");
                    $image_name = uniqid() . '_' . date('YmdHis') . '.' . $image_file->getClientOriginalExtension();
                    $image_file->move(public_path('/admin/orders'), $image_name);
    
                    $item = Document::create([
                        'file_name' => $image_name,
                    ]);
                    $document_ids[] = [
                        'id' => $item->id,
                        'size_id' => $data['size_id'] ?? null,
                        'frame_id' => $data['frame_id'] ?? null,
                        'album_id' => $data['album_id'] ?? null,
                    ];
                }
                else{
                    return $this->responseWithError(null, "No file found.", 400);
                }
            }

            // delivery type 
            $delivery_charge = null;

            if($request->delivery_type == "inside_dhaka"){
                $delivery_charge = 60 ;
            }
            else{
                $delivery_charge = 120;
            }

            // Create order
            $order = Order::create([
                'order_no' => 'ord_' . rand(100000000, 999999999),
                'customer_id' => $customer->id,
                'service_id' => $request->service_id,
                'location' => $request->location,
                'delivery_type' => $request->delivery_type,
                'delivery_charge' => $delivery_charge,
                'note' => $request->note,
            ]);
    
            // Create order details
            $total_amount = null;
            
            foreach ($document_ids as $data) {

                $price = Size::find($data['size_id'])->price;
                $total_amount += $price;

                OrderDetails::create([
                    'order_id' => $order->id,
                    'price' => $price,
                    'document_id' => $data['id'],
                    'size_id' => $data['size_id'],
                    'frame_id' => $data['frame_id'],
                    'album_id' => $data['album_id'],
                ]);
            }

            // payment

            $payment_method = '';
            $order_status = '';
            $payment_status = "pending";


            if($request->payment_method == "online"){
                $payment_method = 'online';
                $payment_status = "success";
                $order_status = "confirm";
            }else{
                $payment_method = 'cod';
                $order_status = "pending";
            }


            // bkash payment

            $payment = Payment::create([
                'order_id' => $order->id,
                'transaction_no' => 'txn_' . rand(10000000, 99999999),
                'payment_method' => $payment_method,
                'payment_status' => $payment_status,
                'payment_amount' => $total_amount + $delivery_charge,
                // 'payment_details' => json_encode($request->all()),
            ]);
            

            $order->update([
                'total' => $total_amount,
                'status' => $order_status,
            ]);

            DB::commit();
            return $this->responseWithSuccess($order, 'Upload successful! We will get back to you shortly.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseWithError(null, $th->getMessage());
        }
    }

}
