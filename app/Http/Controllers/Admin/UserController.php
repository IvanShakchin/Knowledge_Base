<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_approved', false)->get();
        $approvedUsers = User::where('is_approved', true)->get();
        
        return view('admin.users.index', compact('users', 'approvedUsers'));
    }

    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);
        
        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно подтвержден.');
    }

    public function reject(User $user)
    {
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'Пользователь отклонен и удален.');
    }

    public function makeAdmin(User $user)
    {
        $user->update(['role' => 'admin']);
        
        return redirect()->route('admin.users.index')->with('success', 'Пользователю назначены права администратора.');
    }

    public function removeAdmin(User $user)
    {
        $user->update(['role' => 'user']);
        
        return redirect()->route('admin.users.index')->with('success', 'Права администратора отозваны.');
    }
}