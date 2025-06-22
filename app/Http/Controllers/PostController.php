<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::get();

        $user = auth()->user();
        $query = Post::with(['user', 'media'])
            ->where('published_at','<=',now())
            ->latest();

        if ($user) {
            $ids = $user->following()->pluck("users.id");
            $query->whereIn("user_id", $ids);
        }
        $posts = $query->paginate(5);

        return view('post.index', [
            'categories' => $categories,
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('post.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $data = $request->validated();

        // $image = $data['image'];
        // unset($data['image']);
        // $imagePath = $image->store('posts','public');
        // $data['image'] = $imagePath;

        $data['user_id'] = auth()->user()->id;

        $post = Post::create($data);

        $post->addMediaFromRequest('image')->toMediaCollection();

        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
        return view('post.show', [
            'username' => $username,
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::get();
        if ($post->user_id != auth()->user()->id) {
            abort(403);
        }

        return view('post.edit', [
            'post'=> $post,
            'categories'=> $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        if ($post->user_id != auth()->user()->id) {
            abort(403);
        }
        $data = $request->validated();
        $post->update($data);

        if ($data['image'] ?? false) {
            $post->addMediaFromRequest('image')
                ->toMediaCollection();
        }

        return redirect()->route('myPosts');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id != auth()->user()->id) {
            abort(403);
        }
        $post->delete();
        return redirect()->route('dashboard');
    }

    public function category(Category $category)
    {
        $categories = Category::get();


        $user = auth()->user();

        if ($user) {
            $ids = $user->following()->pluck("users.id");
            $posts = $category->posts()->whereIn("user_id", $ids)->where('published_at','<=',now())->with(['user', 'media'])->latest()->paginate(5);
        } else {
            $posts = $category->posts()->where('published_at','<=',now())->with(['user', 'media'])->latest()->paginate(5);
        }


        return view('post.index', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }


    public function myPosts()
    {
        $categories = Category::get();
        $user = auth()->user();

        $posts = $user->posts()->with(['user', 'media'])->latest()->paginate(5);



        return view('post.index', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }
}
