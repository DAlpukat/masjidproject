<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index($pageId)
    {
        $page = Page::findOrFail($pageId);
        $posts = $page->posts()->latest()->paginate(10);

        return view('admin.posts.index', compact('page', 'posts'));
    }

    public function create($pageId)
    {
        $page = Page::findOrFail($pageId);
        return view('admin.posts.create', compact('page'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_id' => 'required|exists:pages,id',
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
        }

        $validated['slug'] = \Str::slug($request->title) . '-' . time();

        Post::create($validated);

        return redirect()->route('admin.posts.index', $request->page_id)->with('success', 'Berita berhasil diterbitkan!');
    }
    
    public function show($id)
    {
        $post = Post::with('page.tempatLayanan')->findOrFail($id);

        $previousUrl = url()->previous();
        $currentUrl = url()->current();
        
        $backUrl = route('room.view', $post->page->tempatLayanan->slug) . '#info-' . $post->page->id;

        if ($previousUrl && $previousUrl !== $currentUrl) {
            $backUrl = $previousUrl;
        }

        return view('user.post-show', compact('post', 'backUrl'));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $pageId = $post->page_id;
        $post->delete();

        return back()->with('success', 'Post berhasil dihapus.');
    }
}