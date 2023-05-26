<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    function add(Request $request)
    {
        $user = auth('api')->user();
		
        if (!$user)
			return response()->json(['message' => "unauthorized user"], 401);
        
            
        $validateReport = Validator::make($request->all(), 
            [
                'data' => 'string'
            ]);

            if($validateReport->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'content error',
                    'errors' => $validateReport->errors()
                ], 422);
            }

            $report = new Report();

            $report->user_id = $user->id;
            $report->data =  $request->data;
            $report->save();

            return response()->json($report);
    }

    function edit(Request $request)
    {
        $user = auth('api')->user();
		
        if (!$user)
			return response()->json(['message' => "unauthorized user"], 401);

        $validateReport = Validator::make($request->all(), 
        [
            'id'=>'required|numeric|exists:reports,id',
            'data' => 'string'
        ]);

        if($validateReport->fails()){
            return response()->json([
                'status' => false,
                'message' => 'report does not exist',
                'errors' => $validateReport->errors()
            ], 422);
        }

        $report = Report::where('user_id',$user->id)->where('id',$request->id)->first();

        if($request->has('data'))
            $report->data = $request->data;
        $report->save();

        return response()->json([
            'status' => true,
            'message' => 'Report edited successfully!',
        ], 200);
    }

    function delete(Request $request)
    {
        $user = auth('api')->user();
		
        if (!$user)
			return response()->json(['message' => "unauthorized user"], 401);

        $validateReport = Validator::make($request->all(), 
        [
            'id'=>'required|numeric|exists:reports,id'
        ]);

        if($validateReport->fails()){
            return response()->json([
                'status' => false,
                'message' => 'article does not exist',
                'errors' => $validateReport->errors()
            ], 422);
        }

        $report = Report::where('user_id',$user->id)->where('id',$request->id)->first();
        $report->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'report deleted successfully!',
        ], 200);
    }

    function get(Request $request)
    {
        $user = auth('api')->user();
		
        if (!$user)
			return response()->json(['message' => "unauthorized user"], 401);

        $report = Report::where('user_id',$user->id)->where('id',$request->id)->first();
        return response()->json($report);
    }
}
