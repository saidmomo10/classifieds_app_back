<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $users = User::orderBy('id','DESC')->paginate(5);

        return $users;
        // $data = User::orderBy('id','DESC')->paginate(5);
        // //dd($data);
        // return view('users.index',compact('data'))
        //     ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }
    
    // public function store(Request $request)
    // {
    //     $user = new User([
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required',
    //         'roles' => 'required'
    //     ]);
    
    //     $input = $request->all();
    //     $input['password'] = Hash::make($input['password']);
    
    //     // $user = User::create($input); 
        
    //     $user->assignRole($request->input('roles'));
    
    //     // return redirect()->route('users.index')
    //     //                 ->with('success','User created successfully');
    //     return response()->json($user, 201);
    // }

    public function store(Request $request)
{
    // Valider les données de la requête
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required',
        'roles' => 'required'
    ]);

    // Créer un nouvel utilisateur
    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
    ]);

    // Attribuer le rôle à l'utilisateur
    $user->assignRole($request->input('roles'));

    // Retourner la réponse JSON avec le nouvel utilisateur et le code HTTP 201 (Created)
    return response()->json($user, 201);
}
    
    public function show($id)
    {
        $user = User::with('roles')->find($id);

        $ads = Ad::where('user_id', $id)->with('subcategory', 'images', 'department', 'city')->get();
        return ['ads' => $ads, 'user' => $user];
        
        // $user = User::find($id)->getRoleNames();
        // dd($user);
        // return view('users.show',compact('user'));
    }
    
    
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.edit',compact('user','roles','userRole'));
    }
    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        // $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }
    
    
    public function destroy($id)
    {
        try {
            $role = User::findOrFail($id);
            $role->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete role'], 500);
        }
    }

    public function getUserRoles(Request $request)
    {
        $user = auth('sanctum')->user();
        $roles = $user->roles()->get();
        return $roles;

        
        // return response()->json(['user' => $user]);
        // return view('welcome',compact('roles'));
    }
}