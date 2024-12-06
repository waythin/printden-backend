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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'documents' => 'required|array|min:1'                
                ],
                []
            );
            if ($validator->fails()) {
                return $this->responseWithError(null, $validator->errors(), 402);
            }

         
            DB::beginTransaction();
            $customer = Customer::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            
            $documents = $request->documents; 
            // dd($documents);
            $document_ids = [];
            
            if (!empty($documents)) {
                foreach ($documents as $key => $data) {
                    // dd($data['file']);
                    if ($request->hasFile("documents.$key.file")) { 
                        $image_file = $request->file("documents.$key.file"); 
                        $image_name = date('YmdHis') . '.' . $image_file->getClientOriginalExtension();
                        $image_file->move(public_path('/admin/orders'), $image_name);
            
                        $item = Document::create([
                            'file_name' => $image_name,
                        ]);
                        $document_ids[] = [
                            'id' => $item->id,
                            'size_id' => $data['size_id'],
                            'frame_id' => $data['frame_id']
                        ];
                    }
                    // dd("bye");

                }
            }
            else{
                return $this->responseWithError(null, "No file found!");
            }
            
            $order = Order::create([
                'order_no' => 'ord_' . rand(10000000, 99999999),
                'customer_id' => $customer->id,
                'service_id' => $request->service_id,
                'album_id' => $request->album_id,
            ]);
            
            // Save order details
            $details = [];
            foreach ($document_ids as $data) {
                OrderDetails::create([
                    'order_id' => $order->id,
                    'document_id' => $data['id'],
                    'size_id' => $data['size_id'],
                    'frame_id' => $data['frame_id'],
                ]);
            }


            DB::commit();
            return $this->responseWithSuccess($order, 'Uploaded Successfully');

        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseWithError(null, $th->getMessage());
        }
    }





}
