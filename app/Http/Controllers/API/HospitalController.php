<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Support\Facades\Validator;

class HospitalController extends Controller
{
    function add(Request $request)
    {
        $user = auth('api')->user();
        // dd($user);
		
        if (!$user)
			return response()->json(['message' => "unauthorized user"], 401);
        
        if($user->role_id == 0)
            return response()->json(['message' => "unauthorized user"], 401);

        $validateHospital = Validator::make($request->all(), 
            [
                'name' => 'string',
                'location' => 'string',
                'image' => 'file'
            ]);

            if($validateHospital->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'content error',
                    'errors' => $validateHospital->errors()
                ], 422);
            }

            $hospital = new Hospital();

            $hospital->name =  $request->name;
            $hospital->location =  $request->location;
            $hospital->user_id = $user->id;
            $hospital->save();

            return response()->json($hospital);
    }

    function edit(Request $request)
    {
        $user = auth('api')->user();

		if (!$user)
			return response()->json(['message' => "unauthorized user"], 401);
        
        if($user->role_id == 0)
            return response()->json(['message' => "unauthorized user"], 401);
                
        $validateHospital = Validator::make($request->all(), 
        [
            'id'=>'required|numeric|exists:hospitals,id',
            'name' => 'string',
            'location' => 'string',
            'image' => 'string'
        ]);

        if($validateHospital->fails()){
            return response()->json([
                'status' => false,
                'message' => 'hospital does not exist',
                'errors' => $validateHospital->errors()
            ], 422);
        }

        $hospital = Hospital::where('user_id',$user->id)->where('id',$request->id)->first();

        if($hospital->user_id == $user->id){
            if($request->has('name'))
                $hospital->name = $request->name;
            if($request->has('location'))
                $hospital->location = $request->location;
            $hospital->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Hospital edited successfully!',
            'hospital' => $hospital
        ], 200);
    }

    function delete(Request $request)
    {
        $user = auth('api')->user();

		if (!$user)
			return response()->json(['message' => "unauthorized user"], 401);

        if($user->role_id == 0)
            return response()->json(['message' => "unauthorized user"], 401);
    
        $validateHospital = Validator::make($request->all(), 
        [
            'id'=>'required|numeric|exists:hospitals,id'
        ]);

        if($validateHospital->fails()){
            return response()->json([
                'status' => false,
                'message' => 'hospital does not exist',
                'errors' => $validateHospital->errors()
            ], 422);
        }

        $hospital = Hospital::find($request->id);

        if($hospital->user_id == $user->id){
            $hospital->delete();
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Hospital deleted successfully!',
            'hospitals' => Hospital::all()
        ], 200);
    }

    function getAll(Request $request)
    {
        $hospitals = Hospital::all();
        return response()->json($hospitals);
    }
}
