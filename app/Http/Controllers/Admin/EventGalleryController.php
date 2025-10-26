<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventGallery;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\File;

class EventGalleryController extends Controller
{
    public function galleryDatatables()
	{
		$gallerys = EventGallery::orderBy('id', 'desc')->get();
		return DataTables::of($gallerys)

			// ->addColumn('name', function ($data) {
			// 	$data = $data->updated_at->format('d-m-Y | h:i:s A'); // Format: Day-Month-Year Hour:Minute:Second AM/PM
			// 	return $data;
			// })

            ->addColumn('image', function ($data) {
                $imageUrl = $data->image ? asset($data->image) : ''; // Default image if no image exists
                return '<img src="' . $imageUrl . '" alt="EventGallery Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">';
            })

			->addColumn('status', function ($data) {
				$statuses = ['active', 'inactive'];
				$dropdown = '<select class="form-control gallery-status-dropdown" data-id="' . $data->id . '">';
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
                    module="gallery" moduleid="' . $data->id . '">
                    <i style="color: #bb1616; font-size:1rem" class="fa fa-trash"></i>
                </a>';
                
                $actionButtons .= '</div>';

                return $actionButtons;
            })
			->rawColumns(['status', 'updated_date', 'action', 'image'])
			->make(true);
	}


	public function galleryList()
    {
        $title = 'EventGallerys';
        $events = Event::where('status','active')->get();
        $categories = EventCategory::where('status','active')->get();
        $galleries = EventGallery::where('status','active')->get();

        return view('admin.events.galleries', compact('title','events','categories','galleries'));
    }


    public function updateEventGalleryStatus(Request $request)
    {
        // dump($request->all());
        $data = EventGallery::find($request->id);
        if ($data) {
            $data->status = $request->status;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Record not found.']);
    }


    public function deleteEventGallery($id)
    {
        $data = EventGallery::where('id',$id)->delete();
        if($data){
            $message = "Data has been deleted successfully!";
            return response()->json(['success_message' => $message]);
        }
        $message = "Something went wrong!";
            return response()->json(['error_message' => $message]);
       
        
    }

public function postEventGallery(Request $request, $id = null)
{
    try {
        // Determine if this is an edit
        $isEdit = !is_null($id);
        $gallery = $isEdit ? EventGallery::findOrFail($id) : new EventGallery;
        $message = $isEdit ? "Gallery updated successfully!" : "Gallery added successfully!";

        if ($request->isMethod('post')) {

            // Validation rules
            $rules = [
                'event_id' => 'required|exists:events,id',
                'event_category_id' => 'required|exists:event_categories,id',
            ];

            if ($isEdit) {
                $rules['image_url'] = 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048';
            } else {
                $rules['image_url.*'] = 'required|image|mimes:jpeg,jpg,png,webp|max:2048';
            }

            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails()) {
                return response()->json(['validation_error' => $validation->getMessageBag()]);
            }

            // Get category folder
            $category = \App\Models\EventCategory::find($request->event_category_id);
            $catTitle = str_replace(' ', '-', strtolower($category->title));
            $directoryPath = public_path('admin/img/gallery/' . $catTitle);
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true, true);
            }

            // ------------------------------
            // 1️⃣ EDIT: Single Image Update
            // ------------------------------
            if ($isEdit && $request->hasFile('image_url')) {

                // Delete old image if exists
                if (!empty($gallery->image_url) && File::exists(public_path($gallery->image_url))) {
                    File::delete(public_path($gallery->image_url));
                }

                $randomNum = rand(11111, 99999);
                $imgName = $catTitle . '-' . $randomNum . '.webp';
                $imagePath = 'admin/img/gallery/' . $catTitle . '/' . $imgName;

                Image::read($request->file('image_url'))
                    ->encode(new WebpEncoder(quality: 80))
                    ->save(public_path($imagePath));

                $gallery->image_url = $imagePath;
            }

            // ------------------------------
            // 2️⃣ ADD: Single or Multiple Images
            // ------------------------------
            if (!$isEdit && $request->hasFile('image_url')) {
                foreach ($request->file('image_url') as $image_tmp) {

                    $randomNum = rand(11111, 99999);
                    $imgName = $catTitle . '-' . $randomNum . '.webp';
                    $imagePath = 'admin/img/gallery/' . $catTitle . '/' . $imgName;

                    Image::read($image_tmp)
                        ->encode(new WebpEncoder(quality: 80))
                        ->save(public_path($imagePath));

                    EventGallery::create([
                        'event_id' => $request->event_id,
                        'event_category_id' => $request->event_category_id,
                        'image_url' => $imagePath,
                        'status' => 'active',
                    ]);
                }

                return response()->json(['success_message' => $message]);
            }

            // ------------------------------
            // 3️⃣ Save other fields for edit without changing image
            // ------------------------------
            if ($isEdit) {
                $gallery->event_id = $request->event_id;
                $gallery->event_category_id = $request->event_category_id;
                $gallery->status = $gallery->status ?? 'active';
                $gallery->save();

                return response()->json(['success_message' => $message]);
            }

        }else{
                return response()->json(['data' => $gallery]);
            }

    } catch (\Throwable $exception) {
        return response()->json(['error_message' => $exception->getMessage()]);
    }
}


    

}
