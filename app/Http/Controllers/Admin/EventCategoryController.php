<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class EventCategoryController extends Controller
{
    public function categoryDatatables()
	{
		$categories = EventCategory::orderBy('id', 'desc')->get();
		return DataTables::of($categories)

			// ->addColumn('name', function ($data) {
			// 	$data = $data->updated_at->format('d-m-Y | h:i:s A'); // Format: Day-Month-Year Hour:Minute:Second AM/PM
			// 	return $data;
			// })

            ->addColumn('image', function ($data) {
                $imageUrl = $data->image ? asset($data->image) : ''; // Default image if no image exists
                return '<img src="' . $imageUrl . '" alt="EventCategory Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">';
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


	public function categoryList()
    {
        $title = 'EventCategorys';
        $events = Event::where('status','active')->get();
        $categories = EventCategory::get();
        return view('admin.events.event_cats', compact('title','events','categories'));
    }


    public function updateEventCategoryStatus(Request $request)
    {
        // dump($request->all());
        $data = EventCategory::find($request->data_id);
        if ($data) {
            $data->status = $request->status;
            $data->save();

            return response()->json(['success_message' => 'Status updated successfully.']);
        }

            return response()->json(['error_message' => 'Record not found.']);
    }


    public function deleteEventCategory($id)
    {
        $data = EventCategory::where('id',$id)->delete();
        if($data){
            $message = "Data has been deleted successfully!";
            return response()->json(['success_message' => $message]);
        }
        $message = "Something went wrong!";
            return response()->json(['error_message' => $message]);
       
        
    }



    public function postEventCategory(Request $request, $id=null){

        // dd($request->toArray());
        try {
            if ($id == "") {
                $event = new EventCategory;
                $message = "EventCategory added Successfully!";
            }
            else{
                $event = EventCategory::find($id);
                $message = "EventCategory updated Successfully!";
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

                $event->event_id = $request->event_id;
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
