<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentStudentModel extends Model
{
    use HasFactory;

    protected $table = 'parent_student';
    protected $fillable = ['parent_id', 'student_id'];


    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }


}
