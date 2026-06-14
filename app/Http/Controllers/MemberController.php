<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $members = User::where('role', 'member') // Pastikan hanya role member
            ->when($search, function($query) use ($search) {
                $cleanSearch = $search;
                if (preg_match('/^(?:#)?MBR-(\d+)$/i', $search, $matches)) {
                    $cleanSearch = (int)$matches[1];
                }

                $query->where(function($q) use ($search, $cleanSearch) {
                    $q->where('name', 'like', "%{$search}%") // Cari berdasarkan nama
                    ->orWhere('email', 'like', "%{$search}%") // Cari berdasarkan email
                    ->orWhere('id', $cleanSearch); // Cari berdasarkan ID
                });
            })
            ->latest()
            ->get();

        return view('pustakawan.member.index', compact('members'));
    }

    public function edit($id)
    {
        $member = User::findOrFail($id);
        return view('pustakawan.member.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|in:aktif,non aktif',
        ]);

        $member->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route(auth()->user()->role . '.member.index')
                        ->with('success', 'Data member berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $member = User::findOrFail($id);
        $member->delete();

        return redirect()->route(auth()->user()->role . '.member.index')
                         ->with('success', 'Data member berhasil dihapus.');
    }
}