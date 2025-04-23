<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(){
        return Category::all();
        
     }

    public function show($id){
        return Category::find($id);
        
    }

    public function store(Request $request){  
        $request->validate([
            'name'=> "required"
        ]);

        $category = Category::where('name',$request->name)->first();
        if($category){
            return response()->json('Erreur', 500);
        }
        
        //dd($request->name);
        $save = Category::create([
            'name'=> $request->name,
        ]);
        
        return response()->json($save, 201);    
    }

}