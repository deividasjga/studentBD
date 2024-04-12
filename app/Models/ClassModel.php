<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'class';
    protected $fillable = ['name'];

    
    public function subjects()
    {
        return $this->belongsToMany(SubjectModel::class, 'class_subject', 'class_id', 'subject_id')->withTimestamps();
    }

}
