<?php

namespace App\Core\Controllers;

use App\Core\Engines\CommentEngine;
use App\Core\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'entity_type' => ['required', 'string'],
            'entity_id' => ['required', 'string'],
            'body' => ['required', 'string'],
            'visibility' => ['nullable', 'in:internal,public,client'],
            'redirect' => ['nullable', 'string'],
        ]);

        $modelClass = $data['entity_type'];
        abort_unless(class_exists($modelClass), 404);

        $model = $modelClass::where('ulid', $data['entity_id'])->firstOrFail();

        $comment = app(CommentEngine::class)->add(
            $model,
            auth()->id(),
            $data['body'],
            $data['visibility'] ?? 'internal'
        );

        preg_match_all('/@([\p{L}\p{N}_.]+)/u', $data['body'], $matches);
        if (! empty($matches[0])) {
            $comment->mentions = array_values(array_unique($matches[0]));
            $comment->save();
        }

        return redirect($data['redirect'] ?? url()->previous())
            ->with('status', 'Comentário adicionado.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return redirect()->back()->with('status', 'Comentário removido.');
    }

    public function toggleReaction(Request $request, Comment $comment): \Illuminate\Http\JsonResponse
    {
        $emoji = $request->input('emoji', '👍');
        $userId = auth()->id();

        $reactions = $comment->reactions ?? [];
        $list = $reactions[$emoji] ?? [];

        if (in_array($userId, $list, true)) {
            $list = array_values(array_diff($list, [$userId]));
        } else {
            $list[] = $userId;
        }

        if (empty($list)) {
            unset($reactions[$emoji]);
        } else {
            $reactions[$emoji] = $list;
        }

        $comment->reactions = $reactions;
        $comment->save();

        $emojis = ['👍', '❤️', '🎉'];
        $current = $comment->reactions ?? [];

        return response()->json([
            'reactions' => collect($emojis)->map(fn ($e) => [
                'emoji' => $e,
                'count' => count($current[$e] ?? []),
                'reacted' => in_array($userId, $current[$e] ?? [], true),
            ])->values(),
        ]);
    }
}
