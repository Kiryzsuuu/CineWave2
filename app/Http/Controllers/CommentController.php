<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Film;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function storeFilmComment(Request $request, string $slug)
    {
        $film = Film::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        // One rating/comment per user per film (top-level)
        $existing = Comment::where('film_id', (string) $film->id)
            ->whereNull('parent_id')
            ->where('user_id', (string) auth()->id())
            ->first();

        if ($existing) {
            $existing->update([
                'body' => $validated['body'],
                'rating' => (int) $validated['rating'],
            ]);

            return back()->with('success', 'Komentar & rating kamu berhasil diperbarui.');
        }

        Comment::create([
            'film_id' => (string) $film->id,
            'user_id' => (string) auth()->id(),
            'user_name' => (string) (auth()->user()->name ?? 'User'),
            'body' => $validated['body'],
            'rating' => (int) $validated['rating'],
            'parent_id' => null,
        ]);

        return back()->with('success', 'Komentar & rating berhasil dikirim.');
    }

    public function storeReply(Request $request, string $commentId)
    {
        $parent = Comment::findOrFail($commentId);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Comment::create([
            'film_id' => (string) $parent->film_id,
            'user_id' => (string) auth()->id(),
            'user_name' => (string) (auth()->user()->name ?? 'User'),
            'body' => $validated['body'],
            'rating' => null,
            'parent_id' => (string) $parent->id,
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }
}
