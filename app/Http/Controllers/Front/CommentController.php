<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id'    => 'required|exists:posts,id',
            'body'       => 'required|string|min:2|max:2000',
            'parent_id'  => 'nullable|exists:comments,id',
            'guest_name' => 'required_without:user_id|string|max:100',
            'guest_email'=> 'required_without:user_id|email|max:255',
        ]);

        Comment::create([
            'post_id'     => $request->post_id,
            'user_id'     => auth()->id(),
            'parent_id'   => $request->parent_id,
            'guest_name'  => auth()->check() ? null : $request->guest_name,
            'guest_email' => auth()->check() ? null : $request->guest_email,
            'body'        => $request->body,
            'status'      => auth()->check() ? 'approved' : 'pending',
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['message' => 'Comment submitted.']);
    }
}
