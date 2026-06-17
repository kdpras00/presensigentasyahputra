<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'assigned_class',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get students that belong to this teacher's assigned class.
     *
     * NOTE: This is NOT an Eloquent relationship — it returns a Collection.
     * Cannot be eager-loaded. Use Student::where('class', ...)->get() directly for queries.
     */
    public function getClassStudents()
    {
        return Student::where('class', $this->assigned_class)->get();
    }
}
