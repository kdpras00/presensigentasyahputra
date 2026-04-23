<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = User::with('teacher')->where('role', 'guru')->latest()->paginate(10);
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $availableClasses = $this->getAvailableClasses();
        return view('teachers.create', compact('availableClasses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => ['nullable', 'string', 'max:255', 'unique:teachers,nip'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'assigned_class' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
        ]);

        \App\Models\Teacher::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'assigned_class' => $request->assigned_class,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $teacher)
    {
        if ($teacher->role !== 'guru') {
            abort(404);
        }
        $availableClasses = $this->getAvailableClasses();
        return view('teachers.edit', compact('teacher', 'availableClasses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $teacher)
    {
        if ($teacher->role !== 'guru') {
            abort(404);
        }

        $request->validate([
            'nip' => ['nullable', 'string', 'max:255', 'unique:teachers,nip,' . ($teacher->teacher ? $teacher->teacher->id : 'NULL')],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $teacher->id],
            'assigned_class' => ['nullable', 'string', 'max:255'],
        ]);

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($teacher->teacher) {
            $teacher->teacher->update([
                'nip' => $request->nip,
                'assigned_class' => $request->assigned_class,
            ]);
        } else {
            \App\Models\Teacher::create([
                'user_id' => $teacher->id,
                'nip' => $request->nip,
                'assigned_class' => $request->assigned_class,
            ]);
        }

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $teacher->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
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
        return Student::select('class')->distinct()->orderBy('class')->pluck('class')->toArray();
    }
}
