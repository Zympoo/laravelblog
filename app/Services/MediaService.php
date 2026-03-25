<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class MediaService
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = ImageManager::gd();
    }

    public function upload($model, UploadedFile $file, ?string $directory = null): Media
    {
        $disk = 'public';

        // Lees afbeelding in
        $image = $this->imageManager->read($file->getPathname());

        $filename = uniqid().'.'.$file->getClientOriginalExtension();
        $directory = $directory ? rtrim($directory, '/') : '';
        $path = $directory ? "$directory/$filename" : $filename;

        // Opslaan
        Storage::disk($disk)->put($path, (string) $image->encode());

        $media = new Media([
            'disk' => $disk,
            'directory' => $directory ?: null,
            'filename' => $filename,
            'mime_type' => $file->getClientMimeType(),
            'size' => Storage::disk($disk)->size($path),
        ]);

        $model->media()->save($media);

        return $media;
    }

    public function replace($model, UploadedFile $file, ?string $directory = null): Media
    {
        if ($model->media) {
            $this->deleteFile($model->media);
            $model->media->delete();
        }

        return $this->upload($model, $file, $directory);
    }

    public function deleteFile(Media $media): void
    {
        $path = $media->path();

        if (Storage::disk($media->disk)->exists($path)) {
            Storage::disk($media->disk)->delete($path);
        }
    }

    public function delete(Media $media): void
    {
        $this->deleteFile($media);
        $media->delete();
    }
}
