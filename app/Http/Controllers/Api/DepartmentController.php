<?php

namespace App\Http\Controllers\Api;
use App\Models\Department;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('cities', 'ads')->get();

        return response()->json($departments->map(function ($dept) {
            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'image' => asset("storage/{$dept->image}"),
                'cities' => $dept->cities,
                'ads' => $dept->ads
            ];
        }));
    }
}
