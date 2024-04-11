<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentCreated;

class CommentController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //      $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
    //      $this->middleware('permission:role-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    // }
    
    /**
     * Display a listing of the resource.²
     *
     */
    public function index(Request $request)
    {
        $comment = Comment::with('user', 'ad')->get();
        return response()->json($comment) ;                                                                                                                                                            
    }
    
    
    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required',
            'user_id' => 'required',
            'ad_id' => 'required',
        ]);
        $user = auth('sanctum')->user();
    
        $comment = new Comment([
            'user_id' => $user->id,
            'ad_id' => $request->ad_id,
            'comment' => $request->input('comment'),

        ]);
        $comment->save();

        $comment_status = 'New Comment';
        $comment_id = $comment->id;
        $adOwner = $comment->ad->user;
        $ad = Ad::findOrFail($request->ad_id);

        $adOwner->notify(new CommentCreated($user, $comment_id, $request->ad_id, $comment_status, $request->comment, $ad->title));


        // $users = User::all();
        // $comment_status = 'New Comment';
        // $comment_id = $comment->id;
        // $ad = Ad::findOrFail($request->ad_id);

        // foreach($users as $user){
        //     if($user->id !== Auth::user()->id){
        //         $user->notify(new CommentCreated(Auth::user(), $comment_id, $request->ad_id, $comment_status, $request->comment, $ad->title));
        //     }
        // }


        return response()->json('succes', 201);
    }
    

    // public function unreadNotifications()
    // {
    //     $unreadNotifications = auth('sanctum')->user()->unreadNotifications;
    //     return response()->json($unreadNotifications);
    // }

    // public function markAsRead()
    // {
    //     Auth::user()->notifications->markAsRead();
    //     return response()->json('success');
    // }

    public function unreadNotifications()
    {
        $unreadNotifications = auth()->user()->unreadNotifications;
        return response()->json($unreadNotifications);
    }

    public function markAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json('success');
    }


    public function show($id)
    {
        
    }
     
    
    public function update(Request $request, $id)
    {
        

    }

    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete comment'], 500);
        }
    }
}
