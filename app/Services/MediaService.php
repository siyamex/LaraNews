<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class MediaService
{
    private array $imageSizes = [
        'thumbnail' => [150, 150],
        'small' => [400, 300],
        'medium' => [800, 600],
        'large' => [1200, 900],
        'og' => [1200, 630],
    ];

    public function uploadImage(UploadedFile $file, string $folder = 'uploads', bool $generateSizes = true): array
    {
        $filename = $this->generateFilename($file);
        $basePath = $folder . '/' . date('Y/m');

        // Store original
        $path = $file->storeAs($basePath, $filename, 'public');

        // Convert to WebP and generate sizes
        $webpPath = null;
        $sizes = [];

        if ($this->isImage($file)) {
            $webpPath = $this->convertToWebP($file, $basePath, $filename);
            if ($generateSizes) {
                $sizes = $this->generateImageSizes($file, $basePath, $filename);
            }
        }

        return [
            'original' => $path,
            'webp' => $webpPath,
            'sizes' => $sizes,
            'filename' => $filename,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'width' => $this->getImageWidth($file),
            'height' => $this->getImageHeight($file),
            'alt' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ];
    }

    private function convertToWebP(UploadedFile $file, string $basePath, string $filename): ?string
    {
        try {
            $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            $webpPath = $basePath . '/webp/' . $webpFilename;

            $image = Image::read($file->getPathname());
            $encoded = $image->toWebp(85);

            Storage::disk('public')->put($webpPath, $encoded);
            return $webpPath;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function generateImageSizes(UploadedFile $file, string $basePath, string $filename): array
    {
        $sizes = [];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);

        foreach ($this->imageSizes as $sizeName => [$width, $height]) {
            try {
                $sizeFilename = "{$name}-{$sizeName}.{$ext}";
                $sizePath = $basePath . '/sizes/' . $sizeFilename;

                $image = Image::read($file->getPathname());
                $image->cover($width, $height);
                Storage::disk('public')->put($sizePath, $image->encode());

                // Also generate WebP version of each size
                $webpSizePath = $basePath . '/sizes/webp/' . $name . "-{$sizeName}.webp";
                $image = Image::read($file->getPathname());
                $image->cover($width, $height);
                Storage::disk('public')->put($webpSizePath, $image->toWebp(85));

                $sizes[$sizeName] = [
                    'path' => $sizePath,
                    'webp' => $webpSizePath,
                    'width' => $width,
                    'height' => $height,
                ];
            } catch (\Exception $e) {
                continue;
            }
        }

        return $sizes;
    }

    public function uploadFile(UploadedFile $file, string $folder = 'documents'): array
    {
        $filename = $this->generateFilename($file);
        $path = $file->storeAs($folder . '/' . date('Y/m'), $filename, 'public');

        return [
            'path' => $path,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];
    }

    public function delete(string $path): void
    {
        Storage::disk('public')->delete($path);
    }

    private function generateFilename(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    private function isImage(UploadedFile $file): bool
    {
        return str_starts_with($file->getMimeType(), 'image/');
    }

    private function getImageWidth(UploadedFile $file): ?int
    {
        if (!$this->isImage($file)) return null;
        try {
            [$width] = getimagesize($file->getPathname());
            return $width;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getImageHeight(UploadedFile $file): ?int
    {
        if (!$this->isImage($file)) return null;
        try {
            [, $height] = getimagesize($file->getPathname());
            return $height;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getImageSrcset(string $basePath): string
    {
        $ext = pathinfo($basePath, PATHINFO_EXTENSION);
        $dir = dirname($basePath);
        $name = pathinfo($basePath, PATHINFO_FILENAME);
        $srcset = [];

        foreach ($this->imageSizes as $sizeName => [$width]) {
            $sizePath = $dir . '/sizes/' . $name . "-{$sizeName}.{$ext}";
            if (Storage::disk('public')->exists($sizePath)) {
                $srcset[] = Storage::url($sizePath) . " {$width}w";
            }
        }

        return implode(', ', $srcset);
    }
}
