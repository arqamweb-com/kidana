<?php

use App\Filament\Support\FileUploadStorage;
use Filament\Forms\Components\FileUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

uses(RefreshDatabase::class);

test('public filament uploads are copied before livewire temporary files are deleted', function () {
    Storage::fake('public');

    Storage::disk('public')->put('livewire-tmp/example.jpg', 'image-content');
    Storage::disk('public')->put('livewire-tmp/example.jpg.json', json_encode([
        'name' => 'office.jpg',
        'type' => 'image/jpeg',
        'size' => 13,
        'hash' => 'office.jpg',
    ]));

    $component = FileUpload::make('image_url')
        ->disk('public')
        ->directory('offices')
        ->visibility('public');

    $temporaryFile = new TemporaryUploadedFile('example.jpg', 'public');
    $storedPath = FileUploadStorage::storePublicly()($component, $temporaryFile);

    expect($storedPath)->toStartWith('offices/')
        ->and(Storage::disk('public')->exists($storedPath))->toBeTrue()
        ->and(Storage::disk('public')->exists('livewire-tmp/example.jpg'))->toBeTrue()
        ->and($temporaryFile->getSize())->toBe(13);
});
