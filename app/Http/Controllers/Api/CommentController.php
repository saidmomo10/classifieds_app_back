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
        $comment = Comment::with('user', 'ad')->orderBy('created_at', 'desc')->get();
        return response()->json($comment) ;                                                                                                                                                            
    }
    
    
    public function store(Request $request)
{
    $request->validate([
        'comment' => 'required',
        'ad_id' => 'required',
    ]);
    
    $user = auth('sanctum')->user();
    
    if (!$user) {
        return response()->json(['message' => 'Utilisateur non authentifié'], 401);
    }
    
    try {
        $comment = new Comment([
            'user_id' => $user->id,
            'ad_id' => $request->ad_id,
            'comment' => $request->input('comment'),
        ]);
        $comment->save();
        
        $comment_status = 'New Comment';
        $comment_id = $comment->id;
        $ad = Ad::findOrFail($request->ad_id);
        $adOwner = $ad->user;
        
        // Ne pas envoyer de notification si l'utilisateur commente sa propre annonce
        if ($adOwner->id !== $user->id) {
            $adOwner->notify(new CommentCreated($user, $comment_id, $request->ad_id, $comment_status, $request->comment, $ad->title));
        }
        
        return response()->json(['message' => 'Commentaire ajouté avec succès'], 201);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Erreur lors de la création du commentaire', 'error' => $e->getMessage()], 500);
    }
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
