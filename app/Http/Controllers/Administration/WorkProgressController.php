<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\StoreWorkProgressRequest;
use App\Http\Requests\Administration\UpdateWorkProgressRequest;
use App\Models\Administration\WorkProgress;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkProgressController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $sort = (string) $request->string('sort', 'updated_at');
        $direction = strtolower((string) $request->string('direction', 'desc'));
        $allowedSorts = ['title', 'owner', 'status', 'priority', 'progress', 'target_date', 'updated_at'];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'updated_at';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $items = WorkProgress::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($workProgressQuery) use ($search) {
                    $workProgressQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('owner', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('priority', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->when($sort !== 'updated_at', fn ($query) => $query->orderByDesc('updated_at'))
            ->when($sort !== 'progress', fn ($query) => $query->orderByDesc('progress'))
            ->paginate(10)
            ->withQueryString();

        return view('auth.administration.work-progress.index', [
            'title' => 'Work Progress',
            'description' => 'Review operational work progress from the administration section.',
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'items' => $items,
        ]);
    }

    public function create(): View
    {
        return view('auth.administration.work-progress.form', [
            'title' => 'Work Progress',
            'description' => 'Create a new work progress record from the administration section.',
            'item' => new WorkProgress([
                'status' => 'planned',
                'priority' => 'medium',
                'progress' => 0,
            ]),
            'isEdit' => false,
        ]);
    }

    public function store(StoreWorkProgressRequest $request): RedirectResponse
    {
        $actor = $request->user()?->username ?? $request->user()?->name;
        $item = WorkProgress::query()->create(array_merge(
            $request->validatedWorkProgressData(),
            [
                'created_by' => $actor,
                'updated_by' => $actor,
            ],
        ));

        return redirect()
            ->route('administration.work-progress.index')
            ->with('status', "Progress {$item->title} berhasil dibuat.");
    }

    public function edit(WorkProgress $workProgress): View
    {
        return view('auth.administration.work-progress.form', [
            'title' => 'Work Progress',
            'description' => 'Update an existing work progress record from the administration section.',
            'item' => $workProgress,
            'isEdit' => true,
        ]);
    }

    public function update(UpdateWorkProgressRequest $request, WorkProgress $workProgress): RedirectResponse
    {
        $actor = $request->user()?->username ?? $request->user()?->name;

        $workProgress->update(array_merge(
            $request->validatedWorkProgressData(),
            ['updated_by' => $actor],
        ));

        return redirect()
            ->route('administration.work-progress.index')
            ->with('status', "Progress {$workProgress->title} berhasil diperbarui.");
    }

    public function destroy(Request $request, WorkProgress $workProgress): RedirectResponse
    {
        abort_unless($request->user()?->canAny(['manage settings', 'delete_work_progress']), 403);

        $title = $workProgress->title;
        $workProgress->delete();

        return redirect()
            ->route('administration.work-progress.index')
            ->with('status', "Progress {$title} berhasil dihapus.");
    }
}
