<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $articles = Article::query()
            ->select(['id', 'title', 'slug', 'category', 'is_published', 'published_at', 'created_at'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->latest('published_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.articles.index', [
            'articles' => $articles,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.articles.create', [
            'article' => new Article,
        ]);
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        Article::query()->create($this->payload($request));

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        $article->update($this->payload($request));

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }

    private function payload(ArticleRequest $request): array
    {
        $validated = $request->validated();
        $validated['is_published'] = $request->boolean('is_published');

        if (! $validated['is_published']) {
            $validated['published_at'] = null;
        } elseif (empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        return $validated;
    }
}
