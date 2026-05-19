<?php

namespace App\Filament\Support;

use Closure;
use Filament\Forms\Components\BaseFileUpload;
use League\Flysystem\UnableToCheckFileExistence;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileUploadStorage
{
    public static function storePublicly(): Closure
    {
        return static function (BaseFileUpload $component, TemporaryUploadedFile $file): ?string {
            try {
                if (! $file->exists()) {
                    return null;
                }
            } catch (UnableToCheckFileExistence) {
                return null;
            }

            $path = $file->storeAs(
                $component->getDirectory(),
                $component->getUploadedFileNameForStorage($file),
                [
                    'disk' => $component->getDiskName(),
                    'visibility' => 'public',
                ],
            );

            return is_string($path) ? $path : null;
        };
    }
}
