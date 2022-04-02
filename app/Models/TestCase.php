<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'project_id'];

    public function testSuite()
    {
        return $this->belongsToMany(TestCase::class, 'test_cases_test_suites', 'test_case_id', 'test_suite_id');
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function testSteps()
    {
        return $this->hasMany(TestStep::class, 'test_case_id');
    }
}
