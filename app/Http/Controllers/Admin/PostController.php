<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display admin post list.
     */
    public function index()
    {
        $posts = Post::query()
            ->with('author')
            ->latest()
            ->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a new post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in([
                    'news',
                    'announcement',
                ]),
            ],

            'category' => [
                'required',
                Rule::in([
                    'general',
                    'important',
                    'community',
                    'program',
                    'notice',
                    'event',
                    'other',
                ]),
            ],

            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable','string','max:1000'],
            'content' => ['required','string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['required', Rule::in(['draft','published','archived']) ],
            'is_pinned' => ['nullable','boolean'],

            // Event 
            'is_event' => ['nullable', 'boolean'],
            'event_date' => ['nullable', 'date', 'required_if:is_event,1'], 
            'event_end_date' => ['nullable', 'date', 'after_or_equal:event_date'], 
            'event_time' => ['nullable', 'date_format:H:i'], 
            'event_location' => ['nullable', 'string', 'max:255'],
        ]);

        // Upload image if supplied
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('posts', 'public');
        }

        $isEvent = $request->boolean('is_event');

        // Create post
        Post::create([
            'created_by' => auth()->guard()->id(),

            'category' => $validated['category'],

            'type' => $validated['type'],

            'title' => $validated['title'],

            'slug' => Str::slug($validated['title']),

            'excerpt' => $validated['excerpt'] ?? null,

            'content' => $validated['content'],

            'image' => $imagePath,

            'status' => $validated['status'],

            'published_at' => $validated['status'] === 'published'
                ? now()
                : null,

            'is_pinned' => $request->boolean('is_pinned'),

            // Event 
            'is_event' => $isEvent,
            'event_date' => $isEvent 
                ? ($validated['event_date'] ?? null) 
                : null, 
            'event_end_date' => $isEvent 
                ? ($validated['event_end_date'] ?? null) 
                : null, 
            'event_time' => $isEvent 
                ? ($validated['event_time'] ?? null) 
                : null, 
            'event_location' => $isEvent 
                ? ($validated['event_location'] ?? null) 
                : null,
        ]);

        return redirect()
            ->route('admin.posts.index')
            ->with(
                'success',
                'Post created successfully.'
            );
    }

     /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        abort_unless(
            $post->status === 'published',
            404
        );

        return view('posts.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show a post for editing.
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update a post.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in([
                    'news',
                    'announcement',
                ]),
            ],

            'category' => [
                'required',
                Rule::in([
                    'general',
                    'important',
                    'community',
                    'program',
                    'notice',
                    'event',
                    'other',
                ]),
            ],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_pinned' => ['nullable', 'boolean'],

            // Event
            'is_event' => ['nullable', 'boolean'],
            'event_date' => ['nullable', 'date', 'required_if:is_event,1'],
            'event_end_date' => ['nullable', 'date', 'after_or_equal:event_date'],
            'event_time' => ['nullable', 'date_format:H:i'],
            'event_location' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('image')) {

            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $post->image = $request
                ->file('image')
                ->store('posts', 'public');
        }

        $isEvent = $request->boolean('is_event');

        $post->update([
            'type' => $validated['type'],
            'category' => $validated['category'],
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],

            'status' => $validated['status'],

            'published_at' => $validated['status'] === 'published'
                ? ($post->published_at ?? now())
                : null,

            'is_pinned' => $request->boolean('is_pinned'),

            // Event
            'is_event' => $isEvent,
            'event_date' => $isEvent
                ? ($validated['event_date'] ?? null)
                : null,
            'event_end_date' => $isEvent
                ? ($validated['event_end_date'] ?? null)
                : null,
            'event_time' => $isEvent
                ? ($validated['event_time'] ?? null)
                : null,
            'event_location' => $isEvent
                ? ($validated['event_location'] ?? null)
                : null,
        ]);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }
 
    /**
     * Delete a post.
     */
    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with(
            'success',
            'Post deleted successfully.'
        );
    }

    public function announcements()
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
            ->paginate(9);

        return view('posts.announcements', compact('announcements'));
    }
}