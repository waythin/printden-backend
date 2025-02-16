<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offer;
use App\Models\ReviewRating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ReviewRatingsController extends Controller
{
    public function reviewDatatables()
	{
		$reviews = ReviewRating::orderBy('id', 'desc')->get();
		return DataTables::of($reviews)

			// ->addColumn('name', function ($data) {
			// 	$data = $data->updated_at->format('d-m-Y | h:i:s A'); // Format: Day-Month-Year Hour:Minute:Second AM/PM
			// 	return $data;
			// })

            ->addColumn('image', function ($data) {
                $imageUrl = $data->image ? asset($data->image) : ''; // Default image if no image exists
                return '<img src="' . $imageUrl . '" alt="Review Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">';
            })

			->addColumn('status', function ($data) {
				$statuses = ['active', 'inactive'];
				$dropdown = '<select class="form-control review-status-dropdown" data-id="' . $data->id . '">';
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
                    module="review" moduleid="' . $data->id . '">
                    <i style="color: #bb1616; font-size:1rem" class="fa fa-trash"></i>
                </a>';
                
                $actionButtons .= '</div>';

                return $actionButtons;
            })
			->rawColumns(['status', 'updated_date', 'action', 'image'])
			->make(true);
	}


	public function reviewList()
    {
        $title = 'Review Ratings';
        return view('admin.review.review_list', compact('title'));
    }


    public function updateReviewStatus(Request $request)
    {
        // dump($request->all());
        $data = ReviewRating::find($request->id);
        if ($data) {
            $data->status = $request->status;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Record not found.']);
    }


    public function deleteReview($id)
    {
        $data = ReviewRating::where('id',$id)->delete();
        if($data){
            $message = "Data has been deleted successfully!";
            return response()->json(['success_message' => $message]);
        }
        $message = "Something went wrong!";
            return response()->json(['error_message' => $message]);
       
        
    }



    public function postReview(Request $request, $id=null){

        // dd($request->toArray());
        try {
            if ($id == "") {
                $review = new ReviewRating;
                $message = "Review added Successfully!";
            }
            else{
                $review = ReviewRating::find($id);
                $message = "Review updated Successfully!";
            }
            if($request->isMethod('post')){
                $data = $request->all();

                 $rules = [
            	    'name' => 'required',
            	    'rate' => 'required',
            	    
                ];
                $customMessages = [
    
                ];
                 $validation = Validator::make($data, $rules, $customMessages);
                 if ($validation->fails()) {
                    return response()->json([
                        'validation_error' => $validation->getMessageBag()
                    ]);
                }

                $image_name = null;
                if ($request->hasfile('image')) {
                    $image_file = $request->file('image');
                    $image_name = date('Ymdhis') . '.' . $image_file->getClientOriginalExtension();
                    $image_file->move(public_path('/admin/reviews'), $image_name);
                }
    

                $review->name = $request->name;
                $review->email = $request->email;
                $review->rate = $request->rate;
                $review->comment = $request->comment;
                $review->status = "active";
                $review->image = $image_name;
                $review->save();
                return response()->json(['success_message' => $message]);
            }
            else{
                return response()->json(['data' => $review]);
            }
    
        } catch (\Throwable $exception) {
            return response()->json(['error_message' => $exception->getMessage()]);
        }
    }

   
}
