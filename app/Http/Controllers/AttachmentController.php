<?php

namespace App\Http\Controllers;

use App\Core\Engines\AttachmentEngine;
use App\Core\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'entity_type' => ['required', 'string'],
            'entity_id' => ['required', 'integer'],
            'file' => ['required', 'file', 'max:20480'],
        ]);

        $model = $data['entity_type']::findOrFail($data['entity_id']);

        $this->authorize('view', $model);

        app(AttachmentEngine::class)->upload($model, $request->file('file'), auth()->id());

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('status', 'Anexo enviado.');
    }

    public function destroy(Request $request, Attachment $attachment)
    {
        $this->authorize('view', $attachment->entity ?? null);

        app(AttachmentEngine::class)->delete($attachment);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('status', 'Anexo removido.');
    }

    public function download(Attachment $attachment)
    {
        $this->authorize('view', $attachment->entity ?? null);

        return Storage::disk($attachment->disk)->download($attachment->path, $attachment->name);
    }
}
