<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feed;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
{
    function add(Request $request)
    {
        $validateFeed = Validator::make($request->all(), 
            [
                'title' => 'string',
                'body' => 'string'
            ]);

            if($validateFeed->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'content error',
                    'errors' => $validateFeed->errors()
                ], 401);
            }

            $post = Feed::create([
                'title' => $request->title,
                'body' => $request->body,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Feed created successfully!',
            ], 200);
    }

    function edit(Request $request)
    {
        $validateFeed = Validator::make($request->all(), 
        [
            'id'=>'required|numeric|exists:feed,id',
            'title' => 'string',
            'body' => 'string'
        ]);

        if($validateFeed->fails()){
            return response()->json([
                'status' => false,
                'message' => 'content error',
                'errors' => $validateFeed->errors()
            ], 401);
        }

        $post = Feed::findOrFail($request->id);

        if($request->has('title'))
                $post->title = request('title');
        if($request->has('body'))
            $post->body = request('body');
        $post->save();

        return response()->json([
            'status' => true,
            'message' => 'Feed created successfully!',
        ], 200);
    }

    function delete(Request $request)
    {
        $post = $request -> validate([
            'id'=>'numeric|required|exists:feed,id'
        ]);
        $post = Feed::find(request('id'));
        $post->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully!',
        ], 200);
        
    }
}
