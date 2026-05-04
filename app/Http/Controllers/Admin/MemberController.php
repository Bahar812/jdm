<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MemberRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $members = User::query()
            ->select(['id', 'name', 'email', 'phone', 'role', 'created_at'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.members.index', [
            'members' => $members,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.members.create', [
            'member' => new User,
        ]);
    }

    public function store(MemberRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        User::query()->create($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil ditambahkan.');
    }

    public function edit(User $member): View
    {
        return view('admin.members.edit', [
            'member' => $member,
        ]);
    }

    public function update(MemberRequest $request, User $member): RedirectResponse
    {
        $validated = $request->validated();

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $member->update($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy(User $member): RedirectResponse
    {
        if ($member->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil dihapus.');
    }
}
