<?php

namespace App\Http\Controllers\API;

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
                ], 422);
            }

            $post = new Feed();

            $post->title =  $request->title;
            $post->body =  $request->body;
            $post->save();

            return response()->json($post);
    }

    function edit(Request $request)
    {
        $validateFeed = Validator::make($request->all(), 
        [
            'id'=>'required|numeric|exists:feeds,id',
            'title' => 'string',
            'body' => 'string'
        ]);

        if($validateFeed->fails()){
            return response()->json([
                'status' => false,
                'message' => 'article does not exist',
                'errors' => $validateFeed->errors()
            ], 422);
        }

        $post = Feed::find($request->id);

        if($request->has('title'))
            $post->title = $request->title;
        if($request->has('body'))
            $post->body = $request->body;
        $post->save();

        return response()->json([
            'status' => true,
            'message' => 'Feed edited successfully!',
        ], 200);
    }

    function delete(Request $request)
    {
        $validateFeed = Validator::make($request->all(), 
        [
            'id'=>'required|numeric|exists:feeds,id'
        ]);

        if($validateFeed->fails()){
            return response()->json([
                'status' => false,
                'message' => 'article does not exist',
                'errors' => $validateFeed->errors()
            ], 422);
        }

        $post = Feed::find($request->id);
        $post->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully!',
        ], 200);
    }

    function getAll(Request $request)
    {
        $feed = Feed::all();
        return response()->json($feed);
    }
}
