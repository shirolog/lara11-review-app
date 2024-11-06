<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $keyword = request()->input('keyword'); 

        if(!empty($keyword)){

            
            $reviews = Review::with('book', 'user')
            ->where('review', 'like', '%'. $keyword .'%')
            ->latest()
            ->paginate(10);

        }else{
            
            $reviews = Review::with('book', 'user')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        }


  
        return view('account.reviews.list', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        return view('account.reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(),[

            'review' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){

            return redirect()->route('review.edit', $review)->withInput()->withErrors($validator);
        }


        $review->review = $request->input('review');
        $review->status = $request->input('status');
        $review->save();

        session()->flash('success', 'Review updated successfully!');

        return redirect()->route('review.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {


        $review->delete();
        
        session()->flash('success', 'Review deleted successfully!');

        return response()->json([

            'status' => true,
        ]);
        
    }
}
