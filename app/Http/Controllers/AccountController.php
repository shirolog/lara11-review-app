<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AccountController extends Controller
{
    public function register(){

        return view('account.register');
    }

    public function store(Request $request){

        
       $validator = Validator::make($request->all(),[

            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required',
        ]);

        if($validator->fails()){

            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }
      
        $user = new User;

        $user -> name = $request->input('name');
        $user -> email = $request->input('email');
        $user -> password = Hash::make($request->input('password'));
        $user->save();


        return redirect()->route('account.login')->with('success', 'You have registered successfully!');
    }


    public function login(){

        return view('account.login');
    }


    public function authenticate(Request $request){

        $validator = Validator::make($request->all(),[

            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){

            return redirect()->route('account.login')->withInput()->withErrors($validator);

        }


        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){

            return redirect()->route('account.profile');

        }else{

            return redirect()->route('account.login')->with('error', 'Either email or password is inccoret!');

        }
    }


    public function profile(){

        $user = User::find(Auth::user()->id);

        return view('account.profile', compact('user'));
    }



    public function updateProfile(Request $request){

        $rules = [

            'name' => 'required|min:3',
            'email' => 'required|unique:users,email,' . Auth::user()->id . ',id'
        ];

        if(!empty($request->file('image'))){

            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()){

            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }


        $user = User::find(Auth::user()->id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();


        if(!empty($request->file('image'))){
            
            $oldImage = public_path('uploads/profile/'. $user->image);

            if(is_file($oldImage)){
                unlink($oldImage);
            }


            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/profile/'), $imageName);
            $user->image = $imageName;
            $user->save();

        }

        return redirect()->route('account.profile')->with('success', 'Profile updated successfully!');
    }



    public function logout(Request $request){

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('account.login');
    }


    public function myReviews(){

        $keyword = request()->input('keyword');

        $reviews = $reviews = Review::with('book')
        ->where('user_id', Auth::user()->id);

        $reviews = $reviews->orderBy('created_at', 'DESC');

        
        if(!empty($keyword)){

            $reviews->where('review', 'like', '%'. $keyword .'%');

        }
        
        $reviews = $reviews->paginate(10);


        return view('account.my-reviews.my-reviews', compact('reviews'));
    }

    

    public function editReview(Review $review){

        $review = Review::where('id', $review->id)
        ->where('user_id', Auth::user()->id)
        ->with('book')
        ->firstOrfail();

        return view('account.my-reviews.edit-review', compact('review'));

    }

    public function updateReview(Request $request, Review $review){

        $validator = Validator::make($request->all(),[

            'review' => 'required',
            'rating' => 'required',
        ]);

        if($validator->fails()){

            return redirect()->route('account.editReview', $review)->withInput()->withErrors($validator);
        }


        $review->review = $request->input('review');
        $review->rating = $request->input('rating');
        $review->save();

        session()->flash('success', 'Review updated successfully!');

        return redirect()->route('account.myReviews');
    }


    public function deleteReview(Review $review){

        $review->delete();
        
        session()->flash('success', 'Review deleted successfully!');

        return response()->json([

            'status' => true,
        ]);

    }
}
