<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'participates', 'project_id', 'user_id')->orderBy('name');
    }

    public function testSuites()
    {
        return $this->hasMany(TestSuite::class, 'project_id');
    }

    public function testCases()
    {
        return $this->hasMany(TestCase::class, 'project_id');
    }
}
