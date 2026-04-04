<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\StoreChangelogRequest;
use App\Http\Requests\Administration\UpdateChangelogRequest;
use App\Models\Administration\Changelog;
use App\Support\ActivityLogs\LogsUserActivity;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    use LogsUserActivity;

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $sort = (string) $request->string('sort', 'released_at');
        $direction = strtolower((string) $request->string('direction', 'desc'));
        $allowedSorts = ['version', 'title', 'status', 'released_at'];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'released_at';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $changelogs = Changelog::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($changelogQuery) use ($search) {
                    $changelogQuery
                        ->where('version', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");

                    $changelogQuery->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->when($sort !== 'released_at', fn ($query) => $query->orderByDesc('released_at'))
            ->when($sort !== 'version', fn ($query) => $query->orderByDesc('version'))
            ->paginate(10)
            ->withQueryString();

        return view('auth.administration.changelogs.index', [
            'title' => 'Changelogs',
            'description' => 'Review application changelogs from the administration section.',
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'changelogs' => $changelogs,
        ]);
    }

    public function create(): View
    {
        return view('auth.administration.changelogs.form', [
            'title' => 'Changelogs',
            'description' => 'Create a changelog record from the administration section.',
            'changelog' => new Changelog,
            'isEdit' => false,
        ]);
    }

    public function store(StoreChangelogRequest $request): RedirectResponse
    {
        $actor = $request->user()?->username ?? $request->user()?->name;
        $changelog = Changelog::query()->create(array_merge(
            $request->validatedChangelogData(),
            [
            'created_by' => $actor,
            'updated_by' => $actor,
            ],
        ));

        $this->logUserActivity(
            activity: 'Created changelog',
            category: 'operations',
            status: 'success',
            user: $request->user(),
            request: $request,
            context: [
                'version' => $changelog->version,
                'title' => $changelog->title,
            ],
        );

        return redirect()
            ->route('administration.changelogs.index')
            ->with('status', "Changelog {$changelog->version} berhasil dibuat.");
    }

    public function edit(Changelog $changelog): View
    {
        return view('auth.administration.changelogs.form', [
            'title' => 'Changelogs',
            'description' => 'Update a changelog record from the administration section.',
            'changelog' => $changelog,
            'isEdit' => true,
        ]);
    }

    public function update(UpdateChangelogRequest $request, Changelog $changelog): RedirectResponse
    {
        $actor = $request->user()?->username ?? $request->user()?->name;

        $changelog->update(array_merge(
            $request->validatedChangelogData(),
            [
            'updated_by' => $actor,
            ],
        ));

        $this->logUserActivity(
            activity: 'Updated changelog',
            category: 'operations',
            status: 'success',
            user: $request->user(),
            request: $request,
            context: [
                'version' => $changelog->version,
                'title' => $changelog->title,
            ],
        );

        return redirect()
            ->route('administration.changelogs.index')
            ->with('status', "Changelog {$changelog->version} berhasil diperbarui.");
    }

    public function destroy(Request $request, Changelog $changelog): RedirectResponse
    {
        abort_unless($request->user()?->canAny(['manage settings', 'delete_changelogs']), 403);

        $version = $changelog->version;
        $changelog->delete();

        $this->logUserActivity(
            activity: 'Deleted changelog',
            category: 'operations',
            status: 'warning',
            user: $request->user(),
            request: $request,
            context: [
                'version' => $version,
            ],
        );

        return redirect()
            ->route('administration.changelogs.index')
            ->with('status', "Changelog {$version} berhasil dihapus.");
    }
}
