<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function eventDatatables()
	{
		$events = Event::orderBy('id', 'desc')->get();
		return DataTables::of($events)

			// ->addColumn('name', function ($data) {
			// 	$data = $data->updated_at->format('d-m-Y | h:i:s A'); // Format: Day-Month-Year Hour:Minute:Second AM/PM
			// 	return $data;
			// })

            ->addColumn('image', function ($data) {
                $imageUrl = $data->image ? asset($data->image) : ''; // Default image if no image exists
                return '<img src="' . $imageUrl . '" alt="Event Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">';
            })

			->addColumn('status', function ($data) {
				$statuses = ['active', 'inactive'];
				$dropdown = '<select class="form-control event-status-dropdown" data-id="' . $data->id . '">';
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
            ->addColumn('action', function ( $data){
                $actionButtons = '<div class="d-flex align-items-center">
                 
                <a title="Delete User"  class="confirmDelete" 
                    module="event" moduleid="' . $data->id . '">
                    <i style="color: #bb1616; font-size:1rem" class="fa fa-trash"></i>
                </a>';
                
                $actionButtons .= '</div>';

                return $actionButtons;
            })
			->rawColumns(['status', 'updated_date', 'action', 'image'])
			->make(true);
	}


	public function eventList()
    {
        $title = 'Events';
        return view('admin.events.events', compact('title'));
    }


    public function updateEventStatus(Request $request)
    {
        // dump($request->all());
        $data = Event::find($request->id);
        if ($data) {
            $data->status = $request->status;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Record not found.']);
    }


    public function deleteEvent($id)
    {
        $data = Event::where('id',$id)->delete();
        if($data){
            $message = "Data has been deleted successfully!";
            return response()->json(['success_message' => $message]);
        }
        $message = "Something went wrong!";
            return response()->json(['error_message' => $message]);
       
        
    }



    public function postEvent(Request $request, $id=null){

        // dd($request->toArray());
        try {
            if ($id == "") {
                $event = new Event;
                $message = "Event added Successfully!";
            }
            else{
                $event = Event::find($id);
                $message = "Event updated Successfully!";
            }
            if($request->isMethod('post')){
                $data = $request->all();

                 $rules = [
            	    'name' => 'required',
            	    
                ];
                $customMessages = [
    
                ];
                 $validation = Validator::make($data, $rules, $customMessages);
                 if ($validation->fails()) {
                    return response()->json([
                        'validation_error' => $validation->getMessageBag()
                    ]);
                }

                // $image_name = null;
                // if ($request->hasfile('image')) {
                //     $image_file = $request->file('image');
                //     $image_name = date('Ymdhis') . '.' . $image_file->getClientOriginalExtension();
                //     $image_file->move(public_path('/admin/events'), $image_name);
                // }
    

                $event->name = $request->name;
                $event->description = $request->description;
                $event->status = "active";
                //$event->image = $image_name;
                $event->save();
                return response()->json(['success_message' => $message]);
            }
            else{
                return response()->json(['data' => $event]);
            }
    
        } catch (\Throwable $exception) {
            return response()->json(['error_message' => $exception->getMessage()]);
        }
    }
}
