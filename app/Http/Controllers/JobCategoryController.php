<?php

namespace App\Http\Controllers;

use App\Models\JobProfile;
use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    public function index(){
        return JobCategory::all();
        
     }

    // public function show($id){
    //     return jobCategory::find($id);
        
    //  }

     public function show($id){
        $jobCategory = JobCategory::find($id);
        $jobs = JobProfile::where('jobcategory_id', $id)->with('jobcategory', 'images')->get();
        $number = $jobs->count();
        return ['jobs' => $jobs, 'jobCategory' => $jobCategory, 'number' => $number];
    }


    public function store(Request $request){  
        $request->validate([
            'name'=> "required",
            'category_id' => "required"
        ]);

        $jobCategory = JobCategory::where('name',$request->name)->first();
        if($jobCategory){
            return response()->json('Erreur', 500);
        }
        
        //dd($request->name);
        $save = JobCategory::create([
            'name'=> $request->name,
            'category_id' => $request->category_id
        ]);
        
        return response()->json($save, 201);    
    }
}
