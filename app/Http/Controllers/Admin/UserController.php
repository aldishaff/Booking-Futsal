<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::where('role', 'user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('no_telepon', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(5);

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with([
            'bookings.lapangan',
            'bookings.transaction'
        ])->findOrFail($id);

        $totalBooking = $user->bookings->count();

        $bookingSelesai = $user->bookings
            ->where('status', 'selesai')
            ->count();

        $bookingPending = $user->bookings
            ->where('status', 'pending')
            ->count();

        $transaksiBerhasil = $user->bookings
            ->filter(function ($booking) {
                return $booking->transaction &&
                    $booking->transaction->status == 'paid';
            })->count();

        return view('admin.users.show', compact(
            'user',
            'totalBooking',
            'bookingSelesai',
            'bookingPending',
            'transaksiBerhasil'
        ));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // UPDATE PASSWORD
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        // UPDATE FOTO
        if ($request->hasFile('photo')) {

            $photo = $request->file('photo')->store('profile', 'public');

            $validated['photo'] = $photo;
        }

        // SIMPAN KE DATABASE
        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}