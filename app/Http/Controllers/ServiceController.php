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
            /** -------------------------------------
             *  ✅ Validation
             * --------------------------------------*/
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email',
                    'phone' => 'required|string|max:20',
                    'service_id' => 'required|integer|exists:services,id',
                    'location' => 'required|string',
                    'delivery_type' => 'required|in:inside_dhaka,outside_dhaka',
                    'payment_method' => 'required|in:cod,online',
                    'documents' => 'required|array|min:1',
                    'documents.*.size_id' => 'required_if:service_id,1,2,3|integer|exists:sizes,id',
                    'documents.*.album_id' => 'required_if:service_id,4|integer|exists:albums,id',
                    'documents.*.pages' => 'required_if:service_id,4|array|min:1',
                    'documents.*.pages.*.file' => 'required_if:service_id,4|file',
                    'documents.*.file' => 'required_if:service_id,1,2,3|file',
                ],
                [
                    'documents.*.size_id.required_if' => 'Size is required for this service.',
                    'documents.*.album_id.required_if' => 'Album ID is required for album service.',
                    'documents.*.pages.required_if' => 'Pages are required for album service.',
                    'documents.*.file.required_if' => 'Document file is required.',
                ]
            );

            if ($validator->fails()) {
                return $this->responseWithError(null, $validator->errors(), 402);
            }

            DB::beginTransaction();

            /** -------------------------------------
             *  ✅ Find or Create Customer
             * --------------------------------------*/
            $customer = Customer::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'name' => $request->name,
                    'slug' => Str::slug($request->name, '-'),
                    'email' => $request->email,
                ]
            );

            // Update if exists
            if (!$customer->wasRecentlyCreated) {
                $customer->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);
            }

            /** -------------------------------------
             *  ✅ Calculate Delivery Charge
             * --------------------------------------*/
            $delivery_charge = $request->delivery_type === 'inside_dhaka' ? 60 : 120;

            /** -------------------------------------
             *  ✅ Create Order
             * --------------------------------------*/
            $order = Order::create([
                'order_no' => 'ORD-' . strtoupper(Str::random(8)),
                'customer_id' => $customer->id,
                'service_id' => $request->service_id,
                'location' => $request->location,
                'delivery_type' => $request->delivery_type,
                'delivery_charge' => $delivery_charge,
                'note' => $request->note,
                'status' => 'pending',
            ]);

            $total_amount = 0;

            /** -------------------------------------
             *  ✅ Process Documents & Order Details
             * --------------------------------------*/
            foreach ($request->documents as $key => $doc) {

                $serviceId = $request->service_id;

                // -----------------------------
                // Album Service (service_id = 4)
                // -----------------------------
                if ($serviceId == 4) {

                    $no_of_pages = $doc['no_of_pages'] ?? count($doc['pages']);
                    $size = Size::find($doc['size_id']);
                    $price = $size ? $size->price : 0;

                    // Create single order detail for album
                    $orderDetail = OrderDetails::create([
                        'order_id' => $order->id,
                        'price' => $price,
                        'album_id' => $doc['album_id'] ?? null,
                        'album_custom_cover' => $doc['album_custom_cover'] ?? null,
                        'no_of_pages' => $no_of_pages,
                        'orientation' => $doc['orientation'] ?? null,
                        'bleed_type' => $doc['bleed_type'] ?? null,
                        'print_type_id' => $doc['print_type_id'] ?? null,
                        'size_id' => $doc['size_id'],
                    ]);

                    // Upload pages
                    foreach ($doc['pages'] as $page_no => $page_file) {
                        if ($page_file instanceof \Illuminate\Http\UploadedFile) {
                            $fileName = uniqid("page_") . '.' . $page_file->getClientOriginalExtension();
                            $page_file->move(public_path('admin/orders'), $fileName);

                            Document::create([
                                'order_id' => $order->id,
                                'order_details_id' => $orderDetail->id,
                                'page_no' => $page_no + 1,
                                'file_name' => $fileName,
                            ]);
                        }
                    }

                    $total_amount += $price;

                } 
                // -----------------------------
                // Photo / Frame Print (service_id 1 or 2)
                // -----------------------------
                elseif (in_array($serviceId, [1,2])) {

                    $size = Size::find($doc['size_id']);
                    $price = $size ? $size->price : 0;

                    if (!$request->hasFile("documents.$key.file")) {
                        DB::rollBack();
                        return $this->responseWithError(null, "Missing file for document index $key.", 400);
                    }

                    $file = $request->file("documents.$key.file");
                    $fileName = uniqid('doc_') . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('admin/orders'), $fileName);

                    $orderDetail = OrderDetails::create([
                        'order_id' => $order->id,
                        'price' => $price,
                        'album_id' => $doc['album_id'] ?? null,
                        'frame_id' => $doc['frame_id'] ?? null,
                        'orientation' => $doc['orientation'] ?? null,
                        'bleed_type' => $doc['bleed_type'] ?? null,
                        'print_type_id' => $doc['print_type_id'] ?? null,
                        'size_id' => $doc['size_id'],
                    ]);

                    Document::create([
                        'order_id' => $order->id,
                        'order_details_id' => $orderDetail->id,
                        'file_name' => $fileName,
                    ]);

                    $total_amount += $price;

                } 
                // -----------------------------
                // Collage Print (service_id = 3)
                // -----------------------------
                elseif ($serviceId == 3) {

                    $size = Size::find($doc['size_id']);
                    $price = $size ? $size->price : 0;

                    if (!$request->hasFile("documents.$key.file")) {
                        DB::rollBack();
                        return $this->responseWithError(null, "Missing collage file.", 400);
                    }

                    $file = $request->file("documents.$key.file");
                    $fileName = uniqid('collage_') . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('admin/orders'), $fileName);

                    $orderDetail = OrderDetails::create([
                        'order_id' => $order->id,
                        'price' => $price,
                        'album_id' => $doc['album_id'] ?? null,
                        'frame_id' => $doc['frame_id'] ?? null,
                        'orientation' => $doc['orientation'] ?? null,
                        'bleed_type' => $doc['bleed_type'] ?? null,
                        'print_type_id' => $doc['print_type_id'] ?? null,
                        'size_id' => $doc['size_id'],
                    ]);

                    Document::create([
                        'order_id' => $order->id,
                        'order_details_id' => $orderDetail->id,
                        'file_name' => $fileName,
                    ]);

                    $total_amount += $price;
                }
            }

            /** -------------------------------------
             *  ✅ Payment
             * --------------------------------------*/
            $payment_status = $request->payment_method === 'online' ? 'success' : 'pending';
            $order_status = $request->payment_method === 'online' ? 'confirm' : 'pending';

            Payment::create([
                'order_id' => $order->id,
                'transaction_no' => 'TXN-' . strtoupper(Str::random(10)),
                'payment_method' => $request->payment_method,
                'payment_status' => $payment_status,
                'payment_amount' => $total_amount + $delivery_charge,
            ]);

            /** -------------------------------------
             *  ✅ Update Order Totals
             * --------------------------------------*/
            $order->update([
                'sub_total' => $total_amount,
                'total' => $total_amount + $delivery_charge,
                'status' => $order_status,
            ]);

            DB::commit();

            return $this->responseWithSuccess($order, 'Order placed successfully!');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseWithError(null, $th->getMessage(), 500);
        }
}

}
