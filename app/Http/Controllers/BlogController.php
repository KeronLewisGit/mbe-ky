<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('blog.index', ['posts' => Post::latest('published_at')->get()]);
    }

    public function show(Post $post): View
    {
        return view('blog.show', [
            'post' => $post,
            'more' => Post::whereKeyNot($post->id)->latest('published_at')->take(3)->get(),
        ]);
    }
}
