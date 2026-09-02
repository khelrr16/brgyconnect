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

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'excerpt' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'content' => [
                'required',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'archived',
                ]),
            ],

            'is_pinned' => [
                'nullable',
                'boolean',
            ],
        ]);

        // Upload image if supplied
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('posts', 'public');
        }

        // Create post
        Post::create([
            'created_by' => auth()->id(),

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

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'excerpt' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'content' => [
                'required',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'archived',
                ]),
            ],

            'is_pinned' => [
                'nullable',
                'boolean',
            ],
        ]);

        $data = [
            'type' => $validated['type'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'status' => $validated['status'],
            'is_pinned' => $request->boolean('is_pinned'),
        ];

        // Set published date only when publishing
        if (
            $validated['status'] === 'published'
            && $post->status !== 'published'
        ) {
            $data['published_at'] = now();
        }

        // Upload replacement image
        if ($request->hasFile('image')) {

            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $data['image'] = $request->file('image')
                ->store('posts', 'public');
        }

        $post->update($data);

        return redirect()
            ->route('admin.posts.index')
            ->with(
                'success',
                'Post updated successfully.'
            );
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
}