<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'admin')->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return redirect()->back()->with('success', 'Admin berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Admin berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true]);
    }

    public function customers()
    {
        $customers = User::where('role', 'customer')->latest()->get();
        return view('admin.customers.index', compact('customers'));
    }

    public function showCustomer($id)
    {
        $customer = User::findOrFail($id);
        return response()->json($customer);
    }

    public function destroyCustomer($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();
        return response()->json(['success' => true]);
    }
}
