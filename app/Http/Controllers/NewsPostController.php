<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;

class NewsPostController extends Controller
{
    public function index()
    {
        if (!session('admin_authenticated')) return redirect('/admin/login');
        $posts = NewsPost::latest()->paginate(10);
        return view('menu.admin.news.index', compact('posts'));
    }

    public function create()
    {
        if (!session('admin_authenticated')) return redirect('/admin/login');
        return view('menu.admin.news.create');
    }

    public function store(Request $request)
    {
        if (!session('admin_authenticated')) return redirect('/admin/login');

        $data = $request->validate([
            'title' => 'required|string|max:180',
            'excerpt' => 'nullable|string|max:280',
            'content' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('news', 'public');
        }

        $data['source_name'] = 'NOCIS';
        $data['is_published'] = (bool)($data['is_published'] ?? false);

        NewsPost::create($data);

        return redirect()->route('admin.news.index')->with('success', 'News created!');
    }

    public function edit(NewsPost $news)
    {
        if (!session('admin_authenticated')) return redirect('/admin/login');
        return view('menu.admin.news.edit', compact('news'));
    }

    public function update(Request $request, NewsPost $news)
    {
        if (!session('admin_authenticated')) return redirect('/admin/login');

        $data = $request->validate([
            'title' => 'required|string|max:180',
            'excerpt' => 'nullable|string|max:280',
            'content' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('news', 'public');
        }

        $data['is_published'] = (bool)($data['is_published'] ?? false);

        if ($data['is_published'] && !$news->published_at) {
            $data['published_at'] = now();
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success','News updated!');
    }
    public function destroy(NewsPost $news)
    {
        if (!session('admin_authenticated')) return redirect('/admin/login');
        $news->delete();
        return back()->with('success','News deleted!');
    }

}
