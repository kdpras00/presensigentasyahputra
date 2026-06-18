<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $teachers = User::with('teacher')
            ->where('role', 'guru')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('teachers.index', compact('teachers', 'search'));
    }

    public function create()
    {
        $availableClasses = $this->getAvailableClasses();
        return view('teachers.create', compact('availableClasses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'assigned_class' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'guru',
        ]);

        \App\Models\Teacher::create([
            'user_id' => $user->id,
            'assigned_class' => $request->assigned_class,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil ditambahkan.');
    }

    public function edit(User $teacher)
    {
        if ($teacher->role !== 'guru') {
            abort(404);
        }
        $availableClasses = $this->getAvailableClasses();
        return view('teachers.edit', compact('teacher', 'availableClasses'));
    }

    public function update(Request $request, User $teacher)
    {
        if ($teacher->role !== 'guru') {
            abort(404);
        }

        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($teacher->id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($teacher->id)],
            'assigned_class' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'in:man,woman'],
        ]);

        $teacher->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'gender' => $request->gender,
        ]);

        if ($teacher->teacher) {
            $teacher->teacher->update([
                'assigned_class' => $request->assigned_class,
            ]);
        } else {
            \App\Models\Teacher::create([
                'user_id' => $teacher->id,
                'assigned_class' => $request->assigned_class,
            ]);
        }

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $teacher->update([
                'password' => $request->password,
            ]);
        }

        return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroy(User $teacher)
    {
        if ($teacher->role !== 'guru') {
            abort(404);
        }
        
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil dihapus.');
    }

    /**
     * Get unique class values from students table for dropdown.
     */
    private function getAvailableClasses(): array
    {
        return config('school.classes', []);
    }
}
