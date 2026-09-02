<?php

namespace App\Http\Controllers;

use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $announcements = Post::query()
            ->where('type', 'announcement')
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $news = Post::query()
            ->where('type', 'news')
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        $events = Post::query()
            ->where('is_event', true)
            ->where('status', 'published')
            ->whereNotNull('event_date')
            ->where('event_date', '>=', today())
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->take(3)
            ->get();

        return view('home', compact(
            'announcements',
            'news',
            'events'
        ));
    }

    public function show(Post $post)
    {
        abort_unless(
            $post->status === 'published' &&
            (
                is_null($post->published_at) ||
                $post->published_at->isPast()
            ),
            404
        );

        $post->load('author');

        return view('posts.show', compact('post'));
    }
}