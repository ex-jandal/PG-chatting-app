<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // You should know how it works..
        $chartData = Message::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-01") as month'),
            DB::raw('count(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(function ($item) {
            return [
                'date' => $item->month,
                'count' => $item->count
            ];
        });

        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'totalUsers' => User::count(),
                'totalMessages' => message::count(),
                'histroy' => $chartData,
            ]
        ]);
    }

    public function users()
    {
        $users = User::all();

        return Inertia::render('admin/UserManagement', [
            'users' => $users
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:user,admin',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return back();
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:user,admin',
        ]);

        $user->update($validated);

        return back();
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->guard()->id()) {
            abort(403, 'You cannot delete yourself.');
        }

        $user->delete();

        return back()->with('success', 'User removed successfully.');
    }
}
