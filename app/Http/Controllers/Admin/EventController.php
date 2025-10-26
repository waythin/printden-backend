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
            ->addColumn('action', function ( $data){
                $actionButtons = '<div class="d-flex align-items-center">
                 
                <a title="Delete User"  class="confirmDelete" 
                    module="event" moduleid="' . $data->id . '">
                    <i style="color: #bb1616; font-size:1rem" class="fa fa-trash"></i>
                </a>';
                
                $actionButtons .= '</div>';

                return $actionButtons;
            })
			->rawColumns(['status', 'action'])
			->make(true);
	}


	public function eventList()
    {
        $title = 'Events';
        $events = Event::orderBy('id', 'desc')->get();
        return view('admin.events.events', compact('title','events'));
    }

    public function updateEventStatus(Request $request)
    {
        // dump($request->all());
        $data = Event::find($request->data_id);
        if ($data) {
            $data->status = $request->status;
            $data->save();

            return response()->json(['success_message' => 'Status updated successfully.']);
        }

            return response()->json(['error_message' => 'Record not found.']);
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
            	    'title' => 'required',
            	    
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
    

                $event->title = $request->title;
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
