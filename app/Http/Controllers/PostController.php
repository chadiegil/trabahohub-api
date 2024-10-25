<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'required|string|max:255',
            'job_type' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
        ]);
        $user = $request->user();
        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        try {
            // Create a new post
            $post = Post::create(array_merge($request->all(), ['user_id' => $user->id]));
            return response()->json($post, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error. Please try again later.'], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'job_title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'location' => 'sometimes|required|string|max:255',
            'salary' => 'sometimes|required|string|max:255',
            'job_type' => 'sometimes|required|string|max:255',
            'company_name' => 'sometimes|required|string|max:255',
        ]);

        try {
            $post->update($request->all());
            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error. Please try again later.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error. Please try again later.'], 500);
        }
    }
}
