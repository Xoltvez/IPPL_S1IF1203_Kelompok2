<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PustakawanController extends Controller
{
    // Menampilkan daftar pustakawan
    public function index(Request $request)
    {
        $search = $request->get('search');

        $pustakawans = User::where('role', 'pustakawan')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pustakawan.index', compact('pustakawans'));
    }

    // Menampilkan form tambah pustakawan
    public function create()
    {
        return view('admin.pustakawan.create');
    }

    // Menyimpan pustakawan baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pustakawan',
        ]);

        return redirect()->route('admin.pustakawan.index')
            ->with('success', 'Akun pustakawan baru berhasil ditambahkan.');
    }

    // Menampilkan form edit pustakawan
    public function edit($id)
    {
        $pustakawan = User::findOrFail($id);
        
        // Pastikan hanya user berrole pustakawan yang bisa diubah di sini
        if ($pustakawan->role !== 'pustakawan') {
            abort(403);
        }

        return view('admin.pustakawan.edit', compact('pustakawan'));
    }

    // Memperbarui data pustakawan
    public function update(Request $request, $id)
    {
        $pustakawan = User::findOrFail($id);

        if ($pustakawan->role !== 'pustakawan') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pustakawan->update($data);

        return redirect()->route('admin.pustakawan.index')
            ->with('success', 'Data pustakawan berhasil diperbarui.');
    }

    // Menghapus data pustakawan
    public function destroy($id)
    {
        $pustakawan = User::findOrFail($id);

        if ($pustakawan->role !== 'pustakawan') {
            abort(403);
        }

        $pustakawan->delete();

        return redirect()->route('admin.pustakawan.index')
            ->with('success', 'Akun pustakawan berhasil dihapus.');
    }
}
