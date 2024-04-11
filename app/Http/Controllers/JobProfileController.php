<?php

namespace App\Http\Controllers;

use App\Models\JobProfile;
use Illuminate\Http\Request;

class JobProfileController extends Controller
{
    public function show($id){
        return JobProfile::find($id);
        
     }

    public function store(Request $request){  
        $user = Auth::user();
        $request->validate([
            'name'=> "required",
            'lastname'=>"required",
            "adress"=> "required",
            'language'=>"required",
            "about_me"=> "required",
            "scolar_level"=> "required",
            "school"=> "required",
            "year"=> "required",
            "trainning"=> "required",
            "logiciel"=> "required",
            "jobcategory_id"=> "required",
            "user_id"=> "required",
        ]);

        // $jobProfile = JobProfile::where('name',$request->name)->first();
        // if($jobProfile){
        //     return response()->json('Erreur', 500);
        // }
        
        //dd($request->name);
        $save = JobProfile::create([
            'name'=> $request->name,
            'lastname'=> $request->lastname,
            'adress'=> $request->adress,
            'language'=> $request->language,
            'scolar_level'=> $request->scolar_level,
            'school'=> $request->school,
            'about_me'=> $request->about_me,
            'year'=> $request->year,
            'trainning' => $request->trainning,
            'logiciel'=> $request->logiciel,
            'jobcategory_id'=> $request->jobcategory_id,
            'user_id'=> $request->$user->id,
        ]);
        
        return response()->json($save, 201);    
    }
}
