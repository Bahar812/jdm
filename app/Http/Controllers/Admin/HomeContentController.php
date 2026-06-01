<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class HomeContentController extends Controller
{
    public function edit(): View
    {
        HomeContent::syncDefaults();

        return view('admin.home_content.edit', [
            'sections' => HomeContent::sections(),
            'values' => HomeContent::values(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'contents' => ['nullable', 'array'],
            'uploads' => ['nullable', 'array'],
            'uploads.*' => ['nullable', 'image', 'max:4096'],
        ]);

        $definitions = collect(HomeContent::definitions())->keyBy('key');
        $contents = $request->input('contents', []);
        $errors = [];

        foreach ($definitions as $key => $definition) {
            $value = trim((string) ($contents[$key] ?? ''));
            $maxLength = match ($definition['type']) {
                HomeContent::TYPE_TEXTAREA => 5000,
                HomeContent::TYPE_IMAGE => 1200,
                default => 255,
            };

            if (Str::length($value) > $maxLength) {
                $errors["contents.{$key}"] = "Field {$definition['label']} maksimal {$maxLength} karakter.";
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        foreach ($definitions as $key => $definition) {
            $value = trim((string) ($contents[$key] ?? ''));
            $uploadedImage = $request->file("uploads.{$key}");

            if ($definition['type'] === HomeContent::TYPE_IMAGE && $uploadedImage) {
                $value = $this->storeUploadedImage($uploadedImage, $key);
            }

            HomeContent::query()->updateOrCreate(
                ['content_key' => $key],
                ['value' => $value],
            );
        }

        return redirect()->route('admin.home-content.edit')
            ->with('success', 'Konten home berhasil diperbarui.');
    }

    private function storeUploadedImage($file, string $key): string
    {
        $directory = public_path('images/cms');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg';
        $filename = Str::slug($key).'-'.now()->format('YmdHis').'-'.Str::random(6).'.'.$extension;

        $file->move($directory, $filename);

        return '/images/cms/'.$filename;
    }
}
