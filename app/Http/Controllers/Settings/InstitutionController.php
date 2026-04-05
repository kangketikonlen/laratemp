<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateInstitutionRequest;
use App\Models\Settings\Institution;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class InstitutionController extends Controller
{
    public function index(): View
    {
        $institution = Institution::query()->firstOrNew();

        return view('auth/settings/institutions/index', [
            'title' => 'Institution',
            'description' => 'Manage institution records from the settings section.',
            'institution' => $institution,
            'logoPreviewUrl' => $this->previewUrl($institution->logo),
            'backgroundPreviewUrl' => $this->previewUrl($institution->background),
        ]);
    }

    public function update(UpdateInstitutionRequest $request): RedirectResponse
    {
        $institution = Institution::query()->firstOrNew();
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $this->deleteAsset($institution->logo);
            $data['logo'] = $request->file('logo')->store('institutions', 'public');
        }

        if ($request->hasFile('background')) {
            $this->deleteAsset($institution->background);
            $data['background'] = $request->file('background')->store('institutions', 'public');
        }

        $actor = $request->user()?->username ?? $request->user()?->name ?? 'System';

        $data['created_by'] = $institution->exists
            ? $institution->created_by
            : $actor;
        $data['updated_by'] = $actor;

        $institution->fill($data);
        $institution->save();

        return redirect()
            ->route('settings.institutions.index')
            ->with('status', 'Institution settings berhasil diperbarui.');
    }

    private function deleteAsset(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    private function previewUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
