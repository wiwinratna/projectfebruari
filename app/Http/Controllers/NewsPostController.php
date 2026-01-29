<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\SportsNewsRssService; // atau SportsNewsService kalau pakai NewsAPI


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
            'title'        => 'required|string|max:180',
            'excerpt'      => 'nullable|string|max:280',
            'content'      => 'nullable|string',
            'cover_image'  => 'nullable|image|max:2048',
            'source_name'  => 'nullable|string|max:100',
            'source_url'   => 'nullable|url|max:255',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('news', 'public');
        }

        // default values (jangan nimpuk kalau user isi)
        $data['source_name']  = $data['source_name'] ?? 'NOCIS';
        $data['is_published'] = (bool)($data['is_published'] ?? false);

        // kalau dipublish tapi published_at kosong -> auto isi
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        NewsPost::create($data);

        return redirect()->route('admin.news.index')->with('success', 'News created!');
    }

    public function edit(NewsPost $news)
    {
        if (!session('admin_authenticated')) return redirect('/admin/login');

        $post = $news; // alias biar view pakai $post
        return view('menu.admin.news.edit', compact('post'));
    }


    public function update(Request $request, NewsPost $news)
    {
        if (!session('admin_authenticated')) return redirect('/admin/login');

        $data = $request->validate([
            'title' => 'required|string|max:180',
            'excerpt' => 'nullable|string|max:280',
            'content' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'source_name' => 'nullable|string|max:100',
            'source_url' => 'nullable|url|max:255',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
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
public function publicIndex(SportsNewsRssService $rss)
{
    $posts = NewsPost::where('is_published', true)
        ->orderByDesc('published_at')
        ->paginate(9);

    $apiNews = Cache::remember('news:rss', 600, function () use ($rss) {
        $limit = (int) env('SPORTS_NEWS_LIMIT', 9);
        return $rss->latest($limit);
    });

    return view('news.index', compact('posts', 'apiNews'));
}

    public function publicShow(NewsPost $news)
    {
        // kalau mau cuma yang publish aja:
        // abort_unless($news->is_published, 404);

        $post = $news; // alias supaya view pakai $post
        return view('news.show', compact('post'));
    }



}
