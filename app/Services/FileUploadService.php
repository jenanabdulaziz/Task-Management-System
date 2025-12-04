<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function upload(Task $task, UploadedFile $file, $userId)
    {
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('attachments/' . $task->id, $filename, 'public');

        return Attachment::create([
            'task_id' => $task->id,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'storage_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => $userId,
        ]);
    }

    public function delete(Attachment $attachment)
    {
        if (Storage::disk('public')->exists($attachment->storage_path)) {
            Storage::disk('public')->delete($attachment->storage_path);
        }
        
        $attachment->delete();
    }
}
