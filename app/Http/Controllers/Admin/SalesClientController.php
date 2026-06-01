<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SalesClientRequest;
use App\Models\SalesClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesClientController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $status = trim((string) $request->query('status'));
        $businessType = trim((string) $request->query('type'));

        $salesClients = SalesClient::query()
            ->select([
                'id',
                'business_name',
                'business_type',
                'contact_person',
                'phone',
                'status',
                'last_contacted_at',
                'next_follow_up_at',
                'created_at',
            ])
            ->with([
                'latestActivity' => fn ($query) => $query->select([
                    'sales_activities.id',
                    'sales_activities.sales_client_id',
                    'sales_activities.activity_date',
                    'sales_activities.status',
                    'sales_activities.description',
                    'sales_activities.next_follow_up_at',
                    'sales_activities.created_at',
                ]),
            ])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('business_name', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, SalesClient::statuses(), true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->when(in_array($businessType, SalesClient::businessTypes(), true), function ($query) use ($businessType): void {
                $query->where('business_type', $businessType);
            })
            ->latest('last_contacted_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.sales_clients.index', [
            'salesClients' => $salesClients,
            'search' => $search,
            'status' => $status,
            'businessType' => $businessType,
            'statusOptions' => SalesClient::statuses(),
            'businessTypeOptions' => SalesClient::businessTypes(),
        ]);
    }

    public function create(): View
    {
        return view('admin.sales_clients.create', [
            'salesClient' => new SalesClient([
                'business_type' => SalesClient::TYPE_RESTAURANT,
                'status' => SalesClient::STATUS_PROSPECT,
                'last_contacted_at' => now()->toDateString(),
            ]),
            'statusOptions' => SalesClient::statuses(),
            'businessTypeOptions' => SalesClient::businessTypes(),
        ]);
    }

    public function store(SalesClientRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $activityNotes = $validated['activity_notes'] ?? null;
        unset($validated['activity_notes']);

        $validated['created_by'] = auth()->id();

        $salesClient = SalesClient::query()->create($validated);
        $this->recordActivity($salesClient, $activityNotes ?: 'Client sales baru dicatat.');

        return redirect()->route('admin.sales-clients.index')
            ->with('success', 'Data client sales berhasil ditambahkan.');
    }

    public function edit(SalesClient $salesClient): View
    {
        $salesClient->load([
            'activities' => fn ($query) => $query
                ->with('user:id,name')
                ->latest('activity_date')
                ->latest()
                ->take(8),
        ]);

        return view('admin.sales_clients.edit', [
            'salesClient' => $salesClient,
            'statusOptions' => SalesClient::statuses(),
            'businessTypeOptions' => SalesClient::businessTypes(),
        ]);
    }

    public function update(SalesClientRequest $request, SalesClient $salesClient): RedirectResponse
    {
        $validated = $request->validated();
        $activityNotes = $validated['activity_notes'] ?? null;
        unset($validated['activity_notes']);

        $statusBefore = $salesClient->status;
        $lastContactBefore = optional($salesClient->last_contacted_at)->toDateString();
        $nextFollowUpBefore = optional($salesClient->next_follow_up_at)->toDateString();

        $salesClient->update($validated);

        $statusChanged = $statusBefore !== $salesClient->status;
        $contactChanged = $lastContactBefore !== optional($salesClient->last_contacted_at)->toDateString();
        $followUpChanged = $nextFollowUpBefore !== optional($salesClient->next_follow_up_at)->toDateString();

        if ($activityNotes || $statusChanged || $contactChanged || $followUpChanged) {
            $description = $activityNotes ?: $this->activityDescription($statusChanged, $contactChanged, $followUpChanged);
            $this->recordActivity($salesClient, $description);
        }

        return redirect()->route('admin.sales-clients.index')
            ->with('success', 'Data client sales berhasil diperbarui.');
    }

    public function destroy(SalesClient $salesClient): RedirectResponse
    {
        $salesClient->delete();

        return redirect()->route('admin.sales-clients.index')
            ->with('success', 'Data client sales berhasil dihapus.');
    }

    private function recordActivity(SalesClient $salesClient, ?string $description): void
    {
        $salesClient->activities()->create([
            'user_id' => auth()->id(),
            'activity_date' => $salesClient->last_contacted_at ?: now()->toDateString(),
            'status' => $salesClient->status,
            'description' => $description,
            'next_follow_up_at' => $salesClient->next_follow_up_at,
        ]);
    }

    private function activityDescription(bool $statusChanged, bool $contactChanged, bool $followUpChanged): string
    {
        $changes = [];

        if ($statusChanged) {
            $changes[] = 'status client';
        }

        if ($contactChanged) {
            $changes[] = 'tanggal kontak';
        }

        if ($followUpChanged) {
            $changes[] = 'jadwal follow up';
        }

        return 'Update '.implode(', ', $changes).'.';
    }
}
