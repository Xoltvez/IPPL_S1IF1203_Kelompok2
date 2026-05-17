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
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%") // Cari berdasarkan nama
                    ->orWhere('email', 'like', "%{$search}%"); // Cari berdasarkan email
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
            // Tambahkan validasi lain jika ada (misal: NIM, nomor telepon)
        ]);

        $member->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('pustakawan.member.index')
                        ->with('success', 'Data member berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $member = User::findOrFail($id);
        $member->delete();

        return redirect()->route('pustakawan.member.index')
                         ->with('success', 'Data member berhasil dihapus.');
    }
}