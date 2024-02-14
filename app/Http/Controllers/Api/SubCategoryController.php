<?php

namespace App\Http\Controllers\Api;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
    public function show($id){
        return SubCategory::find($id);
        
     }

    public function store(Request $request){  
        $request->validate([
            'name'=> "required",
            'category_id' => "required"
        ]);

        $subCategory = SubCategory::where('name',$request->name)->first();
        if($subCategory){
            return response()->json('Erreur', 500);
        }
        
        //dd($request->name);
        $save = SubCategory::create([
            'name'=> $request->name,
            'category_id' => $request->category_id
        ]);
        
        return response()->json($save, 201);    
    }
}
