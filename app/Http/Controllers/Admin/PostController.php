<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\TextUI\Configuration\Php;

class PostController extends Controller
{
    public function index(Request $request) {
        $query = Post::with(['user']);

        if ($request->filled('search')) {
            $search = trim($request->search);

            if (str_starts_with($search, '@')) {
                $username = substr($search, 1);
                if ($username) {
                    $query->whereHas('user', function ($q) use ($username) {
                        $q->where('name', 'like', '%' . $username . '%');
                    });
                }
            } else 
                $query->where('title', 'like', '%' . $search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('admin.pages.posts.index', compact('posts'));
    }

    public function trash(Request $request) {
        $query = Post::with(['user'])->onlyTrashed();

        if ($request->filled('search')) {
            $search = trim($request->search);

            if (str_starts_with($search, '@')) {
                $username = substr($search, 1);
                if ($username) {
                    $query->whereHas('user', function ($q) use ($username) {
                        $q->where('name', 'like', '%' . $username . '%');
                    });
                }
            } else 
                $query->where('title', 'like', '%' . $search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('admin.pages.posts.trash', compact('posts'));
    }

    public function create()
    {
        return view('admin.pages.posts.create');
    }

    public function store(PostRequest $request) {
        try {
            $imagePath = 'default.png';
            if($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
            }

            $post = Post::create([
                'title'=> $request->title,
                'slug' => Str::slug($request->title),
                'user_id' => $request->user_id,
                'content' => $request->content,
                'image' => $imagePath
            ]);

            return redirect()->route('admin.posts.index')->with('success', 'Post created successfully');
        } catch(Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong while creating the post');
        }
    }

    public function show($id)
    {
        
    }

    public function edit($id) {
        $post = Post::findOrFail($id);
        return view('admin.pages.posts.edit', compact('post'));
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        try {
            $imagePath = $post->image;
            if ($request->hasFile('image')) {
                if ($post->image && $post->image !== 'default.png') {
                    Storage::disk('public')->delete($post->image);
                }
                $imagePath = $request->file('image')->store('posts', 'public');
            }

            $post->update([
                'title'=> $request->title,
                'slug'=> Str::slug($request->title),
                'user_id'=> $request->user_id,
                'content'=> $request->content,
                'status' => $request->status,
                'image'=> $imagePath
            ]);

            return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully');
        } catch(Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong while deleting the post');
        }
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        try {
            $post->delete();
            return redirect()->route('admin.posts.index')
                ->with('success', "Post deleted successfully (soft delete).");
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong while deleting the post.');
        }
    }

    public function forceDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);

        try {
            if ($post->image && $post->image !== 'default.png') {
                Storage::disk('public')->delete($post->image);
            }

            $post->forceDelete();
            return back()->with('success', "post permanently deleted.");
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong while permanently deleting the post.');
        }
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);

        if ($post->trashed()) {
            $post->restore();
            return back()->with('success', "Post restored successfully.");
        }

        return back()->with('error', 'post is not deleted.');
    }
}
