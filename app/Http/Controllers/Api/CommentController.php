<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $comment = Comment::all();
        return $comment;                                                                                                                                                            
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
        return response()->json('succes', 201);
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
