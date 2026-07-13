<?php

namespace App\Core\Engines;

use App\Core\Models\Attachment;
use App\Core\Support\CompanyContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AttachmentEngine
{
    public function upload(Model $entity, UploadedFile $file, ?int $userId = null, string $disk = 'local'): Attachment
    {
        $path = $file->store('attachments/'.class_basename($entity), $disk);

        return Attachment::create([
            'company_id' => app(CompanyContext::class)->id(),
            'entity_type' => get_class($entity),
            'entity_id' => $entity->getKey(),
            'user_id' => $userId,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'disk' => $disk,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    public function for(Model $entity)
    {
        return Attachment::query()
            ->where('entity_type', get_class($entity))
            ->where('entity_id', $entity->getKey())
            ->latest();
    }

    public function url(Attachment $attachment): string
    {
        return Storage::disk($attachment->disk)->url($attachment->path);
    }

    public function delete(Attachment $attachment): void
    {
        Storage::disk($attachment->disk)->delete($attachment->path);
        $attachment->delete();
    }
}
